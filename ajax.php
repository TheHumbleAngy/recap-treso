<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 19-Sep-18
     * Time: 4:46 PM
     */
    $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
    if ($connection->connect_error)
        die($connection->connect_error);

    if (isset($_POST['nbr']) && empty($_POST['nbr']) === false && isset($_POST['nature'])) {
        require('connect.php');

        $value = "NGN";
        $nbr = stripcslashes($_POST['nbr']);
        $nature = stripcslashes($_POST['nature']);

        $libelle_nature = $nature == "0" ? "Dépense" : "Recette";

        $query = "SELECT * FROM operations WHERE id_type_operation = '$nature  '";

//        echo $query;

        if ($resultat = $connection->query($query)) {
            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                echo '
            <table class="table table-sm table-hover my-4 ncare bg-light font-weight-light" id="etat">
                <thead class="bg-primary text-light">
                <tr>
                    <th class="" rowspan="2">Pièce</th>
                    <th class="" rowspan="2">Compte</th>
                    <th class="w-8" rowspan="2">Libellé</th>
                    <th class="" rowspan="2">Date</th>
                    <th class="w-25" rowspan="2">Operation</th>
                    <th colspan="3" class="w-12">' . $libelle_nature . '</th>
                    <th class="" rowspan="2">Observation</th>
                </tr>
                <tr class="bg-success">
                    <th class="">En Devise</th>
                    <th class="">Cours</th>
                    <th class="">En XOF</th>
                </tr>
                </thead>
                <tbody>
            ';

                foreach ($lignes as $ligne) {
                    echo '<tr>';
                    echo '
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['piece_operation']) .'">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['compte_operation']) .'">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['tag_operation']) .'">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['date_operation']) .'">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['designation_operation']) .'">
                    </td>
                    
                    <td class="">
                        <input type="text" class="form-control form-control-sm text-uppercase text-right" value="'. stripslashes($ligne['montant_operation']) .'">
                    </td>
                    
                    <td class="">
                        <input type="text" class="form-control form-control-sm text-uppercase text-right" value="'. stripslashes($ligne['cours_operation']) .'">
                    </td>
                    
                    <td class="">
                        <input type="text" class="form-control form-control-sm text-uppercase text-right" value="'. stripslashes($ligne['montant_xof_operation']) .'" readonly>
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase">
                    </td>
                    ';
                    echo '</tr>';
                }

            }
        }
    }