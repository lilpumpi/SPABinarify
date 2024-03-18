<?php
// file: view/layouts/register.php

$view = ViewManager::getInstance();

?><!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="css/registro.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;600&family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>

</head>
<body>
	<main>
		<!-- flash message -->
		<div id="flash">
			<?= $view->popFlash() ?>
		</div>
		<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
	</main>
</body>
<footer id="footer">
	<?php
	include(__DIR__."/language_select_element.php");
	?>
</footer>
</html>