<?php

namespace Jalle19\Haload\Configuration\Haproxy;

/**
 * Class Backend
 * @package Jalle19\Haload\Configuration\Haproxy
 */
class Backend
{

	const DEFAULT_TIMEOUT_CONNECT = 60;
	const DEFAULT_TIMEOUT_SERVER  = 60;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Listener[]
	 */
	private $listeners = [];

	/**
	 * @var int
	 */
	private $timeoutConnect = self::DEFAULT_TIMEOUT_CONNECT;

	/**
	 * @var int
	 */
	private $timeoutServer = self::DEFAULT_TIMEOUT_SERVER;


	/**
	 * Frontend constructor.
	 *
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return Listener[]
	 */
	public function getListeners()
	{
		return $this->listeners;
	}


	/**
	 * @param Listener $listener
	 */
	public function addListener(Listener $listener)
	{
		$this->listeners[] = $listener;
	}

}
