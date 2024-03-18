<?php
//file: view/switchs/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switchs = $view->getVariable("switchs");
$errors = $view->getVariable("errors");
$currentuser = $view->getVariable("currentusername");

$view->setVariable("title", "Switchs");

?>

 <!-- Seccion donde se mostraran los switches encendidos del usuario, y se permitira apagarlos -->
 <div class="switches">
    <div id="barra">
        <h2>Switches</h2>
        <?php if (isset($currentuser)): ?>
	        <a href="index.php?controller=switchs&amp;action=add"><?= i18n("Create Switch") ?></a>
        <?php endif; ?>
    </div>

    <!-- Mostramos los switches dinámicamente desde la base de datos -->
    <div id="switches">

        <?php 
            if (!empty($switchs)) {
                foreach ($switchs as $switch): 
            ?>
                                
                <div class="switch-container">
                    <h3><?= $switch->getName(); ?></h3>
                    <p id="descripcion"><?= $switch->getDescription(); ?></p>
                    <p id="time" class="time" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>"><?= i18n("Time") ?>: <?= $switch->getAutoOffTime(); ?> min</p>
                    <p id="fecha" class="time" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>"><?= i18n("Last time") ?>: <?= $switch->getLastTime(); ?></p>

                    <div class="uris">
                        <p id="privada"><?= i18n("Private URI") ?>: <a href="index.php?controller=switchs&action=view&private_id=<?= $switch->getPrivateId(); ?>">http://localhost/mvcbinarify/index.php?controller=switchs&action=view&private_id=<?= $switch->getPrivateId(); ?></a></p>
                        <p id="publica"><?= i18n("Public URI") ?>: <a href="index.php?controller=switchs&action=view&public_id=<?= $switch->getPublicId(); ?>">http://localhost/mvcbinarify/index.php?controller=switchs&action=view&public_id=<?= $switch->getPublicId(); ?></a></p>
                    </div>

                    <div class="cambiarEstado" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>">
                        <form action="index.php?controller=switchs&amp;action=changeStatus&amp;status=false&amp;redirect=index" method="post">
                            <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                            <button type="submit" class="btn-cambiarEstado" id="btn-apagar"><?= i18n("Turn off") ?></button>
                        </form>
                    </div>

                    <div class="cambiarEstado" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>">
                        <button type="button" class="btn-cambiarEstado" id="btn-encender" onclick="openModal(<?= $switch->getId(); ?>, 'index');"><?= i18n("Turn on") ?></button>
                    </div>

                    <div class="eliminar">
                        <form action="index.php?controller=switchs&amp;action=delete&amp;id=<?= $switch->getId(); ?>" method="post">
                            <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                            <button type="submit" class="btn-eliminar" id="btn-eliminar"><?= i18n("Delete") ?></button>
                        </form>
                    </div>
                </div>

            <?php 
                endforeach; 
            } else {
            ?>
                <div id="mensaje"><?= i18n("No switches") ?></div>
            <?php
            }
        ?>


    </div>    


    <div id="modal-container" class="modal-container"></div>

<?php $view->moveToFragment("css");?>
    <link rel="stylesheet" href="css/switches.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>
