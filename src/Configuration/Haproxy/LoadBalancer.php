<?php

namespace Jalle19\Haload\Configuration\Haproxy;

/**
 * Class LoadBalancer
 * @package Jalle19\Haload\Configuration\Haproxy
 */
class LoadBalancer
{

	/**
	 * @var Frontend
	 */
	private $frontend;

	/**x
	 * @var Backend[]
	 */
	private $backends;


	/**
	 * @return Frontend
	 */
	public function getFrontend()
	{
		return $this->frontend;
	}


	/**
	 * @param Frontend $frontend
	 */
	public function setFrontend($frontend)
	{
		$this->frontend = $frontend;
	}


	/**
	 * @return Backend[]
	 */
	public function getBackends()
	{
		return $this->backends;
	}


	/**
	 * @param Backend $backend
	 */
	public function addBackend(Backend $backend)
	{
		$this->backends[] = $backend;
	}

}
