<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 10:54 AM
     */
    ?>
<div id="wrapper_banque" class="shadow">
    <a id="retour" class="alert alert-info py-1" role="button" onclick="goBack();" data-toggle="tooltip" data-placement="right" title="Page d'accueil">
        <i class="fas fa-home"></i>
    </a>
    <div class="row">
        <div class="col-6 d-flex flex-column justify-content-center">
            <i class="fas fa-university ncare mx-auto"></i>
        </div>
        <div class="col-6">
            <h2 class="d-flex justify-content-center ncare">Banque</h2>
            <form id="form_banque">
                <div class="form-group">
                    <label for="libelle" class="h4">Libellé</label>
                    <input type="text" id="libelle" class="form-control form-control-sm text-uppercase">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="pays">Pays <i class="fas fa-globe-africa ml-1"></i></label>
                    </div>
                    <select class="custom-select" id="pays">
                        <option selected>Sélectionner...</option>
                        <option value="nigeria">NIGERIA</option>
                        <option value="france">FRANCE</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="monnaie">Monnaie <i class="fas fa-dollar-sign ml-1"></i></label>
                    </div>
                    <select class="custom-select" id="monnaie">
                        <option selected>Sélectionner...</option>
                        <option value="usd" class="text-uppercase">USD</option>
                        <option value="euro" class="text-uppercase">EURO</option>
                        <option value="ngn" class="text-uppercase">NGN</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary my-2" onclick="saveData();">
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

<script>
    function saveData() {
        let libelle_banque = $('#libelle').val(),
            pays_banque = $('#pays').val(),
            monnaie_banque = $('#monnaie').val(),
            info, operation;

        if ($.trim(libelle_banque) === "") {
            console.log("empty");
        } else {
            info = "libelle_banque=" + libelle_banque + "&pays_banque=" + pays_banque + "&monnaie_banque=" + monnaie_banque;
            operation = "add";

            $.ajax({
                type: 'POST',
                url: 'updatedata.php?operation=' + operation,
                data: info,
                success: function (data) {
                    $('#content-response').html(data);
                    $('#form_banque').trigger('reset');
                    $('#modal-response').modal('show');
                    setTimeout(function () {
                        $('#modal-response').modal('hide');
                    }, 2500);
                }
            });
        }
    }
</script>