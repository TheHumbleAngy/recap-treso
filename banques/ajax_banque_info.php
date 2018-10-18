<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 21-Sep-18
     * Time: 3:03 PM
     */

    if ((isset($_POST['monnaie']) && empty($_POST['monnaie']) === false) && (isset($_POST['banque']) && empty($_POST['banque']) === false) && isset($_POST['pays']) && empty($_POST['pays']) === false) {

        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
        if ($connection->connect_error) {
            die($connection->connect_error);
        }

        $id_banque = $_POST['banque'];
        $id_pays = $_POST['pays'];
        $id_monnaie = $_POST['monnaie'];

        // Recuperation de l'entité
        $entite = "";
        $sql = "SELECT entite_banque FROM banques WHERE id_banque = '$id_banque'";
        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $entite = stripslashes($ligne['entite_banque']);
            }
        }

        // Calcul du total des recettes
        $recette_xof = 0;
        $recette_devise = 0;
        $sql_recettes = "
SELECT SUM(montant_xof_operation) AS recette_xof, SUM(montant_operation) AS recette_devise
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banques b 
    ON o.id_banque = b.id_banque
  INNER JOIN banque_pays_monnaie m 
    ON b.id_banque = m.id_banque
WHERE
  to2.id_type_operation = 1 AND b.id_banque = '$id_banque' AND id_pays = '$id_pays' AND id_monnaie = '$id_monnaie'
        ";

        $resultat = mysqli_query($connection, $sql_recettes);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $recette_xof = stripslashes($ligne['recette_xof']);
                $recette_devise = stripslashes($ligne['recette_devise']);

                if (empty($recette_xof))
                    $recette_xof = 0;
                if (empty($recette_devise))
                    $recette_devise = 0;
            }
        }
    
        // Calcul du total des depenses
        $depense_xof = 0;
        $depense_devise = 0;
        $sql_depenses = "
SELECT SUM(montant_xof_operation) AS depense_xof, SUM(montant_operation) AS depense_devise
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banques b 
    ON o.id_banque = b.id_banque
  INNER JOIN banque_pays_monnaie m 
    ON b.id_banque = m.id_banque
WHERE
  to2.id_type_operation = 0 AND b.id_banque = '$id_banque' AND id_pays = '$id_pays' AND id_monnaie = '$id_monnaie'
        ";

        $resultat = mysqli_query($connection, $sql_depenses);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $depense_xof = stripslashes($ligne['depense_xof']);
                $depense_devise = stripslashes($ligne['depense_devise']);

                if (empty($depense_xof))
                    $depense_xof = 0;
                if (empty($depense_devise))
                    $depense_devise = 0;
            }
        }

        $solde_xof = $recette_xof - $depense_xof;
        $solde_devise = $recette_devise - $depense_devise;

        $json_data[] = array("entite" => $entite, "solde_xof" => $solde_xof, "solde_devise" => $solde_devise);
        echo json_encode($json_data);
    }