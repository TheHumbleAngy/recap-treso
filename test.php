<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 21-Sep-18
     * Time: 11:17 AM
     */
?>

<div class="bg-white p-2" style="border-radius: 10px">
    <a id="retour" class="alert alert-info py-0 px-2" role="button" data-toggle="tooltip" data-placement="right"
       title="Page d'accueil" href="index.php">
        <i class="fas fa-arrow-left"></i>
    </a>

    <?php
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');

        $sql_banque = "SELECT DISTINCT libelle_banque, pays_banque FROM banque ORDER BY libelle_banque";
        if ($resultat = $connection->query($sql_banque)) {
            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                ?>

                <ul class="nav nav-tabs">
                    <?php
                        foreach ($lignes as $ligne) {
                            $libelle = stripcslashes($ligne['libelle_banque']);
                            $pays = stripcslashes($ligne['pays_banque']);
                            $abbr = substr($libelle, 0, 4);
                            ?>
                            <li class="nav-item dropdown" id="<?php echo 'nav_' . $libelle . '_' . $pays; ?>">

                    <span hidden id="<?php echo 'banque_' . $libelle; ?>"><?php echo $libelle; ?></span>
                    <span hidden id="<?php echo 'pays_' . $pays; ?>"><?php echo $pays; ?></span>

                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="true"
                                   id="<?php echo 'banq_' . $libelle . '_' . $pays; ?>"
                                   aria-expanded="false">

                        <span class="text-uppercase"><?php echo $abbr . ". " . $pays; ?></span>

                                </a>
                                <div class="dropdown-menu">
                                    <?php
                                        $sql_banque = "SELECT DISTINCT monnaie_banque, id_banque FROM banque WHERE libelle_banque = '$libelle' AND pays_banque = '$pays'";
                                        if ($resultat = $connection->query($sql_banque)) {
                                            if ($resultat->num_rows > 0) {
                                                $listes = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($listes as $liste) {
                                                    $id_banque = stripcslashes($liste['id_banque']);
                                                    $monnaie = stripcslashes($liste['monnaie_banque']);
                                                    $sign = "";

                                                    switch ($monnaie) {
                                                        case "usd":
                                                            $sign = '<i class="fas fa-dollar-sign text-uppercase"></i>';
                                                            break;
                                                        case "euro":
                                                            $sign = '<i class="fas fa-euro-sign text-uppercase"></i>';
                                                            break;
                                                        default:
                                                            $sign = '<span class="text-uppercase">' . $monnaie . '</span>';
                                                    }
                                                    ?>

                                                    <span hidden id="idbanque"></span>
                                                    <span hidden id="<?php echo 'monnaie_' . $monnaie; ?>"><?php echo $monnaie; ?></span>

                                                    <a class="dropdown-item"
                                                       id="<?php echo 'menu_' . $monnaie . '_' . $libelle . '_' . $pays . '_' . $id_banque; ?>"
                                                       href="<?php echo '#' . $monnaie . '_' . $libelle . '-tab'; ?>"
                                                       data-toggle="tab"
                                                       aria-controls="<?php echo $monnaie . '_' . $libelle; ?>"
                                                       aria-expanded="true">
                                                        <span class="text-uppercase"><?php echo $monnaie; ?></span>
                                                    </a>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </li>
                            <?php
                        } ?>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane active"
                         id="<?php echo '#' . $monnaie . '_' . $libelle . '-tab'; ?>"
                         role="tabpanel"
                         aria-labelledby="<?php echo 'menu_' . $monnaie . '_' . $libelle . '_' . $pays; ?>"
                         aria-expanded="false">
                        <h5 class="display-5 d-flex justify-content-center my-3 mx-auto py-2 w-25 titre text-uppercase cadre insetshadow">
                            <?php echo $libelle . ' - ' . $monnaie; ?>
                        </h5>

                        <div class="row container-fluid">
                            <div class="col-5">
                                <div class="row cadre p-4">
                                    <div class="container-fluid">
                                        <form>
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="form-group row my-0">
                                                        <label for="nature" class="col-sm-4 ">Nature</label>
                                                        <div class="col-sm-8">
                                                            <select class="custom-select custom-select-sm" id="nature" disabled>
                                                                <option selected>Sélectionner...</option>
                                                                <option value="0">Dépense</option>
                                                                <option value="1">Recette</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="form-group row my-0">
                                                        <label for="nombre" class="col-sm-7 ">Nbr. de lignes</label>
                                                        <div class="col-sm-5">
                                                            <input type="number" class="form-control form-control-sm" id="nombre" min="1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1">
                                                    <button class="btn btn-sm btn-outline-primary px-3" type="button" id="saisir"
                                                            data-toggle="tooltip" data-placement="right"
                                                            title="Cliquez ici pour commencer à saisir">
                                                        <i class="fas fa-arrow-circle-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="co-3 mt-4">
                                        <div class="d-flex flex-column">
                                            <button class="btn btn-block btn-outline-primary px-4" id="valider" disabled onclick="ajoutOperation();">
                                                Valider <i class="far fa-check-circle ml-1"></i>
                                            </button>
                                            <div class="modal fade" id="modal-response" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Operation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id="content-response"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col ml-2">
                                <div class="row cadre p-4">
                                    <div class="container-fluid">
                                        <form>
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group form-inline row my-0">
                                                        <label for="entite" class="col-sm-2">Entité</label>
                                                        <div class="col-sm-6">
                                                            <h4 class="mb-0"><span class="badge badge-secondary text-uppercase" id="entite">#</span></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group form-inline row my-0">
                                                        <label for="solde_xof" class="col-sm-3" title="Solde précédent">Solde Préc.</label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control text-right ncare" id="solde_xof" placeholder="0" aria-placeholder="Solde XOF" readonly>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text text-uppercase" id="monnaie_xof"><strong>XOF</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control text-right ncare" id="solde_devise" placeholder="0" aria-placeholder="Solde Devise" readonly>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text text-uppercase" id="monnaie_devise"><strong>usd</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="feedback"></div>
                    </div>
                </div>

                <?php
            }
        }
    ?>

</div>