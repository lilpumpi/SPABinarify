<?php
//file: view/switchs/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$suscriptions = $view->getVariable("suscriptions");
$errors = $view->getVariable("errors");
$currentuser = $view->getVariable("currentusername");

$view->setVariable("title", "Suscriptions");

?>

 <!-- Seccion donde se mostraran los switches encendidos del usuario, y se permitira apagarlos -->
 <div class="suscriptions">
    <div id="barra">
        <h2>Suscriptions</h2>
    </div>

    <!-- Mostramos los switches dinámicamente desde la base de datos -->
    <div id="suscriptions">

        <?php 
            if (!empty($suscriptions)) {
                foreach ($suscriptions as $suscription): 
                    $switch = $suscription->getSwitch();
            ?>
                                
                <div class="suscription-container">
                    <h3><?= $switch->getName(); ?></h3>
                    <p id="descripcion"><?= $switch->getDescription(); ?></p>
                    <!-- <p id="owner"><?= $switch->getOwner()->getUsername(); ?></p> -->
                    <p id="time" class="time" style="display: <?= $switch->getStatus() ? 'block' : 'none'; ?>">Tiempo: <?= $switch->getAutoOffTime(); ?> min</p>
                    <p id="fecha" class="time" style="display: <?= $switch->getStatus() ? 'none' : 'block'; ?>">Ultima vez: <?= $switch->getLastTime(); ?></p>

                    <div class="suscribe">
                        <form action="index.php?controller=suscriptions&amp;action=delete" method="post">
                            <input type="hidden" name="id" value="<?= $suscription->getId(); ?>">
                            <button type="submit" name="submit" class="btn-suscribe" id="btn-unsuscribe">Desuscribirse</button>
                        </form>
                    </div>

                </div>

            <?php 
                endforeach; 
            } else {
            ?>
                <div id="mensaje">No hay suscripciones</div>
            <?php
            }
        ?>


    </div>     

</div>

<?php $view->moveToFragment("css");?>
    <link rel="stylesheet" href="css/suscriptions.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>
