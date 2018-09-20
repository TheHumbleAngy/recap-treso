<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 19-Sep-18
     * Time: 2:03 PM
     */

    if (isset($_POST['nbr']) && empty($_POST['nbr']) === false) {
        $nbr = htmlspecialchars($_POST['nbr'], ENT_QUOTES);

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

        for ($i = 0; $i < $nbr; $i++) {
//            echo '<span>Test</span>';
            echo '
            <tr>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
                <td><input type="text" class="form-control form-control-sm"></td>
            </tr>
        ';
        }
        echo '
        </tbody>
            </table>
        ';

    }