<?php
//file: view/users/registro.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Registro");
$errors = $view->getVariable("errors");
?>

<div id="registro">
    <div id="imagen">
        <img src="/mvcbinarify/img/binarify.png" alt="Binarify">
    </div>

    <form action="index.php?controller=users&amp;action=register" method="post">
        
        <div id="user"><input type="text" name="username" id="usuario" placeholder="<?= i18n("Nombre de usuario") ?>" required></div>
        <div id="contraseña"><input type="password" name="passwd" id="password" placeholder="<?= i18n("Contraseña") ?>" required></div>
        <div id="correo"><input type="email" name="email" id="email" placeholder="example@gmail.com"></div>
        
        <div id="boton"><input type="submit" id="btLogin" name="boton" value="Registrarse"></div>

        <div id="enlace">
            <a href="index.php?controller=users&amp;action=login"><?= i18n("Log in") ?></a>
        </div>
        
    </form>
</div>

<?php $view->moveToFragment("css");?>
<link rel="stylesheet" type="text/css" src="css/registro.css">
<?php $view->moveToDefaultFragment(); ?>