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
        <h2>Switch Details</h2>
    </div>

    <!-- Mostramos el switch desde la base de datos -->
    <div id="switches">

        <div class="switch-container">
            <h3><?= $switch->getName(); ?></h3>
            <p id="descripcion"><?= $switch->getDescription(); ?></p>
            <p id="time" class="time" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>">Tiempo: <?= $switch->getAutoOffTime(); ?> min</p>
            <p id="fecha" class="time" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>">Ultima vez: <?= $switch->getLastTime(); ?></p>
            <p id="owner">Creado por: <?= $switch->getOwner()->getUsername(); ?></p>

            <div id="boton">

                <?php 

                if (isset($_GET["public_id"])) { ?>
                   
                   <div class="suscribe" style="display: <?= $suscription !== NULL ? 'none' : 'block'; ?>">
                        <form action="index.php?controller=suscriptions&amp;action=add" method="post">
                            <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                            <button type="submit" name="submit" class="btn-suscribe" id="btn-suscribe">Suscribirse</button>
                        </form>
                    </div>

                    <div class="suscribe" style="display: <?= $suscription !== NULL ? 'block' : 'none'; ?>">
                        <form action="index.php?controller=suscriptions&amp;action=delete" method="post">
                            <input type="hidden" name="id" value="<?= $suscription->getId(); ?>">
                            <button type="submit" name="submit" class="btn-suscribe" id="btn-unsuscribe">Desuscribirse</button>
                        </form>
                    </div>

                <?php
                } else if (isset($_GET["private_id"])) { ?>
                    
                    <div class="cambiarEstado" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>">
                        <form action="index.php?controller=switchs&amp;action=changeStatus&amp;status=false&amp;redirect=dashboard" method="post">
                            <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                            <button type="submit" name="submit" class="btn-cambiarEstado" id="btn-apagar">Apagar</button>
                        </form>
                    </div>

                    <div class="cambiarEstado" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>">
                        <form action="index.php?controller=switchs&amp;action=changeStatus&amp;status=true&amp;redirect=dashboard" method="post">
                            <input type="hidden" name="id" value="<?= $switch->getId(); ?>">
                            <button type="submit" name="submit" class="btn-cambiarEstado" id="btn-encender">Encender</button>
                        </form>
                    </div>

                <?php 
                }
                ?>

            </div>

        </div>

    </div>     

</div>

<?php $view->moveToFragment("css");?>
    <link rel="stylesheet" href="css/view.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>