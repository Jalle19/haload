<?php

/* @var string $title */

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $this->e($title); ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/styles.css">
</head>
<body>

<div class="container">
	<?php $this->insert('header'); ?>
	<?php echo $this->section('content'); ?>
</div>

<script src="/scripts.js"></script>

</body>
</html>
