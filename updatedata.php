<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 5:55 PM
     */
    if (isset($_GET['action']) && $_GET['action'] == "ajout_banque") {
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
    } elseif (isset($_GET['action']) && $_GET['action'] == "ajout_operation") {
        include 'class_operation.php';

        $operation = new operation();

        $nbr = htmlspecialchars($_POST['nbr'], ENT_QUOTES);

        $id_banque = htmlspecialchars($_POST['id_banque'], ENT_QUOTES);
        $id_type_operation = htmlspecialchars($_POST['id_type_operation'], ENT_QUOTES);
        $piece_operation = json_decode($_POST['piece_operation'], ENT_QUOTES);
        $compte_operation = json_decode($_POST['compte_operation'], ENT_QUOTES);
        $tag_operation = json_decode($_POST['tag_operation'], ENT_QUOTES);
        $date_saisie_operation = htmlspecialchars($_POST['date_saisie_operation'], ENT_QUOTES);
        $date_operation = json_decode($_POST['date_operation'], ENT_QUOTES);
        $designation_operation = json_decode($_POST['designation_operation'], ENT_QUOTES);
        $cours_operation = json_decode($_POST['cours_operation'], ENT_QUOTES);
        $montant_operation = json_decode($_POST['montant_operation'], ENT_QUOTES);
        $montant_xof_operation = json_decode($_POST['montant_xof_operation'], ENT_QUOTES);
        $observation_operation = json_decode($_POST['observation_operation'], ENT_QUOTES);

        for ($i = 0; $i < $nbr; $i++) {
            $piece_operation[$i] = strtoupper($piece_operation[$i]);
            $compte_operation[$i] = strtoupper($compte_operation[$i]);
            $tag_operation[$i] = strtoupper($tag_operation[$i]);
            $designation_operation[$i] = strtoupper($designation_operation[$i]);
            $observation_operation[$i] = strtoupper($observation_operation[$i]);

            $error = 0;

            if ($operation->setData($id_banque, $id_type_operation, $piece_operation[$i], $compte_operation[$i], $tag_operation[$i], $date_saisie_operation, $date_operation[$i], $designation_operation[$i], $cours_operation[$i], $montant_operation[$i], $montant_xof_operation[$i], $observation_operation[$i])) {
                if (!$operation->saveData()) {
                    $error = 1;
                    break;
                }
            } else {
                $error = 2;
                break;
            }
        }

        if ($error == 0) {
            echo "
            <div class='alert alert-success alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Succès!</strong><br/> Les opérations ont été enregistrées.
            </div>
            ";
        } elseif ($error == 1) {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la tentative d'enregistrement des opérations. Veuillez contacter l'administrateur.
            </div>
            ";
        } elseif ($error == 2) {
            echo "
            <div class='alert alert-danger alert-dismissible' role='alert' style='width: 60%; margin-right: auto; margin-left: auto'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' style='position: inherit'>
                    <span aria-hidden='true'>&times;</span>
                </button>
                <strong>Erreur!</strong><br/> Une erreur s'est produite lors de la récupération des informations. Veuillez contacter l'administrateur.
            </div>
            ";
        }
    }