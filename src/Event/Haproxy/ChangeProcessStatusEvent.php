<?php

namespace Jalle19\Haload\Event\Haproxy;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ChangeProcessStatusEvent
 * @package Jalle19\Haload\Event\Haproxy
 */
class ChangeProcessStatusEvent extends Event
{

	const ACTION_START   = 'start';
	const ACTION_RESTART = 'restart';
	const ACTION_STOP    = 'stop';

	/**
	 * @var string
	 */
	private $action;


	/**
	 * ChangeProcessStatusEvent constructor.
	 *
	 * @param string $action
	 */
	public function __construct($action)
	{
		$this->action = $action;
	}


	/**
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}

}
