<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 21-Sep-18
     * Time: 11:17 AM
     */
?>

<div class="bg-white p-2" style="border-radius: 10px">
    <a class="retour" role="button" data-toggle="tooltip" data-placement="right"
       title="Accueil" href="index.php"><i class="fas fa-home fa-1-5x"></i></a>
    <a id="retour_banque" class="" role="button" data-toggle="tooltip" data-placement="right"
       title="Enregistrer une banque" href="index.php?page=banques/form_banques"><i
                class="fas fa-university fa-1-5x"></i></a>

    <?php
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');

        $sql_banque = "
        SELECT DISTINCT
          libelle_banque,
          libelle_pays,
          abbr_banque,
          abbr_pays
        FROM banques b 
          INNER JOIN banque_pays_monnaie bpm on b.id_banque = bpm.id_banque
          INNER JOIN pays p on bpm.id_pays = p.id_pays ORDER BY libelle_banque AND libelle_pays
        ";

        if ($resultat = $connection->query($sql_banque)) {
            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                ?>

                <ul class="nav nav-tabs mt-2">
                    <?php
                        foreach ($lignes as $ligne) {
                            $libelle = stripcslashes($ligne['libelle_banque']);
                            $pays = stripcslashes($ligne['libelle_pays']);
                            $abbr_banque = stripcslashes($ligne['abbr_banque']);
                            $abbr_pays = stripcslashes($ligne['abbr_pays']);
                            ?>
                            <li class="nav-item dropdown" id="<?php echo 'nav_' . $libelle . '_' . $pays; ?>">

                                <!-- potential-->
<!--                                <span hidden id="--><?php //echo 'banque_' . $libelle; ?><!--">--><?php //echo $libelle; ?><!--</span>-->
<!--                                <span hidden id="--><?php //echo 'pays_' . $pays; ?><!--">--><?php //echo $pays; ?><!--</span>-->
                                <!-- -->

                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="true"
                                   id="<?php echo 'banq_' . $libelle . '_' . $pays; ?>"
                                   title="<?php echo strtoupper($libelle). " " . strtoupper($pays); ?>"
                                   aria-expanded="false">

                                    <span class="text-uppercase"><?php echo strtoupper($abbr_banque) . ". " . strtoupper($abbr_pays) . "."; ?></span>
                                </a>
                                <div class="dropdown-menu">
                                    <?php
                                        $param_pays = addslashes($pays);
                                        $sql = "
                                        SELECT DISTINCT *
                                        FROM banques b
                                          INNER JOIN banque_pays_monnaie bpm on b.id_banque = bpm.id_banque
                                          INNER JOIN pays p on bpm.id_pays = p.id_pays
                                          INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
                                        WHERE libelle_banque = '$libelle' AND libelle_pays = '$param_pays'
                                        ORDER BY sigle_monnaie";

                                        if ($resultat = $connection->query($sql)) {
                                            if ($resultat->num_rows > 0) {
                                                $rows = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($rows as $row) {
                                                    $id_banque = stripcslashes($row['id_banque']);
                                                    $id_pays = stripcslashes($row['id_pays']);
                                                    $id_monnaie = stripcslashes($row['id_monnaie']);

                                                    $monnaie = stripcslashes($row['sigle_monnaie']);
                                                    ?>

                                                    <span hidden id="idbanque"><?php echo $id_banque;?></span>
                                                    <span hidden id="<?php echo 'monnaie_' . $id_monnaie; ?>"><?php echo $monnaie; ?></span>

                                                    <a class="dropdown-item"
                                                       id="<?php echo 'menu_' . $libelle . '_' . $pays . '_' . $monnaie . '_id_' . $id_banque . '_' . $id_pays . '_' . $id_monnaie; ?>"
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
                        <h5 class="display-5 d-flex justify-content-center my-3 mx-auto py-2 w-50 titre text-uppercase cadre insetshadow">
                            <i class="fa fa-angle-up faa-bounce animated" title="Sélectionnez une banque pour commencer..."></i>
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
                                                        <label for="nombre" class="col-sm-7">Nbr. de lignes</label>
                                                        <div class="col-sm-5">
                                                            <input type="number" class="form-control form-control-sm" id="nombre" min="1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1">
                                                    <button class="btn btn-sm btn-outline-primary px-3 faa-parent animated-hover" type="button" id="saisir"
                                                            title="Cliquez ici pour commencer à saisir" onclick="afficherSaisieOperations()">
                                                        <i class="fas fa-angle-double-down faa-float"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col ml-2">
                                <div class="row cadre p-4">
                                    <div class="container-fluid">
                                        <form>
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="form-group form-inline row my-0">
                                                        <label for="entite" class="col-sm-4">Entité</label>
                                                        <div class="col-sm-8">
                                                            <h5 class="mb-0"><span class="badge badge-secondary text-uppercase" id="entite">#</span></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group form-inline row my-0">
                                                        <label for="solde_xof" class="col-sm-3" title="Solde précédent">Solde Préc.</label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control text-right ncare font-weight-bold" id="solde_xof" placeholder="0" aria-placeholder="Solde XOF" readonly>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text text-uppercase xof" id="monnaie_xof"><strong>XOF</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control text-right ncare font-weight-bold" id="solde_devise" placeholder="0" aria-placeholder="Solde Devise" readonly>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text text-uppercase devise" id="monnaie_devise"><strong><i class="fa fa-dollar-sign"></i></strong></span>
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
                        <div class="row">
                            <div class="d-flex justify-content-center mx-auto">
                                <button class="btn btn-block btn-outline-primary mt-4 px-4 faa-parent animated-hover"
                                        id="valider" disabled onclick="ajoutOperation();">
                                    <i class="fas fa-save mr-2 faa-pulse"></i>
                                    Enregistrer
                                </button>
                            </div>
                            <div class="modal fade" id="modal-response" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Operation</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
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
                        <div id="feedback" class="mb-4"></div>
                    </div>
                </div>
                <?php
            }
        }
    ?>
</div>