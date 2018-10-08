<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 2:54 PM
     */
    ?>
<div style="position: relative">
    <a class="alert alert-info py-1" role="button" data-toggle="tooltip" data-placement="right" title="Page d'accueil" href="index.php" style="position: absolute; top: 6vh">
        <i class="fas fa-home"></i>
    </a>
    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
        <li class="nav-item dropdown" id="nav_01">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" id="banq01"
               aria-expanded="false">BANQUE 01</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" id="menu_euro_banq01" href="#euro_banq01-tab" data-toggle="tab"
                   aria-controls="euro_banq01" aria-expanded="true">EURO</a>
                <a class="dropdown-item" id="menu_ngn_banq01" href="#ngn_banq01-tab" data-toggle="tab"
                   aria-controls="ngn_banq01" aria-expanded="true">NGN</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="pills-eco_nigeria_ngn-tab" data-toggle="pill" href="#pills-eco_nigeria_ngn" role="tab"
               aria-controls="pills-eco_nigeria_ngn" aria-selected="true">ECOBANK Nigeria - NGN</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-eco_nigeria_usd-tab" data-toggle="pill" href="#pills-eco_nigeria_usd" role="tab"
               aria-controls="pills-eco_nigeria_usd" aria-selected="false">ECOBANK Nigeria - USD</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-bgfi_euro-tab" data-toggle="pill" href="#pills-bgfi_euro" role="tab"
               aria-controls="pills-bgfi_euro" aria-selected="false">BGFI - EURO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-bgfi_usd-tab" data-toggle="pill" href="#pills-bgfi_usd" role="tab"
               aria-controls="pills-bgfi_usd" aria-selected="false">BGFI - USD</a>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-eco_nigeria_ngn" role="tabpanel" aria-labelledby="pills-eco_nigeria_ngn">
            <h5 class="display-5 d-flex justify-content-center my-4">ECOBANK Nigeria</h5>

            <div class="row">
                <div class="col-3 my-2">
                    <form class="form-inline">
                        <label for="entite">Entité :</label>
                        <input type="text" class="form-control form-control-sm mx-2" id="entite" placeholder="ECONIGN" disabled>
                    </form>
                </div>
                <div class="col-3 my-2">
                    <form class="form-inline">
                        <label for="monnaie">Monnaie :</label>
                        <input type="text" class="form-control form-control-sm mx-2 w-25" id="monnaie" placeholder="NGN" disabled>
                    </form>
                </div>
                <div class="col-4 my-2">
                    <form class="form-inline">
                        <label for="solde">Solde Précédent :</label>
                        <input type="text" class="form-control form-control-sm mx-2" id="solde">
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 my-2">
                    <form class="form-inline">
                        <label for="nombre">Nombre de lignes :</label>
                        <input type="number" class="form-control form-control-sm mx-2" id="nombre" min="1" style="width: 15%">
                        <button class="btn btn-sm btn-outline-primary px-3" type="button" id="saisir" data-toggle="tooltip" data-placement="right" title="Cliquez ici pour commencer à saisir">
                            teST
                        </button>
                    </form>
                </div>
            </div>
            <div class="container-fluid">
                <div class="d-flex">
                    <div class="p-2 bd-highlight mx-auto">
                        <button class="btn btn-block btn-outline-primary px-5" id="valider" disabled>Valider</button>
                    </div>
                </div>
            </div>

            <div id="form_banque">
                <div class="container-fluid">
                    <?php //include 'accueil.php'; ?>
                </div>
            </div>

            <div id="feedback" style="height: 10vh"></div>

        </div>
        <div class="tab-pane fade" id="pills-eco_nigeria_usd" role="tabpanel" aria-labelledby="pills-eco_nigeria_usd">
            <h5 class="display-5 d-flex justify-content-center my-4">ECOBANK Nigeria - USD</h5>
        </div>
        <div class="tab-pane fade" id="pills-bgfi_euro" role="tabpanel" aria-labelledby="pills-bgfi_euro">
            <h5 class="display-5 d-flex justify-content-center my-4">BGFI - EURO</h5>
        </div>
        <div class="tab-pane fade" id="pills-bgfi_usd" role="tabpanel" aria-labelledby="pills-bgfi_usd">
            <h5 class="display-5 d-flex justify-content-center my-4">BGFI - USD</h5>
        </div>
    </div>
</div>