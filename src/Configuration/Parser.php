<?php

namespace Jalle19\Haload\Configuration;

use Jalle19\Haload\Exception\ParseException;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Parser
 * @package Jalle19\Haload\Configuration
 */
class Parser
{

	/**
	 * @param InputInterface $input
	 *
	 * @return Configuration
	 *
	 * @throws ParseException
	 */
	public static function parseConfiguration(InputInterface $input)
	{
		$configuration = new Configuration();

		$configuration->setConfigurationPath(self::validateConfigurationPath($input->getArgument('configurationPath')))
		              ->setBindAddress($input->getArgument('bindAddress'))
		              ->setBindPort(self::validateBindPort($input->getArgument('bindPort')));

		return $configuration;
	}


	/**
	 * @param string $configurationPath
	 *
	 * @return string
	 * @throws ParseException
	 */
	private static function validateConfigurationPath($configurationPath)
	{
		if (!file_exists($configurationPath) || !is_writable($configurationPath)) {
			throw new ParseException('configurationPath does not exist or is not writable');
		}

		return $configurationPath;
	}


	/**
	 * @param string $bindPort
	 *
	 * @return string
	 * @throws ParseException
	 */
	private static function validateBindPort($bindPort)
	{
		if ($bindPort < 1 || $bindPort > 65535) {
			throw new ParseException('bindPort is out of range');
		}

		return $bindPort;
	}

}
