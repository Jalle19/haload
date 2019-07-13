<?php

/* @var \Jalle19\Haload\Http\FlashMessage\FlashMessageCollection $flashMessages */
use Jalle19\Haload\Http\FlashMessage\FlashMessage;

/* @var $processStatus string */

?>
	<nav class="navbar navbar-default">
		<div class="container-fluid">

			<div class="navbar-header">
				<a class="navbar-brand" href="/">Haload</a>
			</div>

			<ul class="nav navbar-nav">
				<li><a href="/">Dashboard</a></li>
			</ul>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
						   aria-expanded="false">Configuration <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="/haproxy/dump-configuration/current">Dump current configuration</a></li>
							<li><a href="/haproxy/dump-configuration/pending">Dump pending configuration</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
						   aria-expanded="false">Manage daemon <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<?php

							if ($processStatus === \Jalle19\Haload\Event\Haproxy\GetProcessStatusEvent::PROCESS_STATUS_STARTED) {
								?>
								<li class="disabled"><a href="/haproxy/start">Start process</a></li><?php
							} else {
								?>
								<li><a href="/haproxy/start">Start process</a></li><?php
							}

							?>
							<li><a href="/haproxy/restart">Restart process</a></li>
							<li><a href="/haproxy/stop">Stop process</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
<?php

foreach ($flashMessages->getMessages() as $type => $messages) {
	// Convert the type to the corresponding bootstrap class
	switch ($type) {
		case FlashMessage::TYPE_ERROR:
			$bootstrapType = 'danger';
			break;
		default:
			$bootstrapType = $type;
	}

	$className = 'alert alert-' . $bootstrapType;

	foreach ($messages as $message) {
		/* @var FlashMessage $message */
		?>
		<div class="<?php echo $className; ?>"><?php echo $this->e($message->getContent()); ?></div>
		<?php
	}
}

$flashMessages->reset();
