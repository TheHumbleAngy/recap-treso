<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 5:55 PM
     */
    if (isset($_GET['operation']) && $_GET['operation'] == "add") {
        include 'class_banque.php';

        $banque = new banque();

        if ($banque->setData()) {
            if ($banque->saveData()) {
                echo '
                    <h6>Enregistrée <small class="text-muted">avec succès</small></h6>
                ';
            } else {
                echo '
                    <h6>Erreur <small class="text-muted">lors de la sauvegarde</small></h6>
                ';
            }
        } else {
            echo '
                    <h6>Erreur <small class="text-muted">lors de la recupération des données</small></h6>
                ';
        }
    }