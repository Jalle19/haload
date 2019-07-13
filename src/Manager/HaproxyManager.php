<?php

namespace Jalle19\Haload\Manager;

use Jalle19\Haload\Configuration\Haproxy\Backend;
use Jalle19\Haload\Configuration\Haproxy\Configuration;
use Jalle19\Haload\Configuration\Haproxy\Frontend;
use Jalle19\Haload\Configuration\Haproxy\Listener;
use Jalle19\Haload\Configuration\Haproxy\LoadBalancer;
use Jalle19\Haload\Event\Events;
use Jalle19\Haload\Event\Haproxy\ChangeProcessStatusEvent;
use Jalle19\Haload\Event\Haproxy\DumpConfigurationEvent;
use Jalle19\Haload\Event\Haproxy\GetProcessStatusEvent;
use Jalle19\Haload\Exception\ProcessException;
use Jalle19\HaPHProxy\Configuration as HaphproxyConfiguration;
use Jalle19\HaPHProxy\Exception\BaseException;
use Jalle19\HaPHProxy\Parser;
use Jalle19\HaPHProxy\Section\BackendSection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class HaproxyManager
 * @package Jalle19\Haload\Manager
 */
class HaproxyManager extends AbstractManager implements EventSubscriberInterface
{

	/**
	 * @var HaphproxyConfiguration
	 */
	private $rawConfiguration;

	/**
	 * @var Configuration
	 */
	private $currentConfiguration;

	/**
	 * @var Configuration
	 */
	private $pendingConfiguration;


	/**
	 * @inheritDoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::APPLICATION_STARTED           => 'onApplicationStarted',
			Events::HAPROXY_DUMP_CONFIGURATION    => 'onDumpConfiguration',
			Events::HAPROXY_GET_PROCESS_STATUS    => 'onGetProcessStatus',
			Events::HAPROXY_CHANGE_PROCESS_STATUS => 'onChangeProcessStatus',
		];
	}


	/**
	 * Handles the APPLICATION_STARTED event. The haproxy configuration is read here.
	 */
	public function onApplicationStarted()
	{
		try {
			$configurationPath      = $this->configuration->getConfigurationPath();
			$parser                 = new Parser($configurationPath);
			$this->rawConfiguration = $parser->parse();

			$this->logger->notice('Loaded HAproxy configuration from {configurationPath}', [
				'configurationPath' => $configurationPath,
			]);

			// Parse the raw configuration into our own representation of it
			$this->currentConfiguration = $this->parseConfiguration();

			// Use the parsed configuration as pending configuration too
			$this->pendingConfiguration = clone $this->currentConfiguration;
		} catch (BaseException $e) {
			$this->logger->error('Failed to parse HAproxy configuration: {message}', [
				'message' => $e->getMessage(),
			]);
		}
	}


	/**
	 * Handles the HAPROXY_DUMP_CONFIGURATION event
	 *
	 * @param DumpConfigurationEvent $event
	 */
	public function onDumpConfiguration(DumpConfigurationEvent $event)
	{
		switch ($event->getType()) {
			case DumpConfigurationEvent::CONFIGURATION_TYPE_CURRENT:
				$event->setConfiguration($this->rawConfiguration);
				break;
			case DumpConfigurationEvent::CONFIGURATION_TYPE_PENDING:
				// TODO: Convert pending to haphproxy configuration
				$event->setConfiguration($this->rawConfiguration);
				break;
		}
	}


	/**
	 * @param GetProcessStatusEvent $event
	 */
	public function onGetProcessStatus(GetProcessStatusEvent $event)
	{
		$builder = new ProcessBuilder(['pgrep', 'haproxy']);
		$process = $builder->getProcess();
		$process->run();

		// No output means no process is running
		$output        = $process->getOutput();
		$processStatus = empty($output) ? GetProcessStatusEvent::PROCESS_STATUS_STOPPED : GetProcessStatusEvent::PROCESS_STATUS_STARTED;

		$event->setProcessStatus($processStatus);
	}


	/**
	 * @param ChangeProcessStatusEvent $event
	 *
	 * @throws ProcessException if the process failed to change state
	 */
	public function onChangeProcessStatus(ChangeProcessStatusEvent $event)
	{
		$builder = new ProcessBuilder(['service', 'haproxy', $event->getAction()]);
		$process = $builder->getProcess();
		$process->run();

		if (!$process->isSuccessful()) {
			throw new ProcessException('Failed to ' . $event->getAction() . ' process: ' . $process->getErrorOutput());
		}

		$this->logger->notice('Process status changed successfully ({action})', [
			'action' => $event->getAction(),
		]);
	}


	/**
	 * @return Configuration
	 */
	private function parseConfiguration()
	{
		$configuration = new Configuration();

		// Create a load balancer for each frontend
		foreach ($this->rawConfiguration->getFrontendSections() as $section) {
			$loadBalancer = new LoadBalancer();
			$loadBalancer->setFrontend(new Frontend($section));
			$configuration->addLoadBalancer($loadBalancer);
		}

		// Use the frontends to find the matching backends
		foreach ($configuration->getLoadBalancers() as $loadBalancer) {
			$frontend             = $loadBalancer->getFrontend();
			$expectedBackendNames = $frontend->getExpectedBackendNames();

			foreach ($this->rawConfiguration->getBackendSections() as $section) {
				/* @var BackendSection $section */
				foreach ($expectedBackendNames as $expectedBackendName) {
					if ($section->getName() === $expectedBackendName) {
						$backend = new Backend($section->getName());

						// Parse listeners
						foreach ($section->getParametersByName('server') as $parameter) {
							preg_match_all('/(.*) (.*):([0-9]+)/i', $parameter->getValue(), $result);

							$backend->addListener(new Listener($result[1][0], $result[2][0], $result[3][0]));
						}

						$loadBalancer->addBackend($backend);
					}
				}
			}
		}

		return $configuration;
	}

}
