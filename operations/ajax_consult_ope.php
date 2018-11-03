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

        // Recup des info de la banque
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
        $sql = "SELECT * FROM banque_pays_monnaie WHERE entite_banque = '" . $entite . "'";
        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
            foreach ($lignes as $ligne) {
                $id_banque = $ligne['id_banque'];
                $id_pays = $ligne['id_pays'];
                $id_monnaie = $ligne['id_monnaie'];
            }
        }

        $r_sql_1 = "
SELECT SUM(montant_xof_operation) AS recette_solde_avant 
FROM operations AS o 
  INNER JOIN banque_pays_monnaie AS b ON o.id_banque = b.id_banque 
WHERE b.entite_banque = '$entite' 
  AND o.id_banque = '$id_banque'
  AND o.id_pays = '$id_pays'
  AND o.id_monnaie = '$id_monnaie'
  AND o.date_saisie_operation < '$debut' 
  AND o.id_type_operation = 1";

        $r_sql_2 = "
SELECT SUM(montant_xof_operation) AS recette_solde_apres 
FROM operations AS o 
  INNER JOIN banque_pays_monnaie AS b ON o.id_banque = b.id_banque 
WHERE b.entite_banque = '$entite'  
  AND o.id_banque = '$id_banque'
  AND o.id_pays = '$id_pays'
  AND o.id_monnaie = '$id_monnaie'
  AND o.date_saisie_operation <= '$fin' 
  AND o.id_type_operation = 1";

        $d_sql_1 = "
SELECT SUM(montant_xof_operation) AS depense_solde_avant 
FROM operations AS o 
  INNER JOIN banque_pays_monnaie AS b ON o.id_banque = b.id_banque 
WHERE b.entite_banque = '$entite'  
  AND o.id_banque = '$id_banque'
  AND o.id_pays = '$id_pays'
  AND o.id_monnaie = '$id_monnaie'
  AND o.date_saisie_operation < '$debut' 
  AND o.id_type_operation = 0";

        $d_sql_2 = "
SELECT SUM(montant_xof_operation) AS depense_solde_apres 
FROM operations AS o 
  INNER JOIN banque_pays_monnaie AS b ON o.id_banque = b.id_banque 
WHERE b.entite_banque = '$entite'  
  AND o.id_banque = '$id_banque'
  AND o.id_pays = '$id_pays'
  AND o.id_monnaie = '$id_monnaie'
  AND o.date_saisie_operation <= '$fin' 
  AND o.id_type_operation = 0";

        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
        if ($connection->connect_error) {
            die($connection->connect_error);
        }

        $recette_solde_avant = 0;
        $recette_solde_apres = 0;

        $depense_solde_avant = 0;
        $depense_solde_apres = 0;

        $resultat = mysqli_query($connection, $r_sql_1);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $recette_solde_avant = stripcslashes($ligne['recette_solde_avant']);
            }
        }

        $resultat = mysqli_query($connection, $d_sql_1);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $depense_solde_avant = $ligne['depense_solde_avant'];
            }
        }

        $resultat = mysqli_query($connection, $r_sql_2);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $recette_solde_apres = stripcslashes($ligne['recette_solde_apres']);
            }
        }

        $resultat = mysqli_query($connection, $d_sql_2);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $depense_solde_apres = $ligne['depense_solde_apres'];
            }
        }

        $solde_avt = $recette_solde_avant - $depense_solde_avant;
        $solde_apr = $recette_solde_apres - $depense_solde_apres;

        echo '<input type="hidden" id="solde_avt" value="'. number_format(floatval($solde_avt), 2, ',', ' ') . '">';
        echo '<input type="hidden" id="solde_apr" value="'. number_format(floatval($solde_apr), 2, ',', ' ') . '">';

        $sql = "
SELECT * 
FROM operations o 
  INNER JOIN banque_pays_monnaie b ON o.id_banque = b.id_banque 
