<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 5:55 PM
     */
    if (isset($_GET['action']) && $_GET['action'] == "ajout_operation" && isset($_GET['monnaie']) && isset($_GET['pays'])) {
        include 'class_operations.php';

        $operation = new operations();
    
        $id_pays = $_GET['pays'];
        $monnaie = $_GET['monnaie']; //echo $monnaie . " " . $id_pays;

        $nbr = htmlspecialchars($_POST['nbr'], ENT_QUOTES);

        $id_banque = htmlspecialchars($_POST['id_banque'], ENT_QUOTES);
        $id_type_operation = htmlspecialchars($_POST['id_type_operation'], ENT_QUOTES);
        $compte_operation = json_decode($_POST['compte_operation'], ENT_QUOTES);
        $tag_operation = json_decode($_POST['tag_operation'], ENT_QUOTES);
        $date_saisie_operation = htmlspecialchars($_POST['date_saisie_operation'], ENT_QUOTES);
        $date_operation = json_decode($_POST['date_operation'], ENT_QUOTES);
        $designation_operation = json_decode($_POST['designation_operation'], ENT_QUOTES);
        $cours_operation = json_decode($_POST['cours_operation'], ENT_QUOTES);
        $montant_operation = json_decode($_POST['montant_operation'], ENT_QUOTES);
        $montant_xof_operation = json_decode($_POST['montant_xof_operation'], ENT_QUOTES);
        $statut_operation = json_decode($_POST['statut_operation'], ENT_QUOTES);
        $observation_operation = json_decode($_POST['observation_operation'], ENT_QUOTES);
    
        $error = 0;
    
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
        
        // Recuperation des abbréviations de la banque et du pays
        $sql = "SELECT abbr_banque FROM banques WHERE id_banque = '$id_banque'";
        $resultat = $connection->query($sql);
        $abbr_banque = "";
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $abbr_banque = stripslashes($ligne['abbr_banque']);
            }
        }
        
        $sql = "SELECT abbr_pays FROM pays WHERE id_pays = '$id_pays'";
        $resultat = $connection->query($sql);
        $abbr_pays = "";
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $abbr_pays = stripslashes($ligne['abbr_pays']);
            }
        }
        
        for ($i = 0; $i < $nbr; $i++) {
            $compte_operation[$i] = strtoupper($compte_operation[$i]);
            $tag_operation[$i] = strtoupper($tag_operation[$i]);
            $designation_operation[$i] = strtoupper($designation_operation[$i]);
            $observation_operation[$i] = strtoupper($observation_operation[$i]);
    
            if ($operation->setData($id_banque, $id_type_operation, $compte_operation[$i], $tag_operation[$i], $date_saisie_operation, $date_operation[$i], $designation_operation[$i], $cours_operation[$i], $montant_operation[$i], $montant_xof_operation[$i], $statut_operation[$i], $observation_operation[$i])) {
                if ($operation->saveData($abbr_banque, $abbr_pays, $monnaie)) {
                    $error = 0;
                } else {
                    $error = 1;
                    break;
                }
//                $operation->saveData($abbr_banque, $abbr_pays, $monnaie);
            } else {
                $error = 2;
                break;
            }
        }

        if ($error == 0) {
            echo '
            <h6>Enregistrée(s) <small class="text-muted">avec succès</small></h6>
            ';
        } elseif ($error == 1) {
            echo '
            <h6>Erreur <small class="text-muted">lors de la sauvegarde [' . $i . ']</small></h6>
                ';
        } elseif ($error == 2) {
            echo '
            <h6>Erreur <small class="text-muted">lors de la recupération des données [' . $i . ']</small></h6>
                ';
        }
    }