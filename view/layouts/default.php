<?php
//file: view/layouts/default.php

$view = ViewManager::getInstance();
$currentuser = $view->getVariable("currentusername");

?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;600&family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/76845d9a4c.js" crossorigin="anonymous"></script>
	<script src="index.php?controller=language&amp;action=i18njs"></script>

	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>
</head>
<body>
	<!-- Header con el logotipo de la página web -->
    <header>
        <div class="logo">
            <img src="/mvcbinarify/img/binarify.png" alt="Binarify">
        </div>
        <div class="menu-icon">
            <i class="fa-solid fa-bars"></i>
        </div>
    </header>

     <!-- Menu navegacion -->
     <div id="containerMenu">
        <div id="contMenu">
            <nav>
                <a href="index.php?controller=switchs&amp;action=dashboard"><?= i18n("Dashboard") ?></a>
                <a href="index.php?controller=switchs&amp;action=index">Switches</a>
                <a href="index.php?controller=suscriptions&amp;action=index"><?= i18n("Suscriptions") ?></a>
                <a href="index.php?controller=switchs&amp;action=add"><?= i18n("Create") ?></a>
                <a href="index.php?controller=users&amp;action=logout"><?= i18n("Log out") ?></a>
            </nav>
            <i id="cerrar" class="fa-solid fa-xmark"></i>
        </div>
    </div>

     <!-- Contenido de la pagina -->
     <main class="main-content">

        <div id="flash">
			<?= $view->popFlash() ?>
		</div>

        

		<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>

    </main>

	<footer id="footer">
		<?php
		include(__DIR__."/language_select_element.php");
		?>
	</footer>

    <script src="/mvcbinarify/js/menu_desplegable.js"></script>
    <script src="/mvcbinarify/js/modal.js"></script>

</body>
</html>
