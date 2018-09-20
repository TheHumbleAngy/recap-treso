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

    if (isset($_POST['nbr']) && empty($_POST['nbr']) === false) {
        require('connect.php');

        $value = "NGN";

        $query = "SELECT * FROM operations";

//        echo $query;

        if ($resultat = $connection->query($query)) {
            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                echo '
            <table class="table table-sm table-hover my-4 ncare bg-light font-weight-light" id="etat">
                <thead class="bg-primary text-light">
                <tr>
                    <th scope="col" rowspan="2">Pièce</th>
                    <th scope="col" rowspan="2">Compte</th>
                    <th scope="col" rowspan="2">Libellé</th>
                    <th scope="col" rowspan="2">Date</th>
                    <th scope="col" rowspan="2">Operation</th>
                    <th colspan="3">Recette</th>
                    <th colspan="3">Dépense</th>
                    <th scope="col" rowspan="2">Observation</th>
                </tr>
                <tr>
                    <th scope="col">Devise</th>
                    <th scope="col">Cours</th>
                    <th scope="col">XOF</th>
                    <th scope="col">Devise</th>
                    <th scope="col">Cours</th>
                    <th scope="col">XOF</th>
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
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['cours_operation']) .'">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['montant_operation']) .'">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" value="'. stripslashes($ligne['montant_xof_operation']) .'">
                    </td>

                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase">
                    </td>
                    
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase">
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