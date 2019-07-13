<?php

namespace Jalle19\Haload\Http\Controller;

use Jalle19\Haload\Event\Events;
use Jalle19\Haload\Event\Haproxy\ChangeProcessStatusEvent;
use Jalle19\Haload\Event\Haproxy\DumpConfigurationEvent;
use Jalle19\Haload\Event\Haproxy\GetProcessStatusEvent;
use Jalle19\Haload\Http\FlashMessage\FlashMessage;
use Jalle19\HaPHProxy\Writer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class HaproxyController
 * @package Jalle19\Haload\Http\Controller
 */
class HaproxyController extends AbstractController
{
	
	/**
	 * @param string $type the type of configuration to dump
	 *
	 * @return ResponseInterface
	 *
	 * @throws BadRequestHttpException
	 */
	public function dumpConfigurationAction($type)
	{
		// Validate the specified type
		if ($type !== DumpConfigurationEvent::CONFIGURATION_TYPE_CURRENT &&
			$type !== DumpConfigurationEvent::CONFIGURATION_TYPE_PENDING
		) {
			throw new BadRequestHttpException('Invalid type specified');
		}

		$writer = new Writer($this->getConfiguration($type));
		$dump   = $writer->dump();

		$body = $this->templateEngine->render('haproxy::dumpconfiguration', [
			'title'         => 'Configuration dump (' . $type . ')',
			'configuration' => $dump,
		]);

		return $this->createResponse($body);
	}


	/**
	 * @return ResponseInterface
	 */
	public function startProcessAction()
	{
		$this->eventDispatcher->dispatch(Events::HAPROXY_CHANGE_PROCESS_STATUS,
			new ChangeProcessStatusEvent(ChangeProcessStatusEvent::ACTION_START));

		$this->refreshProcessStatus();

		if ($this->processStatus === GetProcessStatusEvent::PROCESS_STATUS_STARTED) {
			$this->flashMessages->addMessage(new FlashMessage(FlashMessage::TYPE_SUCCESS,
				'Process successfully started'));
		}

		return $this->redirect('/');
	}


	/**
	 * @return ResponseInterface
	 */
	public function restartProcessAction()
	{
		$this->eventDispatcher->dispatch(Events::HAPROXY_CHANGE_PROCESS_STATUS,
			new ChangeProcessStatusEvent(ChangeProcessStatusEvent::ACTION_RESTART));

		$this->refreshProcessStatus();

		if ($this->processStatus === GetProcessStatusEvent::PROCESS_STATUS_STARTED) {
			$this->flashMessages->addMessage(new FlashMessage(FlashMessage::TYPE_SUCCESS,
				'Process successfully restarted'));
		}

		return $this->redirect('/');
	}


	/**
	 * @return ResponseInterface
	 */
	public function stopProcessAction()
	{
		$this->eventDispatcher->dispatch(Events::HAPROXY_CHANGE_PROCESS_STATUS,
			new ChangeProcessStatusEvent(ChangeProcessStatusEvent::ACTION_STOP));

		$this->refreshProcessStatus();

		if ($this->processStatus === GetProcessStatusEvent::PROCESS_STATUS_STOPPED) {
			$this->flashMessages->addMessage(new FlashMessage(FlashMessage::TYPE_SUCCESS,
				'Process successfully stopped'));
		}

		return $this->redirect('/');
	}

}