WHERE 
  b.entite_banque = '$entite' 
  AND o.id_banque = '$id_banque'
  AND o.id_pays = '$id_pays'
  AND o.id_monnaie = '$id_monnaie' 
  AND o.date_saisie_operation BETWEEN '$debut' AND '$fin' 
  ORDER BY o.date_saisie_operation";

        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            if ($choix == "simple") {
                echo '
            <table class="table table-striped ncare" style="font-size: small">
            <thead class="table-sticky-top">
            <tr class="bg-primary text-light">
                <th>COMPTE</th>
                <th>LIBELLE</th>
                <th title="Date de saisie">SAISIE LE</th>
                <th title="Date de l\'opération">DATE OP.</th>
                <th>OPERATION</th>
                <th class="col-1 text-right">RECETTE</th>
                <th class="col-1 text-right">DEPENSE</th>
                <th class="" title="En attente">STATUT</th>
            </tr>
            </thead>
            <tbody>
            ';

                $i = 0;
                foreach ($lignes as $ligne) {

                    $piece = stripcslashes($ligne['id_operation']);
                    $type_operation = stripslashes($ligne['id_type_operation']);
                    $compte = stripslashes($ligne['compte_operation']);
                    $libelle = stripslashes($ligne['tag_operation']);
                    $date_saisie = stripslashes($ligne['date_saisie_operation']);
                    $date = stripslashes($ligne['date_operation']);
                    $designation = stripslashes($ligne['designation_operation']);
                    $statut = stripslashes($ligne['statut_operation']);
                    $xof = stripslashes($ligne['montant_xof_operation']);
                    $xof = sprintf("%01.2f", $xof);
                    $xof = number_format($xof, 2, ',', ' ');

                    $arr = explode('-', $date_saisie);
                    $date_saisie = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

                    $arr = explode('-', $date);
                    $date = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

                    echo '
                <tr title="N° ' . $piece . '">
                    <td>' . $compte . '</td>
                    <td>' . $libelle . '</td>
                    <td>' . $date_saisie . '</td>
                    <td>' . $date . '</td>
                    <td>' . $designation . '</td>
                ';
                    if ($type_operation == 0) {
                        echo '
                    <td></td>
                    <td title="Dépense de..." class="text-right text-danger" style="font-weight: bolder">' . $xof . '</td>               
                ';
                        // En attente
                        if ($statut == 1) {
                            echo '
                    <td>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="rdo_statut_' . $i . '" class="custom-control-input">
                            
                        </div>
                    </td>            
                ';
                        } else {
                            echo '
                    <td></td>';
                        }
                    } elseif ($type_operation == 1) {
                        echo '
                    <td title="Recette de..." class="text-right text-success text" style="font-weight: bolder">' . $xof . '</td>
                    <td></td>            
                ';
                        // En attente
                        if ($statut == 1) {
                            echo '
                    <td>
                        <select class="custom-select custom-select-sm" id="statut_' . $piece . '" onchange="majStatut(this)">
                          <option value="1">Val.</option>
                          <option value="2">Att.</option>
                          <option value="3">Ann.</option>
                        </select>
                    </td>            
                ';
                            $i++;
                        } else {
                            echo '
                    <td></td>';
                        }
                    }
                }

                echo '
            </tbody>
            </table>
            ';
            }
            elseif ($choix == "detaillee") {
                echo '
            <table class="table table-striped ncare" style="font-size: small">
            <thead class="table-sticky-top">
            <tr>
                <th class="bg-primary text-light" rowspan="2">COMPTES</th>
                <th class="bg-primary text-light" rowspan="2">LIBELLES</th>
                <th class="bg-primary text-light" rowspan="2" title="Date de saisie">SAISIE LE</th>
                <th class="bg-primary text-light" rowspan="2" title="Date de l\'opération">DATE OP.</th>
                <th class="bg-primary text-light" rowspan="2">OPERATIONS</th>
                <th class="bg-primary text-light" colspan="3">RECETTES</th>
                <th class="bg-primary text-light" colspan="3">DEPENSES</th>
                <th class="bg-primary text-light" rowspan="2" title="Observations">OBS.</th>
            </tr>
            <tr class="">
                <th class="bg-success text-light col-1 text-right" title="Montant en devise">Devise</th>
                <th class="bg-success text-light col-1 text-right">Cours</th>
                <th class="bg-success text-light col-1 text-right" title="Montant en FCFA">XOF</th>
                <th class="bg-danger text-light col-1 text-right" title="Montant en devise">Devise</th>
                <th class="bg-danger text-light col-1 text-right">Cours</th>
                <th class="bg-danger text-light col-1 text-right" title="Montant en FCFA">XOF</th>
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
                    $observation = stripslashes($ligne['observation_operation']);

                    $cours = stripslashes($ligne['cours_operation']);
                    $cours = sprintf("%01.4f", $cours);
                    $cours = number_format($cours, 4, ',', ' ');

                    $devise = stripslashes($ligne['montant_operation']);
                    $devise = sprintf("%01.2f", $devise);
                    $devise = number_format($devise, 2, ',', ' ');

                    $xof = stripslashes($ligne['montant_xof_operation']);
                    $xof = sprintf("%01.2f", $xof);
                    $xof = number_format($xof, 2, ',', ' ');

                    echo '
                <tr title="N° ' . $piece . '">
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
                    <td class="text-right text-danger">' . $devise . '</td>
                    <td class="text-right text-danger">' . $cours . '</td>
                    <td class="text-right text-danger" style="font-weight: bolder" title="Dépense de...">' . $xof . '</td>               
                ';
                    } elseif ($type_operation == 1) {
                        echo '
                    <td class="text-right text-success">' . $devise . '</td>
                    <td class="text-right text-success">' . $cours . '</td>
                    <td class="text-right text-success" style="font-weight: bolder" title="Recette de...">' . $xof . '</td>
                    <td></td>
                    <td></td>
                    <td></td>               
                ';
                    }

                    echo '
                    <td title="' . $observation . '"></td>
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
