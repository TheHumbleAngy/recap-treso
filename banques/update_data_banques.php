<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 11-Oct-18
     * Time: 9:36 AM
     */
    if (isset($_GET['action']) && $_GET['action'] == "ajout_banque") {
        include 'class_banques.php';
        include 'class_banques_pays_monnaies.php';

        $banque = new banques();
        $banque_pays_monnaie = new banques_pays_monnaies();
        

        // First, save in banques table
        if ($banque->setData($_POST['libelle_banque'])) {
            if ($banque->saveData()) {

                // Second, save in banques_pays_monnaie
                $id_banque = $banque->getIdBanque();
                if ($banque_pays_monnaie->setData($id_banque, $_POST['pays_banque'], $_POST['monnaie_banque'], $_POST['entite_banque'])) {
                    if ($banque_pays_monnaie->saveData()) {
                        echo '
                        <h6>Enregistrée <small class="text-muted">avec succès</small></h6>
                        ';
                    } else {
                        echo '
                    <h6>Erreur <small class="text-muted">lors de la sauvegarde banque_pays_monnaie</small></h6>
                ';
                    }
                } else {
                    echo '
                    <h6>Erreur <small class="text-muted">lors de la recupération des données banque_pays_monnaie</small></h6>
                ';
                }
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
    } elseif (isset($_POST['libelle'])) {
        include 'class_banques.php';
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');

        $banque = new banques();
        $libelle = $_POST['libelle'];

        $sql = "SELECT * FROM banques b INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque WHERE libelle_banque = '" . $libelle . "'";

        if ($result = mysqli_query($connection, $sql)) {
            echo $result->num_rows;
        }
    }