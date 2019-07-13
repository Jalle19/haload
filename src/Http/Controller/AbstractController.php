<?php

namespace Jalle19\Haload\Http\Controller;

use Jalle19\Haload\Event\Events;
use Jalle19\Haload\Event\Haproxy\DumpConfigurationEvent;
use Jalle19\Haload\Event\Haproxy\GetProcessStatusEvent;
use Jalle19\Haload\Http\FlashMessage\FlashMessageCollection;
use Jalle19\HaPHProxy\Configuration;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zend\Diactoros\Response;

/**
 * Class AbstractController
 * @package Jalle19\Haload\Http\Controller
 */
abstract class AbstractController
{

	/**
	 * @var EventDispatcher
	 */
	protected $eventDispatcher;

	/**
	 * @var Engine
	 */
	protected $templateEngine;

	/**
	 * @var FlashMessageCollection
	 */
	protected $flashMessages;

	/**
	 * @var string
	 */
	protected $processStatus;

	/**
	 * @var ServerRequestInterface
	 */
	protected $request;


	/**
	 * AbstractController constructor.
	 *
	 * @param EventDispatcher        $eventDispatcher
	 * @param FlashMessageCollection $flashMessageCollection
	 * @param ServerRequestInterface $request
	 */
	public function __construct(
		EventDispatcher $eventDispatcher,
		FlashMessageCollection $flashMessageCollection,
		ServerRequestInterface $request
	) {
		$this->eventDispatcher = $eventDispatcher;
		$this->flashMessages   = $flashMessageCollection;
		$this->request         = $request;
		$this->templateEngine  = $this->configureTemplateEngine();

		$this->refreshProcessStatus();
		$this->init();
	}


	/**
	 * @return Engine
	 */
	public function configureTemplateEngine()
	{
		$templatePath = __DIR__ . '/../../../templates';

		$templateEngine = new Engine($templatePath);
		$templateEngine->addFolder('index', $templatePath . '/index');
		$templateEngine->addFolder('haproxy', $templatePath . '/haproxy');
		$templateEngine->addFolder('loadbalancer', $templatePath . '/loadbalancer');

		$templateEngine->addData([
			'flashMessages' => $this->flashMessages,
		]);

		return $templateEngine;
	}


	/**
	 * Called after the constructor has finished
	 */
	protected function init()
	{

	}


	/**
	 * @param string $body
	 * @param int    $statusCode (optional)
	 * @param array  $headers    (optional)
	 *
	 * @return ResponseInterface
	 */
	protected function createResponse($body, $statusCode = 200, $headers = [])
	{
		// Use text/html as the default Content-Type
		if (!isset($headers['Content-Type'])) {
			$headers['Content-Type'] = 'text/html';
		}

		$response = new Response();

		if ($response->getBody()->isWritable()) {
			$response->getBody()->write($body);
		}

		$response = $response->withStatus($statusCode);

		foreach ($headers as $name => $value) {
			$response = $response->withAddedHeader($name, $value);
		}

		return $response;
	}


	/**
	 * @param string $url
	 *
	 * @return ResponseInterface
	 */
	protected function redirect($url)
	{
		return $this->createResponse('', 302, [
			'Location' => $url,
		]);
	}


	/**
	 * @return string
	 */
	protected function refreshProcessStatus()
	{
		/* @var GetProcessStatusEvent $event */
		$event = $this->eventDispatcher->dispatch(Events::HAPROXY_GET_PROCESS_STATUS,
			new GetProcessStatusEvent());

		$this->processStatus = $event->getProcessStatus();

		// Make the current process status available to the template engine
		$this->templateEngine->addData([
			'processStatus' => $this->processStatus,
		]);
	}


	/**
	 * @param string $type
	 *
	 * @return Configuration
	 */
	protected function getConfiguration($type)
	{
		/* @var DumpConfigurationEvent $event */
		$event = $this->eventDispatcher->dispatch(Events::HAPROXY_DUMP_CONFIGURATION,
			new DumpConfigurationEvent($type));

		return $event->getConfiguration();
	}

}
