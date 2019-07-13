<?php

namespace Jalle19\Haload\Manager;

use Auryn\Injector;
use Icicle\Http\Message\BasicResponse;
use Icicle\Http\Message\Request;
use Icicle\Http\Server\RequestHandler;
use Icicle\Http\Server\Server;
use Icicle\Psr7Bridge\MessageFactory;
use Icicle\Socket\Socket;
use Jalle19\Haload\Configuration\Configuration;
use Jalle19\Haload\Event\Events;
use Jalle19\Haload\Http\FlashMessage\FlashMessageCollection;
use Jalle19\Haload\Http\Router;
use Jalle19\Haload\Utility\IcicleNullLogger;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Phroute\Phroute\HandlerResolverInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zend\Diactoros\Response as Psr7Response;

/**
 * Class HttpManager
 * @package Jalle19\Haload\Manager
 */
class HttpManager extends AbstractManager implements RequestHandler, HandlerResolverInterface, EventSubscriberInterface
{

	/**
	 * @var Injector
	 */
	private $injector;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @var ServerRequestInterface
	 */
	private $request;


	/**
	 * @inheritDoc
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger, EventDispatcher $eventDispatcher)
	{
		parent::__construct($configuration, $logger, $eventDispatcher);

		// Configure the controller injector and router
		$this->injector = new Injector();
		$this->injector->share($this->eventDispatcher)
		               ->share(new FlashMessageCollection());

		$this->router = new Router();
	}


	/**
	 * @inheritdoc
	 */
	public function onRequest(Request $request, Socket $socket)
	{
		// Use ourselves as the resolver
		$dispatcher = new Dispatcher($this->router->getData(), $this);

		// Convert the Icicle request to a PSR-7 request and share it to all instantiated controllers
		$factory    = new MessageFactory();
		$psrRequest = $factory->createServerRequest($request);

		$this->request = $psrRequest;
		$this->injector->share($psrRequest);

		/* @var Psr7Response $psrResponse */
		try {
			$psrResponse = $dispatcher->dispatch($request->getMethod(),
				$request->getUri()->getPath());

			if ($psrResponse !== null) {
				$icicleResponse = new BasicResponse($psrResponse->getStatusCode(), $psrResponse->getHeaders());

				yield $icicleResponse->getBody()->end((string)$psrResponse->getBody());
				yield $icicleResponse;
			}
		} catch (HttpMethodNotAllowedException $e) {
			$response = new BasicResponse(405);

			yield $response->getBody()->end('Method not allowed');
			yield $response;
		} catch (\Exception $e) {
			$this->logger->error('Uncaught exception {exception}: {message}', [
				'exception' => get_class($e),
				'message'   => $e->getMessage(),
			]);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function onError($code, Socket $socket)
	{
		return new BasicResponse($code);
	}


	/**
	 * @inheritDoc
	 */
	public function resolve($handler)
	{
		list($controllerName, $method) = $handler;

		return [$this->injector->make($controllerName, [':request' => $this->request]), $method];
	}


	/**
	 * @inheritDoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::APPLICATION_STARTED => 'onApplicationStarted',
		];
	}


	/**
	 * Handles the APPLICATION_STARTED event. The HTTP server is started here
	 */
	public function onApplicationStarted()
	{
		$server = new Server($this, new IcicleNullLogger());
		$server->listen($this->configuration->getBindPort(), $this->configuration->getBindAddress());

		$this->logger->notice('HTTP server started on http://{bindAddressPort}', [
			'bindAddressPort' => $this->configuration->getBindAddressPort(),
		]);
	}

}
