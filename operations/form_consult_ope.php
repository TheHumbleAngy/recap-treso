<?php
    /**
     * Created by PhpStorm.
     * User: Ange KOUAKOU
     * Date: 30/09/2018
     * Time: 15:22
     */

    if (isset($_POST['param'])) {
        $entite = $_POST['param'];
        ?>
        <div class="bg-white p-3" style="border-radius: 10px">
            <input type="hidden" id="entite" value="<?php echo $entite; ?>">
            <div class="container-fluid">
                <div class="row">
                    <a class="retour mx-2" role="button" data-toggle="tooltip" data-placement="right"
                       title="Accueil" href="index.php">
                        <i class="fas fa-home fa-1-5x"></i>
                    </a>
                    <a class="retour mx-2" role="button" data-toggle="tooltip" data-placement="right"
                       title="Retour au parmetrage" href="index.php?page=operations/param_consult_ope">
                        <i class="fas fa-wrench fa-1x faa-wrench animated"></i>
                    </a>
                    <div class="col-10 cadre mb-4 p-4 mx-auto">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <h4 class="mb-0">
                                    <span class="badge badge-primary text-uppercase" id="entite">
                                        #<?php echo $entite; ?>
                                    </span>
                                </h4>
                            </div>
                        </div>
                        <div class="form-group row">
                            <span class="col-sm-1" title="Date de saisie">Période</span>
                            <div class="col-sm-4">
                                <div class="row">
                                    <label for="debut"></label><input type="date" class="form-control form-control-sm col mx-2" id="debut">
                                    <label for="fin"></label><input type="date" class="form-control form-control-sm col mx-2" id="fin">
                                </div>
                            </div>
                            <label for="solde_avant" class="col-sm-2 offset-1 text-right">Solde Avant</label>
                            <div class="input-group input-group-sm col-2">
                                <input type="text" class="form-control text-right ncare font-weight-bold" id="solde_avant"
                                       placeholder="0" aria-placeholder="Solde XOF" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text text-uppercase xof" id="monnaie_xof"><strong>XOF</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <span class="col-sm-1 ">Nature</span>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="custom-control custom-radio mx-2">
                                        <input type="radio" id="rdo_simple" name="rdo_nature" class="custom-control-input" value="simple">
                                        <label class="custom-control-label" for="rdo_simple">Simple</label>
                                    </div>
                                    <div class="custom-control custom-radio mx-2">
                                        <input type="radio" id="rdo_detaillee" name="rdo_nature" class="custom-control-input" value="detaillee">
                                        <label class="custom-control-label" for="rdo_detaillee">Détaillée</label>
                                    </div>
                                </div>
                            </div>
                            <label for="solde_avant" class="col-sm-2 offset-1 text-right">Solde Après</label>
                            <div class="input-group input-group-sm col-2">
                                <input type="text" class="form-control text-right ncare font-weight-bold" id="solde_apres"
                                       placeholder="0" aria-placeholder="Solde XOF" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text text-uppercase xof" id="monnaie_xof"><strong>XOF</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-primary w-25" id="consluter"
                                    onclick="consultationOperation();">
                                Consulter <i class="fas fa-search ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" id="feedback_consultation"></div>
            </div>
        </div>

        <?php
    } else {
        echo "Aucune entité n'a été sélectionnée";
    }
?>