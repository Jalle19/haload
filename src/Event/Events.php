<?php

namespace Jalle19\Haload\Event;

/**
 * Class Events
 * @package Jalle19\Haload\Event
 */
final class Events
{

	const APPLICATION_STARTED = 'application.started';

	const HAPROXY_DUMP_CONFIGURATION    = 'haproxy.dumpconfiguration';
	const HAPROXY_GET_PROCESS_STATUS    = 'haproxy.getprocesstatus';
	const HAPROXY_CHANGE_PROCESS_STATUS = 'haproxy.changeprocesstatus';

}
