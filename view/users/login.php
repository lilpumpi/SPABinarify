<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Login");
$errors = $view->getVariable("errors");
?>

<div id="login">
    <div id="imagen">
        <img src="/mvcbinarify/img/binarify.png" alt="Binarify">
    </div>
    <?= isset($errors["general"])?$errors["general"]:"" ?>

    <form action="index.php?controller=users&amp;action=login" method="POST">
        <input type="text" name="username" placeholder="<?= i18n("Username")?>" required>
        <input type="password" name="passwd" placeholder="<?= i18n("Password")?>" required>
        <input type="submit" value="<?= i18n("Log in") ?>">
    </form>

    <div id="enlace">
        <a href="index.php?controller=users&amp;action=register"><?= i18n("Register")?></a>
    </div>
</div>

<?php $view->moveToFragment("css");?>
<link rel="stylesheet" type="text/css" src="css/login.css">
<?php $view->moveToDefaultFragment(); ?>
