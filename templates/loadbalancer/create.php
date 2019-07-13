<?php

/* @var string $title */
$this->layout('layout', ['title' => $title]);

?>
<form class="form-horizontal" method="POST" action="/loadbalancer/create">
	<div class="row">

		<div class="col-md-6">
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="name" name="name">
				</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="well">
				<input type="submit" class="btn btn-primary" value="Save changes">
			</div>
		</div>
	</div>
</form>
