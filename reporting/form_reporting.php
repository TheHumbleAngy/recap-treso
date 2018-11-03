<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 31-Oct-18
     * Time: 10:18 AM
     */
?>
<div class="row">
    <div class="col-8 mx-auto bg-white" style="border-radius: 4px">
        <a class="retour mx-2" role="button" data-toggle="tooltip" data-placement="right"
           title="Accueil" href="index.php">
            <i class="fas fa-home fa-1-5x"></i></a>
        <a class="retour mx-2" role="button" data-toggle="tooltip" data-placement="right"
               title="Retour au parametrage" href="index.php?page=reporting/param_reporting">
            <i class="fas fa-wrench fa-1-5x animated"></i></a>
        <?php
            $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
            $debut = '2018-01-01';
            $fin = '2018-12-31';

            $arr = explode('-', $debut);
            $debut_ = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $fin);
            $fin_ = $arr[2] . '-' . $arr[1] . '-' . $arr[0];

            if (isset($_POST['rdo_nature']) && $_POST['rdo_nature'] == "toutes") {

                $sql_grp_banq = "
SELECT DISTINCT
  libelle_banque,
  libelle_pays
FROM banques b
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
ORDER BY libelle_banque";

                $libelle_banque = array();
                $libelle_pays = array();
                $monnaie = array();
                $n = 0;

                echo "<h4 class='w-75 text-center py-2 mx-auto cadre-titre'>RECAP TRESORERIE du <span class='badge badge-primary'>" . $debut_. "</span> au <span class='badge badge-primary'>" . $fin_. "</span></h4>";
                if ($resultat = mysqli_query($connection, $sql_grp_banq)) {
                    if ($resultat->num_rows > 0) {
                        $lignes =$resultat->fetch_all(MYSQLI_ASSOC);
                        $total_general_solde_prec = 0;
                        $total_general_rec_apr = 0;
                        $total_general_dep_apr = 0;
                        $total_general_solde_final = 0;

                        foreach ($lignes as $ligne) { // banque-pays
                            $libelle_banque[$n] = stripcslashes($ligne['libelle_banque']);
                            $libelle_pays[$n] = stripcslashes($ligne['libelle_pays']);

                            $qry_libelle_banque = addslashes($libelle_banque[$n]);
                            $qry_libelle_pays = addslashes($libelle_pays[$n]);

                            $sql_grp_curr = "
SELECT DISTINCT sigle_monnaie
FROM banques b
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
WHERE libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
ORDER BY sigle_monnaie";

                            $m = 0;
                            echo '
<table class="table table-sm table-hover my-4 ncare font-weight-light" id="etat" style="font-size: small">
    <thead class="">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th class="bg-primary text-light text-center" colspan="4">COMPTABILITE</th>
        <th></th>
    </tr>
    <tr class="">
        <th class="col-3" rowspan="2">' . $libelle_banque[$n] . ' ' . $libelle_pays[$n] . '</th>
        <th class="table-primary" rowspan="2" title="Numéro de compte">Compte</th>
        <th class="table-primary col-1" rowspan="2" title="Solde de la banque">Banque</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde précédent">Solde Préc.</th>
        <th class="bg-primary text-light" colspan="2">Mouvements</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde final">Solde Fin.</th>
        <th class="table-info col-1" rowspan="2" title="Opérations en attente">Op. En Att.</th>
    </tr>
    <tr>
        <th class="bg-primary text-light pl-2">Entrées</th>
        <th class="bg-primary text-light pl-2">Sorties</th>
    </tr>
    </thead>
    <tbody>';
                            if ($resultat = mysqli_query($connection, $sql_grp_curr)) {
                                if ($resultat->num_rows > 0) {

                                    $rows =$resultat->fetch_all(MYSQLI_ASSOC);
                                    $total_solde_prec = 0;
                                    $total_rec_apr = 0;
                                    $total_dep_apr = 0;
                                    $total_solde_final = 0;

                                    foreach ($rows as $row) { // banque-pays-monnaie
                                        
                                        $monnaie[$m] = stripcslashes($row['sigle_monnaie']);

                                        $sql_rec_prec = "
SELECT SUM(montant_xof_operation) AS rec_prec
FROM operations o
  INNER JOIN banque_pays_monnaie bpm ON o.id_banque = bpm.id_banque
  INNER JOIN banques b ON bpm.id_banque = b.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 1 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation < '$debut'";

                                        $sql_dep_prec = "
SELECT SUM(montant_xof_operation) AS dep_prec
FROM operations o
  INNER JOIN banque_pays_monnaie bpm ON o.id_banque = bpm.id_banque
  INNER JOIN banques b ON bpm.id_banque = b.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 0 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation < '$debut'";

                                        echo $sql_rec_apr = "
SELECT SUM(montant_xof_operation) AS rec_apr
FROM operations o
  INNER JOIN banque_pays_monnaie bpm ON o.id_banque = bpm.id_banque
  INNER JOIN banques b ON bpm.id_banque = b.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 1 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation >= '$debut' AND date_saisie_operation <= '$fin'";

                                        $sql_dep_apr = "
SELECT SUM(montant_xof_operation) AS dep_apr
FROM operations o
  INNER JOIN banque_pays_monnaie bpm ON o.id_banque = bpm.id_banque
  INNER JOIN banques b ON bpm.id_banque = b.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 0 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation >= '$debut' AND date_saisie_operation <= '$fin'";

                                        if ($resultat = mysqli_query($connection, $sql_rec_prec)) {
                                            if ($resultat->num_rows > 0) {
                                                $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($set as $element) {
                                                    $rec_prec = $element['rec_prec'];
                                                    if ($rec_prec == '')
                                                        $rec_prec = 0;
                                                }
                                            }
                                        }

                                        if ($resultat = mysqli_query($connection, $sql_dep_prec)) {
                                            if ($resultat->num_rows > 0) {
                                                $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($set as $element) {
                                                    $dep_prec = $element['dep_prec'];
                                                    if ($dep_prec == '')
                                                        $dep_prec = 0;
                                                }
                                            }
                                        }

                                        if ($resultat = mysqli_query($connection, $sql_rec_apr)) {
                                            if ($resultat->num_rows > 0) {
                                                $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($set as $element) {
                                                    $rec_apr = $element['rec_apr'];
                                                    if ($rec_apr == '')
                                                        $rec_apr = 0;
                                                }
                                            }
                                        }

                                        if ($resultat = mysqli_query($connection, $sql_dep_apr)) {
                                            if ($resultat->num_rows > 0) {
                                                $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($set as $element) {
                                                    $dep_apr = $element['dep_apr'];
                                                    if ($dep_apr == '')
                                                        $dep_apr = 0;
                                                }
                                            }
                                        }

                                        $solde_prec = $rec_prec - $dep_prec;
                                        $solde_final = $solde_prec + $rec_apr - $dep_apr;

                                        $total_solde_prec += $solde_prec;
                                        $total_rec_apr += $rec_apr;
                                        $total_dep_apr += $dep_apr;
                                        $total_solde_final += $solde_final;
                                //echo "Recette prec: " . $rec_prec . " Dépense prec: " . $dep_prec . " Recette apr: " . $rec_apr . " Depense apr: " . $dep_apr . "<br>";
                                //echo "Solde prec: " . $solde_prec . " Entrées: " . $rec_apr . " Sortie: " . $dep_apr . " Solde final: " . $solde_final . "<br><br>";

                                        echo '
<tr>
    <td class="montant pl-4">' . $monnaie[$m] . '</td>
    <td class="montant text-right"></td>
    <td class="montant text-right"></td>
    <td class="montant text-right">' . number_format($solde_prec , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($rec_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($dep_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($solde_final , 2, ",", " ") . '</td>
    <td class="montant text-right"></td>
</tr>
                                ';
                                        $m++;
                                    }
                                }
                            }

                            echo '
<tr class="table-success" style="font-weight: bold;">
    <td class="montant">Total ' . $libelle_banque[$n] . ' ' . $libelle_pays[$n] . '</td>
    <td class="montant text-right"></td>
    <td class="montant text-right"></td>
    <td class="montant text-right">' . number_format($total_solde_prec , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($total_rec_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($total_dep_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($total_solde_final , 2, ",", " ") . '</td>
    <td class="montant text-right"></td>
</tr>';
                            $n++;
                        }
                        echo '
    </tbody>
</table>';
                        $total_general_solde_prec += $total_solde_prec;
                        $total_general_rec_apr += $total_rec_apr;
                        $total_general_dep_apr += $total_dep_apr;
                        $total_general_solde_final += $total_solde_final;

                        echo '
<table class="table table-sm my-4 ncare">
    <thead class="">
    <tr class="">
        <th class="col-3" rowspan="2"></th>
        <th class="table-info" rowspan="2" title="Numéro de compte"></th>
        <th class="table-info col-1" rowspan="2" title="Solde de la banque">Banque</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde précédent">Solde Préc.</th>
        <th class="bg-primary text-light" colspan="2">Mouvements</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde final">Solde Fin.</th>
        <th class="table-info col-1" rowspan="2" title="Opérations en attente">Op. En Att.</th>
    </tr>
    <tr>
        <th class="bg-primary text-light pl-2">Entrées</th>
        <th class="bg-primary text-light pl-2">Sorties</th>
    </tr>
    <tr class="bg-success text-light">
        <th>Total Général</th>
        <th></th>
        <th></th>
        <th>' . number_format($total_general_solde_prec , 2, ",", " ") . '</th>
        <th>' . number_format($total_general_rec_apr , 2, ",", " ") . '</th>
        <th>' . number_format($total_general_dep_apr , 2, ",", " ") . '</th>
        <th>' . number_format($total_general_solde_final , 2, ",", " ") . '</th>
        <th></th>
    </tr>
    </thead>
</table>';
                    }
                }
            }
            elseif (isset($_POST['rdo_nature']) && $_POST['rdo_nature'] == "selection" && isset($_POST['liste'])) {
                $libelle_banque = array();
                $libelle_pays = array();
                $monnaie = array();

                echo "<h4 class='w-75 text-center py-2 mx-auto' style='color: #1A74B8; border: solid 2px #1A74B8; border-radius: 4px'>RECAP TRESORERIE du <span class='badge badge-primary'>" . $debut_. "</span> au <span class='badge badge-primary'>" . $fin_. "</span></h4>";
                $total_general_solde_prec = 0;
                $total_general_rec_apr = 0;
                $total_general_dep_apr = 0;
                $total_general_solde_final = 0;

                $liste = $_POST['liste'];
                $arr = explode(',', $liste);
                $n = 0;

                foreach ($arr as $item) {
                    $tab = explode('_', $item);
                    $libelle_banque[$n] = $tab[0];
                    $libelle_pays[$n] = $tab[1];

                    $qry_libelle_banque = addslashes($tab[0]);
                    $qry_libelle_pays = addslashes($tab[1]);

                    $sql_grp_curr = "
SELECT DISTINCT sigle_monnaie
FROM banques b
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
WHERE libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
ORDER BY sigle_monnaie";

                    $m = 0;
                    echo '
<table class="table table-sm table-hover my-4 ncare bg-light font-weight-light" id="etat" style="font-size: small">
    <thead class="">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th class="bg-primary text-light text-center" colspan="4">COMPTABILITE</th>
        <th></th>
    </tr>
    <tr class="">
        <th class="col-3" rowspan="2">' . $libelle_banque[$n] . ' ' . $libelle_pays[$n] . '</th>
        <th class="table-primary" rowspan="2" title="Numéro de compte">Compte</th>
        <th class="table-primary col-1" rowspan="2" title="Solde de la banque">Banque</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde précédent">Solde Préc.</th>
        <th class="bg-primary text-light" colspan="2">Mouvements</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde final">Solde Fin.</th>
        <th class="table-info col-1" rowspan="2" title="Opérations en attente">Op. En Att.</th>
    </tr>
    <tr>
        <th class="bg-primary text-light pl-2">Entrées</th>
        <th class="bg-primary text-light pl-2">Sorties</th>
    </tr>
    </thead>
    <tbody>';
                    if ($resultat = mysqli_query($connection, $sql_grp_curr)) {
                        if ($resultat->num_rows > 0) {

                            $rows =$resultat->fetch_all(MYSQLI_ASSOC);
                            $total_solde_prec = 0;
                            $total_rec_apr = 0;
                            $total_dep_apr = 0;
                            $total_solde_final = 0;

                            foreach ($rows as $row) { // banque-pays-monnaie
                                
                                $monnaie[$m] = stripcslashes($row['sigle_monnaie']);

                                $sql_rec_prec = "
SELECT SUM(montant_xof_operation) AS rec_prec
FROM operations o
  INNER JOIN banques b ON o.id_banque = b.id_banque
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 1 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation < '$debut'";

                                $sql_dep_prec = "
SELECT SUM(montant_xof_operation) AS dep_prec
FROM operations o
  INNER JOIN banques b ON o.id_banque = b.id_banque
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 0 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation < '$debut'";

                                $sql_rec_apr = "
SELECT SUM(montant_xof_operation) AS rec_apr
FROM operations o
  INNER JOIN banques b ON o.id_banque = b.id_banque
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 1 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation >= '$debut' AND
      date_saisie_operation <= '$fin'";

                                $sql_dep_apr = "
SELECT SUM(montant_xof_operation) AS dep_apr
FROM operations o
  INNER JOIN banques b ON o.id_banque = b.id_banque
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
WHERE o.id_type_operation = 0 AND libelle_banque = '$qry_libelle_banque' AND libelle_pays = '$qry_libelle_pays'
      AND sigle_monnaie = '$monnaie[$m]' AND date_saisie_operation >= '$debut' AND
      date_saisie_operation <= '$fin'";

                                if ($resultat = mysqli_query($connection, $sql_rec_prec)) {
                                    if ($resultat->num_rows > 0) {
                                        $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                        foreach ($set as $element) {
                                            $rec_prec = $element['rec_prec'];
                                            if ($rec_prec == '')
                                                $rec_prec = 0;
                                        }
                                    }
                                }

                                if ($resultat = mysqli_query($connection, $sql_dep_prec)) {
                                    if ($resultat->num_rows > 0) {
                                        $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                        foreach ($set as $element) {
                                            $dep_prec = $element['dep_prec'];
                                            if ($dep_prec == '')
                                                $dep_prec = 0;
                                        }
                                    }
                                }

                                if ($resultat = mysqli_query($connection, $sql_rec_apr)) {
                                    if ($resultat->num_rows > 0) {
                                        $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                        foreach ($set as $element) {
                                            $rec_apr = $element['rec_apr'];
                                            if ($rec_apr == '')
                                                $rec_apr = 0;
                                        }
                                    }
                                }

                                if ($resultat = mysqli_query($connection, $sql_dep_apr)) {
                                    if ($resultat->num_rows > 0) {
                                        $set = $resultat->fetch_all(MYSQLI_ASSOC);
                                        foreach ($set as $element) {
                                            $dep_apr = $element['dep_apr'];
                                            if ($dep_apr == '')
                                                $dep_apr = 0;
                                        }
                                    }
                                }

                                $solde_prec = $rec_prec - $dep_prec;
                                $solde_final = $solde_prec + $rec_apr - $dep_apr;

                                $total_solde_prec += $solde_prec;
                                $total_rec_apr += $rec_apr;
                                $total_dep_apr += $dep_apr;
                                $total_solde_final += $solde_final;
                                //echo "Recette prec: " . $rec_prec . " Dépense prec: " . $dep_prec . " Recette apr: " . $rec_apr . " Depense apr: " . $dep_apr . "<br>";
                                //echo "Solde prec: " . $solde_prec . " Entrées: " . $rec_apr . " Sortie: " . $dep_apr . " Solde final: " . $solde_final . "<br><br>";

                                echo '
<tr>
    <td class="montant pl-4">' . $monnaie[$m] . '</td>
    <td class="montant text-right"></td>
    <td class="montant text-right"></td>
    <td class="montant text-right">' . number_format($solde_prec , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($rec_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($dep_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($solde_final , 2, ",", " ") . '</td>
    <td class="montant text-right"></td>
</tr>';
                                $m++;
                            }
                        }
                    }

                    echo '
<tr class="table-success" style="font-weight: bold;">
    <td class="montant">Total ' . $libelle_banque[$n] . ' ' . $libelle_pays[$n] . '</td>
    <td class="montant text-right"></td>
    <td class="montant text-right"></td>
    <td class="montant text-right">' . number_format($total_solde_prec , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($total_rec_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($total_dep_apr , 2, ",", " ") . '</td>
    <td class="montant text-right">' . number_format($total_solde_final , 2, ",", " ") . '</td>
    <td class="montant text-right"></td>
</tr>';
                    $n++;
                    $total_general_solde_prec += $total_solde_prec;
                    $total_general_rec_apr += $total_rec_apr;
                    $total_general_dep_apr += $total_dep_apr;
                    $total_general_solde_final += $total_solde_final;
                }
                echo '
    </tbody>
</table>';

                echo '
<table class="table table-sm table-hover my-4 font-weight-light">
    <thead class="">
    <tr class="">
        <th class="col-3" rowspan="2"></th>
        <th class="table-info" rowspan="2" title="Numéro de compte">Compte</th>
        <th class="table-info col-1" rowspan="2" title="Solde de la banque">Banque</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde précédent">Solde Préc.</th>
        <th class="bg-primary text-light" colspan="2">Mouvements</th>
        <th class="bg-primary text-light" rowspan="2" title="Solde final">Solde Fin.</th>
        <th class="table-info col-1" rowspan="2" title="Opérations en attente">Op. En Att.</th>
    </tr>
    <tr>
        <th class="bg-primary text-light pl-2">Entrées</th>
        <th class="bg-primary text-light pl-2">Sorties</th>
    </tr>
    <tr class="bg-success text-light">
        <th>Total Général</th>
        <th class="text-right"></th>
        <th class="text-right"></th>
        <th class="text-right">' . number_format($total_general_solde_prec , 2, ",", " ") . '</th>
        <th class="text-right">' . number_format($total_general_rec_apr , 2, ",", " ") . '</th>
        <th class="text-right">' . number_format($total_general_dep_apr , 2, ",", " ") . '</th>
        <th class="text-right">' . number_format($total_general_solde_final , 2, ",", " ") . '</th>
        <th class="text-right"></th>
    </tr>
    </thead>
</table>';
            }
            else
                echo "Not set";
        ?>
    </div>
</div>
