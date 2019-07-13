<?php

namespace Jalle19\Haload\Http\Controller;

use Jalle19\Haload\Event\Haproxy\DumpConfigurationEvent;
use Psr\Http\Message\ResponseInterface;

/**
 * Class IndexController
 * @package Jalle19\Haload\Http\Controller
 */
class IndexController extends AbstractController
{

	/**
	 * @return ResponseInterface
	 */
	public function indexAction()
	{
		$configuration = $this->getConfiguration(DumpConfigurationEvent::CONFIGURATION_TYPE_PENDING);

		$body = $this->templateEngine->render('index::index', [
			'title'         => 'Dashboard',
			'configuration' => $configuration,
		]);

		return $this->createResponse($body);
	}

}
