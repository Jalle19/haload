<?php

namespace Jalle19\Haload\Event\Haproxy;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class GetProcessStatusEvent
 * @package Jalle19\Haload\Event\Haproxy
 */
class GetProcessStatusEvent extends Event
{

	const PROCESS_STATUS_UNKNOWN = 'unknown';
	const PROCESS_STATUS_STARTED = 'started';
	const PROCESS_STATUS_STOPPED = 'stopped';

	/**
	 * @var string
	 */
	private $processStatus = self::PROCESS_STATUS_UNKNOWN;


	/**
	 * @return string
	 */
	public function getProcessStatus()
	{
		return $this->processStatus;
	}


	/**
	 * @param string $processStatus
	 */
	public function setProcessStatus($processStatus)
	{
		$this->processStatus = $processStatus;
	}

}
