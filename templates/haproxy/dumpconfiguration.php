<?php

/* @var string $title */
/* @var string $configuration */

$this->layout('layout', ['title' => $title]);

?>
<div class="row">
	<div class="col-md-12">
		<h1><?php echo $this->e($title); ?></h1>

		<pre>
<?php echo $configuration; ?>
		</pre>
	</div>
</div>

