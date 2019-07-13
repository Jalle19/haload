<?php

/* @var string $title */

$this->layout('layout', ['title' => $title]);

?>
<div class="row">
	<div class="col-md-12">
		<?php
		
		var_dump($configuration);
		
		?>
		<p>
			This is the dashboard
		</p>

		<div class="well">
			<a class="btn btn-primary" href="/loadbalancer/create">Create load balancer</a>
		</div>
	</div>
</div>

