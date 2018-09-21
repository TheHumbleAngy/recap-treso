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

        $sql = "SELECT entite_operation FROM banque WHERE libelle_banque = '$banque' AND pays_banque = '$pays' AND monnaie_banque = '$monnaie'";

        $resultat = mysqli_query($connection, $sql);

        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $entite = stripslashes($ligne['entite_operation']);
            }
        }

        $sql = "
SELECT SUM(montant_xof_operation) AS recette
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banque b on o.id_banque = b.id_banque
WHERE
  to2.id_type_operation = 1 AND b.pays_banque = '$pays' AND b.libelle_banque = '$banque' AND b.monnaie_banque = '$monnaie'
        ";

        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $recette = stripslashes($ligne['recette']);

                if (empty($recette))
                    $recette = 0;
            }
        }

        $sql = "
SELECT SUM(montant_xof_operation) AS depense
FROM operations AS o
  INNER JOIN type_operation AS to2
    ON o.id_type_operation = to2.id_type_operation
  INNER JOIN banque b on o.id_banque = b.id_banque
WHERE
  to2.id_type_operation = 0 AND b.pays_banque = '$pays' AND b.libelle_banque = '$banque' AND b.monnaie_banque = '$monnaie'
        ";

        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $depense = stripslashes($ligne['depense']);

                if (empty($depense))
                    $depense = 0;
            }
        }

        $solde = $recette - $depense;

        $json_data[] = array("entite" => $entite, "solde" => $solde);
        echo json_encode($json_data);
    }

?>