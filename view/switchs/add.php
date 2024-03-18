<?php
//file: view/switchs/add.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switchs = $view->getVariable("switch");
$errors = $view->getVariable("errors");
$currentuser = $view->getVariable("currentusername");

$view->setVariable("title", "Add Switch");

?>

<div id="form-container">
    <div id="barra">
        <h3><?= i18n("Create Switch") ?></h3>
    </div>

    <div id="formulario">
        <form action="index.php?controller=switchs&amp;action=add" method="POST"> 
            <div id="cont-name" class="input">
                <label for="name"><?= i18n("Name") ?>: </label>
                <input type="text" name="name" id="nombre" placeholder="<?= i18n("Nombre") ?>" required>
            </div>

            <div id="cont-desc" class="input">
                <label for="description"><?= i18n("Description") ?>: </label>
                <textarea name="description" id="description" cols="70" rows="10" maxlength="400" placeholder="<?= i18n("Descripcion") ?>" required></textarea>
            </div>

            <div id="cont-time" class="input">
                <label for="time"><?= i18n("Time") ?>: </label>
                <input type="number" name="auto_off_time" id="time" placeholder="<?= i18n("Time") ?> (min)" required>
            </div>
            
            <div id="botones">
                <button type="submit" name="submit" id="crear"><?= i18n("Create") ?></button>
                <button type="button" id="cancelar"><a href="index.php?controller=switchs&amp;action=dashboard"><?= i18n("Cancel") ?></a></button>
            </div>
        </form>
    </div>
</div>

<?php $view->moveToFragment("css");?>
    <link rel="stylesheet" href="css/add.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>