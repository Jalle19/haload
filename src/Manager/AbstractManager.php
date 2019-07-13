<?php

namespace Jalle19\Haload\Manager;

use Jalle19\Haload\Configuration\Configuration;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class AbstractManager
 * @package Jalle19\Haload\Manager
 */
abstract class AbstractManager
{

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var EventDispatcher
	 */
	protected $eventDispatcher;


	/**
	 * AbstractManager constructor.
	 *
	 * @param Configuration   $configuration
	 * @param LoggerInterface $logger
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger, EventDispatcher $eventDispatcher)
	{
		$this->configuration   = $configuration;
		$this->logger          = $logger;
		$this->eventDispatcher = $eventDispatcher;
	}

}
