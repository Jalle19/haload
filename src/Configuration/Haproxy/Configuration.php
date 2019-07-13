<?php

namespace Jalle19\Haload\Configuration\Haproxy;

/**
 * Class Configuration
 * @package Jalle19\Haload\Configuration\Haproxy
 */
class Configuration
{

	/**
	 * @var LoadBalancer[]
	 */
	private $loadBalancers;


	/**
	 * @return LoadBalancer[]
	 */
	public function getLoadBalancers()
	{
		return $this->loadBalancers;
	}


	/**
	 * @param LoadBalancer $loadBalancer
	 */
	public function addLoadBalancer(LoadBalancer $loadBalancer)
	{
		$this->loadBalancers[] = $loadBalancer;
	}

}
