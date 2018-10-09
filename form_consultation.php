<?php
/**
 * Created by PhpStorm.
 * User: Ange KOUAKOU
 * Date: 30/09/2018
 * Time: 15:22
 */
?>
<div class="bg-white p-2" style="border-radius: 10px">
    <div class="container-fluid">
        <a id="retour" class="alert alert-info py-1" role="button" data-toggle="tooltip" data-placement="right"
           title="Page d'accueil" href="index.php">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="row">
            <div class="col-10 cadre mb-4 p-4 mx-auto">
                <div class="row">
                    <div class="col-8">
                        <div class="form-inline">
                            <label for="debut" class="">Période :</label>
                            <div class="input-group input-group-sm mb-2 mx-2">
                                <input type="date" class="form-control" id="debut">
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                </div>
                            </div>
                            <div class="input-group input-group-sm mb-2 mx-2">
                                <input type="date" class="form-control" id="fin">
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-inline">
                            <label class="" for="solde_avant">Solde Avant:</label>
                            <input type="text" class="form-control form-control-sm mx-2 text-right" id="solde_avant" readonly>
                            <strong><span>XOF</span></strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <label for="">Nature :</label>
                        <div class="form-check form-check-inline mx-2">
                            <input class="form-check-input" type="radio" id="choix_simple" value="1">
                            <label class="form-check-label" for="choix_simple">Simple</label>
                        </div>
                        <div class="form-check form-check-inline mx-2">
                            <input class="form-check-input" type="radio" id="choix_detaille" value="2">
                            <label class="form-check-label" for="choix_detaille">Détaillée</label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mx-5 px-4" id="test" onclick="consultation();">
                            Consulter <i class="fas fa-search ml-1"></i>
                        </button>
                    </div>
                    <div class="col-4">
                        <div class="form-inline">
                            <label class="" for="solde_avant">Solde Après:</label>
                            <input type="text" class="form-control form-control-sm mx-2 text-right" id="solde_apres" readonly>
                            <strong><span>XOF</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="feedback_consultation"></div>
    </div>
</div>