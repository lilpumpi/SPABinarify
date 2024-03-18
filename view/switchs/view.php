<?php
//file: view/switchs/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switch = $view->getVariable("switch");
$suscription = $view->getVariable("suscription"); //Si no esta suscrito al switch es NULL
$errors = $view->getVariable("errors");
$currentuser = $view->getVariable("currentusername");

$view->setVariable("title", "View Switch");

?>

 <!-- Seccion donde se mostraran los detalles del switch -->
<div class="switches">
    <div id="barra">
        <h2><?= i18n("Switch Details") ?></h2>
    </div>

    <!-- Mostramos el switch desde la base de datos -->
    <div id="switches">

        <div class="switch-container">
            <h3><?= $switch->getName(); ?></h3>
            <p id="descripcion"><?= $switch->getDescription(); ?></p>
            <p id="time" class="time" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>"><?= i18n("Time") ?>: <?= $switch->getAutoOffTime(); ?> min</p>
            <p id="fecha" class="time" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>"><?= i18n("Last time") ?>: <?= $switch->getLastTime(); ?></p>
            <p id="owner"><?= i18n("Created by") ?>: <?= $switch->getOwner()->getUsername(); ?></p>

            <div id="boton">

                <?php 

                if (isset($_GET["public_id"])){

                    if($suscription == NULL){ ?>
                        <!-- Sino esta suscrito (susription == NULL) mostrar boton de suscribirse -->
                        <div class="suscribe">
                            <form action="index.php?controller=suscriptions&amp;action=add" method="post">
                                <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                                <button type="submit" name="submit" class="btn-suscribe" id="btn-suscribe"><?= i18n("Subscribe") ?></button>
                            </form>
                        </div>

                    <?php } else { ?>
                        <!-- Si esta suscrito (susription != NULL) mostrar boton de desuscribirse -->
                        <div class="suscribe">
                            <form action="index.php?controller=suscriptions&amp;action=delete" method="post">
                                <input type="hidden" name="id" value="<?= $suscription->getId(); ?>">
                                <button type="submit" name="submit" class="btn-suscribe" id="btn-unsuscribe"><?= i18n("Unsubscribe") ?></button>
                            </form>
                        </div>
                        
                    <?php } ?>
                <?php

                } else if (isset($_GET["private_id"])) { ?>
                    
                    <div class="cambiarEstado" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>">
                        <form action="index.php?controller=switchs&amp;action=changeStatus&amp;status=false&amp;redirect=dashboard" method="post">
                            <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                            <button type="submit" name="submit" class="btn-cambiarEstado" id="btn-apagar"><?= i18n("Turn off") ?></button>
                        </form>
                    </div>

                    <div class="cambiarEstado" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>">
                        <button type="button" class="btn-cambiarEstado" id="btn-encender" onclick="openModal(<?= $switch->getId(); ?>, 'dashboard');"><?= i18n("Turn on") ?></button>
                    </div>

                <?php 
                }
                ?>

            </div>

        </div>

    </div>     

</div>

<div id="modal-container" class="modal-container"></div>

<?php $view->moveToFragment("css");?>
    <link rel="stylesheet" href="css/view.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>