<?php

namespace Jalle19\Haload;

use Auryn\Injector;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Icicle\Loop;
use Jalle19\Haload\Configuration\Configuration;
use Jalle19\Haload\Configuration\Parser as ConfigurationParser;
use Jalle19\Haload\Event\Events;
use Jalle19\Haload\Manager\HaproxyManager;
use Jalle19\Haload\Manager\HttpManager;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Haload
 * @package Jalle19\Haload
 */
class Haload extends Command
{

	const COMMAND_NAME = 'haload';


	/**
	 * @inheritdoc
	 */
	protected function configure()
	{
		$this->setName(self::COMMAND_NAME);
		$this->setDescription('An easy-to-use wrapper around HAproxy');

		// Configure arguments
		$this->addArgument('configurationPath', InputArgument::OPTIONAL, 'The path to the HAproxy configuration file',
			'/etc/haproxy/haproxy.cfg');
		$this->addArgument('bindAddress', InputArgument::OPTIONAL, 'The address to bind to', '::');
		$this->addArgument('bindPort', InputArgument::OPTIONAL, 'The port to bind to', 9712);
	}


	/**
	 * @inheritdoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// Create the configuration and the logger
		$configuration = ConfigurationParser::parseConfiguration($input);
		$logger        = $this->configureLogger($output);
		$this->logConfiguration($logger, $configuration);
		$eventDispatcher = new EventDispatcher();

		// Wire the injector
		$injector = new Injector();

		$aliases = [
			':logger' => $logger,
		];

		$injector->share($configuration)
		         ->share($logger)
		         ->share($eventDispatcher);

		$httpManager    = $injector->make(HttpManager::class, $aliases);
		$haproxyManager = $injector->make(HaproxyManager::class, $aliases);

		// Wire the event dispatcher
		$eventDispatcher->addSubscriber($haproxyManager);
		$eventDispatcher->addSubscriber($httpManager);

		// Start the application
		$eventDispatcher->dispatch(Events::APPLICATION_STARTED);
		Loop\run();
	}


	/**
	 * Configures and returns the logger instance
	 *
	 * @param OutputInterface $output
	 *
	 * @return LoggerInterface
	 */
	private function configureLogger(OutputInterface $output)
	{
		$consoleHandler = new ConsoleHandler($output);
		$consoleHandler->setFormatter(new ColoredLineFormatter(null, "[%datetime%] %level_name%: %message%\n"));

		$logger = new Logger(self::COMMAND_NAME);
		$logger->pushHandler($consoleHandler);
		$logger->pushProcessor(new PsrLogMessageProcessor());

		return $logger;
	}


	/**
	 * @param LoggerInterface $logger
	 * @param Configuration   $configuration
	 */
	private function logConfiguration(LoggerInterface $logger, Configuration $configuration)
	{
		$logger->notice('Loaded application configuration:');
		$logger->notice('    bindAddress: {bindAddress}', ['bindAddress' => $configuration->getBindAddress()]);
		$logger->notice('    bindPort:    {bindPort}', ['bindPort' => $configuration->getBindPort()]);
	}

}
