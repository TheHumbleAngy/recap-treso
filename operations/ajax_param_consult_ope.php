<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 11-Oct-18
     * Time: 2:22 AM
     */
    if (isset($_POST['param']) && empty($_POST['param']) === false) {
        $param = $_POST['param'];

        $sql = "
SELECT *
FROM banques b
  INNER JOIN banque_pays_monnaie bpm on b.id_banque = bpm.id_banque
  INNER JOIN pays p on bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie 
WHERE entite_banque = '$param'";

        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
        if ($connection->connect_error) {
            die($connection->connect_error);
        }

        $resultat = mysqli_query($connection, $sql);
        if ($resultat->num_rows > 0) {
            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

            foreach ($lignes as $ligne) {
                $libelle = stripcslashes($ligne['libelle_banque']);
                $pays = stripcslashes($ligne['libelle_pays']);
                $monnaie = stripcslashes($ligne['sigle_monnaie']);
            }

            echo '
            <div class="row my-3">
                <div class="col-4">
                    <label for="banque">Banque</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm text-uppercase" id="banque" readonly value="' . $libelle . '">
                </div>
            </div>
            <div class="row my-3">
                <div class="col-4">
                    <label for="pays">Pays</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm text-uppercase" id="pays" readonly value="' . $pays . '">
                </div>
            </div>
            <div class="row my-3">
                <div class="col-4">
                    <label for="monnaie">Monnaie</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm text-uppercase" id="monnaie" readonly value="' . $monnaie . '">
                </div>
            </div>
            ';
        }
    }