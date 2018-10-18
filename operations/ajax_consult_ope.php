<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 01-Oct-18
     * Time: 5:14 PM
     */
    if ((isset($_POST['debut']) && empty($_POST['debut']) === false) && (isset($_POST['fin']) && empty($_POST['fin']) === false) && (isset($_POST['choix']) && empty($_POST['choix']) === false) && (isset($_POST['entite']) && empty($_POST['entite']) === false)) {
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        $entite = $_POST['entite'];
        $choix = $_POST['choix'];

        $sql = "SELECT * FROM operations AS o INNER JOIN banque AS b ON o.id_banque = b.id_banque WHERE b.entite_banque = '$entite' AND o.date_saisie_operation BETWEEN '$debut' AND '$fin' ORDER BY o.date_saisie_operation";
        $sql_1 = "SELECT SUM(montant_xof_operation) AS solde_avant FROM operations AS o INNER JOIN banque AS b ON o.id_banque = b.id_banque WHERE b.entite_banque = '$entite' AND o.date_saisie_operation < '$debut'";
        $sql_2 = "SELECT SUM(montant_xof_operation) AS solde_apres FROM operations AS o INNER JOIN banque AS b ON o.id_banque = b.id_banque WHERE b.entite_banque = '$entite' AND o.date_saisie_operation <= '$fin'";
//        echo $sql;

        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
        if ($connection->connect_error) {
            die($connection->connect_error);
        }

        $solde_avant = 0;
        $solde_apres = 0;

        $resultat = mysqli_query($connection, $sql_1);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $solde_avant = stripcslashes($ligne['solde_avant']);
                $solde_avant = number_format(floatval($solde_avant), 2, ',', '.');
            }
        }

        $resultat = mysqli_query($connection, $sql_2);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $solde_apres = $ligne['solde_apres'];
                $solde_apres = number_format(floatval($solde_apres), 2, ',', '.');
            }
        }

        echo '<input type="hidden" id="solde_avt" value="'. $solde_avant . '">';
        echo '<input type="hidden" id="solde_apr" value="'. $solde_apres . '">';

        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            if ($choix == "simple") {
                echo '
            <table class="table table-striped fixed_header" style="font-size: small">
            <thead class="gradient">
            <tr>
                <th>COMPTE</th>
                <th>LIBELLE</th>
                <th title="Date de saisie">SAISIE LE</th>
                <th title="Date de l\'opération">DATE OP.</th>
                <th>OPERATION</th>
                <th class="text-right">RECETTES</th>
                <th class="text-right">DEPENSES</th>
            </tr>
            </thead>
            <tbody>
            ';

                foreach ($lignes as $ligne) {

                    $piece = stripcslashes($ligne['id_operation']);
                    $type_operation = stripslashes($ligne['id_type_operation']);
                    $compte = stripslashes($ligne['compte_operation']);
                    $libelle = stripslashes($ligne['tag_operation']);
                    $date_saisie = stripslashes($ligne['date_saisie_operation']);
                    $date = stripslashes($ligne['date_operation']);
                    $designation = stripslashes($ligne['designation_operation']);
                    $xof = stripslashes($ligne['montant_xof_operation']);
                    $xof = sprintf("%01.2f", $xof);
                    $xof = number_format($xof, 2, ',', '.');

                    $arr = explode('-', $date_saisie);
                    $date_saisie = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

                    $arr = explode('-', $date);
                    $date = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

                    echo '
                <tr>
                    <td title="N° ' . $piece . '">' . $compte . '</td>
                    <td title="N° ' . $piece . '">' . $libelle . '</td>
                    <td>' . $date_saisie . '</td>
                    <td>' . $date . '</td>
                    <td title="N° ' . $piece . '">' . $designation . '</td>
                ';
                    if ($type_operation == 0) {
                        echo '
                    <td></td>
                    <td title="Dépense de...">' . $xof . '</td>               
                ';
                    } elseif ($type_operation == 1) {
                        echo '
                    <td title="Recette de..." class="text-right">' . $xof . '</td>
                    <td></td>            
                ';
                    }
                }

                echo '
            </tbody>
            </table>
            ';
            }
            elseif ($choix == "detaillee") {
                echo '
            <table class="table table-striped" style="font-size: small">
            <thead class="sticky-top">
            <tr>
                <th class="gradient" rowspan="2">COMPTES</th>
                <th class="gradient" rowspan="2">LIBELLES</th>
                <th class="gradient" rowspan="2" title="Date de saisie">SAISIE LE</th>
                <th class="gradient" rowspan="2" title="Date de l\'opération">DATE OP.</th>
                <th class="gradient" rowspan="2">OPERATIONS</th>
                <th class="gradient" colspan="3">RECETTES</th>
                <th class="gradient" colspan="3">DEPENSES</th>
                <th class="gradient" rowspan="2" title="Observations">OBS.</th>
            </tr>
            <tr class="">
                <th class="gradient-in">Devise</th>
                <th class="gradient-in">Cours</th>
                <th class="gradient-in">XOF</th>
                <th class="gradient-out">Devise</th>
                <th class="gradient-out">Cours</th>
                <th class="gradient-out">XOF</th>
            </tr>
            </thead>
            <tbody>
            ';

                foreach ($lignes as $ligne) {

                    $piece = stripcslashes($ligne['id_operation']);
                    $type_operation = stripslashes($ligne['id_type_operation']);
                    $compte = stripslashes($ligne['compte_operation']);
                    $libelle = stripslashes($ligne['tag_operation']);
                    $date_saisie = stripslashes($ligne['date_saisie_operation']);
                    $date = stripslashes($ligne['date_operation']);
                    $designation = stripslashes($ligne['designation_operation']);
                    $cours = stripslashes($ligne['cours_operation']);
                    $devise = stripslashes($ligne['montant_operation']);
                    $xof = stripslashes($ligne['montant_xof_operation']);
                    $observation = stripslashes($ligne['observation_operation']);

                    echo '
                <tr>
                    <td title="' . $piece . '">' . $compte . '</td>
                    <td title="' . $piece . '">' . $libelle . '</td>
                    <td>' . $date_saisie . '</td>
                    <td>' . $date . '</td>
                    <td title="' . $piece . '">' . $designation . '</td>
                ';
                    if ($type_operation == 0) {
                        echo '
                    <td></td>
                    <td></td>
                    <td></td>
                    <td title="Dépense de...">' . $cours . '</td>
                    <td title="Dépense de...">' . $devise . '</td>
                    <td title="Dépense de...">' . $xof . '</td>               
                ';
                    } elseif ($type_operation == 1) {
                        echo '
                    <td title="Recette de...">' . $cours . '</td>
                    <td title="Recette de...">' . $devise . '</td>
                    <td title="Recette de...">' . $xof . '</td>
                    <td></td>
                    <td></td>
                    <td></td>               
                ';
                    }

                    echo '
                    <td>' . $observation . '</td>
                </tr> 
                ';
                }

                echo '
            </tbody>
            </table>
            ';
            }
        }
        else {
            echo '
            <div class="alert alert-info my-4 mx-auto">
                <h6>Aucune opération enregistrée durant cette période.</h6>
            </div>
            ';
        }
    }
