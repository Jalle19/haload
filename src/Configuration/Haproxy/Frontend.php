<?php

namespace Jalle19\Haload\Configuration\Haproxy;

use Jalle19\HaPHProxy\Section\FrontendSection;

/**
 * Class Frontend
 * @package Jalle19\Haload\Configuration\Haproxy
 */
class Frontend
{

	const DEFAULT_TIMEOUT_CLIENT = '60';

	/**
	 * @var FrontendSection
	 */
	private $rawSection;


	/**
	 * Frontend constructor.
	 *
	 * @param FrontendSection $section
	 */
	public function __construct(FrontendSection $section)
	{
		$this->rawSection = $section;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->rawSection->getName();
	}


	/**
	 * @return array
	 */
	public function getExpectedBackendNames()
	{
		// TCP frontends and HTTP frontends without ACLs only have a single backend
		$mode = $this->rawSection->getParameterByName('mode')->getValue();

		if ($mode === 'tcp' || ($mode === 'http' && !$this->rawSection->hasParameter('acl'))) {
			return [$this->getName() . '-listeners'];
		}

		$backendNames = [];

		foreach ($this->rawSection->getParametersByName('acl') as $acl) {
			list($aclName,) = explode(' ', $acl->getValue(), 2);

			$backendNames[] = $aclName . '-listeners';
		}

		// Multiple ACLs can point at the same backend
		return array_unique($backendNames);
	}

}
