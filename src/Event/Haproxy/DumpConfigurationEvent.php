<?php

namespace Jalle19\Haload\Event\Haproxy;

use Jalle19\HaPHProxy\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DumpConfigurationEvent
 * @package Jalle19\Haload\Event\Haproxy
 */
class DumpConfigurationEvent extends Event
{

	const CONFIGURATION_TYPE_CURRENT = 'current';
	const CONFIGURATION_TYPE_PENDING = 'pending';

	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * @var string
	 */
	private $type;


	/**
	 * DumpConfigurationEvent constructor.
	 *
	 * @param string $type
	 */
	public function __construct($type)
	{
		$this->type = $type;
	}


	/**
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}


	/**
	 * @param Configuration $configuration
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

}
