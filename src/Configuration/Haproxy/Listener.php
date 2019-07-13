<?php

namespace Jalle19\Haload\Configuration\Haproxy;

/**
 * Class Listener
 * @package Jalle19\Haload\Configuration\Haproxy
 */
class Listener
{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $hostname;

	/**
	 * @var int
	 */
	private $port;


	/**
	 * Listener constructor.
	 *
	 * @param string $name
	 * @param string $hostname
	 * @param int    $port
	 */
	public function __construct($name, $hostname, $port)
	{
		$this->name     = $name;
		$this->hostname = $hostname;
		$this->port     = $port;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getHostname()
	{
		return $this->hostname;
	}


	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}
	
}
