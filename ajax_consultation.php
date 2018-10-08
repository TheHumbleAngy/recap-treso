<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 01-Oct-18
     * Time: 5:14 PM
     */
    if ((isset($_POST['debut']) && empty($_POST['debut']) === false) && (isset($_POST['fin']) && empty($_POST['fin']) === false) && (isset($_POST['choix']) && empty($_POST['choix']) === false)) {
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        $choix = $_POST['choix'];

        $sql = "SELECT * FROM operations WHERE date_saisie_operation BETWEEN '$debut' AND '$fin' ORDER BY date_saisie_operation";
        $sql_1 = "SELECT SUM(montant_xof_operation) AS solde_avant FROM operations WHERE date_saisie_operation < '$debut'";
        $sql_2 = "SELECT SUM(montant_xof_operation) AS solde_apres FROM operations WHERE date_saisie_operation <= '$fin'";
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
                $solde_avant = $ligne['solde_avant'];
            }
        }

        $resultat = mysqli_query($connection, $sql_2);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $solde_apres = $ligne['solde_apres'];
            }
        }

        echo '<input type="hidden" id="solde_avt" value="'. $solde_avant . '">';
        echo '<input type="hidden" id="solde_apr" value="'. $solde_apres . '">';

        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            if ($choix == 1) {
                echo '
            <table class="table table-striped fixed_header">
            <thead class="">
            <tr>
                <th>Pièce</th>
                <th>Compte</th>
                <th>Libellé</th>
                <th>Date</th>
                <th>Date Op.</th>
                <th>Opération</th>
                <th>Recettes</th>
                <th>Dépenses</th>
            </tr>
            </thead>
            <tbody>
            ';

                foreach ($lignes as $ligne) {

                    $type_operation = stripslashes($ligne['id_type_operation']);
                    $piece = stripslashes($ligne['piece_operation']);
                    $compte = stripslashes($ligne['compte_operation']);
                    $libelle = stripslashes($ligne['tag_operation']);
                    $date_saisie = stripslashes($ligne['date_saisie_operation']);
                    $date = stripslashes($ligne['date_operation']);
                    $designation = stripslashes($ligne['designation_operation']);
                    $xof = stripslashes($ligne['montant_xof_operation']);

                    $arr = explode('-', $date_saisie);
                    $date_saisie = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

                    $arr = explode('-', $date);
                    $date = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

                    echo '
                <tr>
                    <th scope="row">' . $piece . '</th>
                    <td>' . $compte . '</td>
                    <td>' . $libelle . '</td>
                    <td>' . $date_saisie . '</td>
                    <td>' . $date . '</td>
                    <td>' . $designation . '</td>
                ';
                    if ($type_operation == 0) {
                        echo '
                    <td></td>
                    <td>' . $xof . '</td>               
                ';
                    } elseif ($type_operation == 1) {
                        echo '
                    <td>' . $xof . '</td>
                    <td></td>            
                ';
                    }
                }

                echo '
            </tbody>
            </table>
            ';
            }
            elseif ($choix == 2) {
                echo '
            <table class="table table-striped">
            <thead>
            <tr>
                <th class="" rowspan="2">Pièce</th>
                <th class="" rowspan="2">Compte</th>
                <th class="" rowspan="2">Libellé</th>
                <th class="" rowspan="2">Date</th>
                <th class="" rowspan="2">Date Op.</th>
                <th class="w-25" rowspan="2">Opération</th>
                <th colspan="3" class="">Recettes</th>
                <th colspan="3" class="">Dépenses</th>
                <th class="" rowspan="2">Observation</th>
            </tr>
            <tr class="">
                <th class="bg-success text-light">Devise</th>
                <th class="bg-success text-light">Cours</th>
                <th class="bg-success text-light">XOF</th>
                <th class="bg-secondary text-light">Devise</th>
                <th class="bg-secondary text-light">Cours</th>
                <th class="bg-secondary text-light">XOF</th>
            </tr>
            </thead>
            <tbody>
            ';

                foreach ($lignes as $ligne) {

                    $type_operation = stripslashes($ligne['id_type_operation']);
                    $piece = stripslashes($ligne['piece_operation']);
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
                    <th scope="row">' . $piece . '</th>
                    <td>' . $compte . '</td>
                    <td>' . $libelle . '</td>
                    <td>' . $date_saisie . '</td>
                    <td>' . $date . '</td>
                    <td>' . $designation . '</td>
                ';
                    if ($type_operation == 0) {
                        echo '
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>' . $cours . '</td>
                    <td>' . $devise . '</td>
                    <td>' . $xof . '</td>               
                ';
                    } elseif ($type_operation == 1) {
                        echo '
                    <td>' . $cours . '</td>
                    <td>' . $devise . '</td>
                    <td>' . $xof . '</td>
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
