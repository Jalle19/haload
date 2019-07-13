<?php

namespace Jalle19\Haload\Utility;

use Icicle\Log\Log;

/**
 * Logger which does nothing. For some reason icicleio/http decides to 1) use a non-standard logger interface and 2)
 * create a logger if null is passed. This contraption silences it.
 *
 * @package Jalle19\Haload\Utility
 */
class IcicleNullLogger implements Log
{

	/**
	 * @inheritDoc
	 */
	public function log($level, $format /* , ...$args */)
	{

	}


	/**
	 * @inheritDoc
	 */
	public function getLevel()
	{

	}

}
