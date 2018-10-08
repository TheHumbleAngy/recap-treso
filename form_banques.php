<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 10:54 AM
     */
    ?>
<div id="wrapper_banque" class="shadow">
    <a id="retour" class="alert alert-info py-1" role="button" data-toggle="tooltip" data-placement="right" title="Page d'accueil" href="index.php">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="row">
        <div class="col-4 d-flex flex-column justify-content-center">
            <i class="fas fa-university ncare mx-auto"></i>
        </div>
        <div class="col offset-1">
            <h2 class="d-flex justify-content-center ncare insetshadow cadre pb-2 w-75 mx-auto">Banque</h2>
            <form id="form_banque">
                <div class="input-group input-group-sm my-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Libellé <i class="fas fa-tag ml-1"></i></span>
                    </div>
                    <input type="text" class="form-control text-uppercase" id="libelle">
                </div>
                <div class="input-group input-group-sm my-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Entité <i class="fas fa-hashtag ml-1"></i></span>
                    </div>
                    <input type="text" class="form-control text-uppercase" id="entite">
                </div>
                <div class="input-group input-group-sm my-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="pays">Pays <i class="fas fa-globe-africa ml-1"></i></label>
                    </div>
                    <select class="form-control form-control-sm" id="pays">
                        <option selected>Sélectionner...</option>
                        <option value="france">FRANCE</option>
                        <option value="ghana">GHANA</option>
                        <option value="nigeria">NIGERIA</option>
                    </select>
                </div>
                <div class="input-group input-group-sm my-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="monnaie">Monnaie <i class="fas fa-dollar-sign ml-1"></i></label>
                    </div>
                    <select class="form-control form-control-sm" id="monnaie">
                        <option selected>Sélectionner...</option>
                        <option value="ghs" class="text-uppercase">GHS</option>
                        <option value="usd" class="text-uppercase">USD</option>
                        <option value="euro" class="text-uppercase">EURO</option>
                        <option value="ngn" class="text-uppercase">NGN</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary my-2" onclick="ajoutBanque();">
                        <i class="fas fa-save mr-2"></i>
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