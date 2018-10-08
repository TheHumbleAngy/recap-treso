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

        $banque = $_POST['banque'];
        $pays = $_POST['pays'];
        $monnaie = $_POST['monnaie'];

        $sql = "SELECT entite_banque FROM banque WHERE libelle_banque = '$banque' AND pays_banque = '$pays' AND monnaie_banque = '$monnaie'";

        $resultat = mysqli_query($connection, $sql);
//        echo $sql;

        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $entite = stripslashes($ligne['entite_banque']);
            }
        }

        $sql_xof = "
SELECT SUM(montant_xof_operation) AS recette_xof
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banque b on o.id_banque = b.id_banque
WHERE
  to2.id_type_operation = 1 AND b.pays_banque = '$pays' AND b.libelle_banque = '$banque' AND b.monnaie_banque = '$monnaie'
        ";

        $resultat = mysqli_query($connection, $sql_xof);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $recette_xof = stripslashes($ligne['recette_xof']);

                if (empty($recette_xof)) {
                    $recette_xof = 0;
                }
            }
        }

        $sql_devise = "
SELECT SUM(montant_operation) AS recette_devise
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banque b on o.id_banque = b.id_banque
WHERE
  to2.id_type_operation = 1 AND b.pays_banque = '$pays' AND b.libelle_banque = '$banque' AND b.monnaie_banque = '$monnaie'
        ";

        $resultat = mysqli_query($connection, $sql_devise);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $recette_devise = stripslashes($ligne['recette_devise']);

                if (empty($recette_devise)) {
                    $recette_devise = 0;
                }
            }
        }

        $sql_xof = "
SELECT SUM(montant_xof_operation) AS depense_xof
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banque b on o.id_banque = b.id_banque
WHERE
  to2.id_type_operation = 0 AND b.pays_banque = '$pays' AND b.libelle_banque = '$banque' AND b.monnaie_banque = '$monnaie'
        ";

        $resultat = mysqli_query($connection, $sql_xof);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $depense_xof = stripslashes($ligne['depense_xof']);

                if (empty($depense_xof)) {
                    $depense_xof = 0;
                }
            }
        }

        $sql_devise = "
SELECT SUM(montant_xof_operation) AS depense_devise
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banque b on o.id_banque = b.id_banque
WHERE
  to2.id_type_operation = 0 AND b.pays_banque = '$pays' AND b.libelle_banque = '$banque' AND b.monnaie_banque = '$monnaie'
        ";

        $resultat = mysqli_query($connection, $sql_devise);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $depense_devise = stripslashes($ligne['depense_devise']);

                if (empty($depense_devise)) {
                    $depense_devise = 0;
                }
            }
        }

        $solde_xof = $recette_xof - $depense_xof;
        $solde_devise = $recette_devise - $depense_devise;

        $json_data[] = array("entite" => $entite, "solde_xof" => $solde_xof, "solde_devise" => $solde_devise);
        echo json_encode($json_data);
    }

?>