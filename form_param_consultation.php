<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 09-Oct-18
     * Time: 9:32 PM
     */
?>
<div id="wrapper_consultation" class="shadow">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="row my-3">
                    <div class="col-4">
                        <label for="banque">Banque</label>
                    </div>
                    <div class="col-8">
                        <select class="custom-select custom-select-sm" id="banque">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-4">
                        <label for="pays">Pays</label>
                    </div>
                    <div class="col-8">
                        <select class="custom-select custom-select-sm" id="pays">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-4">
                        <label for="monnaie">Monnaie</label>
                    </div>
                    <div class="col-8">
                        <select class="custom-select custom-select-sm" id="monnaie">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <a role="button" class="btn btn-primary btn-sm btn-lg my-2 faa-parent animated-hover" href="index.php?page=form_consultation">
                        Consulter
                        <i class="fa fa-arrow-right ml-2 faa-passing"></i>
                    </a>
                </div>
            </div>
            <div class="col d-flex flex-column justify-content-center" style="color: #1A74B8">
                <i class="fas fa-cog faa-spin animated fa-4x mx-auto"></i>
            </div>
        </div>

    </div>
</div>
