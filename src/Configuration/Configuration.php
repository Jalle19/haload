<?php

namespace Jalle19\Haload\Configuration;

/**
 * Class Configuration
 * @package Jalle19\Haload\Configuration
 */
class Configuration
{

	/**
	 * @var string
	 */
	private $configurationPath;

	/**
	 * @var string
	 */
	private $bindAddress;

	/**
	 * @var int
	 */
	private $bindPort;


	/**
	 * @return string
	 */
	public function getConfigurationPath()
	{
		return $this->configurationPath;
	}


	/**
	 * @param string $configurationPath
	 *
	 * @return Configuration
	 */
	public function setConfigurationPath($configurationPath)
	{
		$this->configurationPath = $configurationPath;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getBindAddress()
	{
		return $this->bindAddress;
	}


	/**
	 * @param string $bindAddress
	 *
	 * @return Configuration
	 */
	public function setBindAddress($bindAddress)
	{
		$this->bindAddress = $bindAddress;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getBindPort()
	{
		return $this->bindPort;
	}


	/**
	 * @param int $bindPort
	 *
	 * @return Configuration
	 */
	public function setBindPort($bindPort)
	{
		$this->bindPort = $bindPort;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getBindAddressPort()
	{
		$address = $this->getBindAddress();

		if (strpos($address, ':') !== false) {
			$address = '[' . $address . ']';
		}

		return $address . ':' . $this->getBindPort();
	}

}
