<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 10:54 AM
     */
    ?>
<div id="wrapper_banque" class="shadow gradient">
    <a id="retour" class="mx-2" role="button" data-toggle="tooltip" data-placement="right"
       title="Accueil" href="index.php">
        <i class="fas fa-home fa-1-5x"></i>
    </a>
    <div style="padding: 20px 80px 40px;">
        <div class="row">
            <div class="col-4 d-flex flex-column justify-content-center">
                <i class="fas fa-university ncare mx-auto"></i>
            </div>
            <div class="col offset-1">
                <h3 class="d-flex justify-content-center ncare insetshadow cadre pb-2 w-75 mx-auto">Banque</h3>
                <form id="form_banque">
                    <div class="row my-3">
                        <div class="col-4">
                            <label for="pays">Pays</label>
                        </div>
                        <div class="col-8">
                            <select class="custom-select custom-select-sm" id="pays">
                                <option selected>Sélectionner...</option>
                                <?php
                                    $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');

                                    $sql = "SELECT * FROM pays ORDER BY libelle_pays";
                                    if ($resultat = $connection->query($sql)) {
                                        if ($resultat->num_rows > 0) {
                                            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($lignes as $ligne) {
                                                $id_pays = stripcslashes($ligne['id_pays']);
                                                $libelle_pays = stripcslashes($ligne['libelle_pays']);
                                                ?>
                                                <option value="<?php echo $id_pays; ?>"><?php echo $libelle_pays; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-4">
                            <label for="monnaie">Monnaie</label>
                        </div>
                        <div class="col-8">
                            <select class="custom-select custom-select-sm" id="monnaie">
                                <option selected>Sélectionner...</option>
                                <?php
                                    $sql = "SELECT * FROM monnaies ORDER BY sigle_monnaie";
                                    if ($resultat = $connection->query($sql)) {
                                        if ($resultat->num_rows > 0) {
                                            $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                                            foreach ($lignes as $ligne) {
                                                $id_monnaie = stripcslashes($ligne['id_monnaie']);
                                                $sigle_monnaie = stripcslashes($ligne['sigle_monnaie']);
                                                $libelle_monnaie = stripcslashes($ligne['libelle_monnaie']);
                                                ?>
                                                <option value="<?php echo $id_monnaie; ?>"><?php echo $sigle_monnaie; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-4">
                            <label for="libelle">Libellé</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control form-control-sm text-uppercase" id="libelle">
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-4">
                            <label for="entite">Entité</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control form-control-sm text-uppercase" id="entite">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary btn-lg btn-sm my-2 faa-parent animated-hover" onclick="ajoutBanque();">
                            <i class="fas fa-save mr-2 faa-pulse"></i>
                            Enregistrer
                        </button>
                        <div class="modal fade" id="modal-response" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Banque</h5>
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
                </form>
            </div>
        </div>
    </div>
</div>