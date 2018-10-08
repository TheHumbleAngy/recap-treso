<?php
    if (isset($_GET['page']))
        $page = $_GET['page'];
    else
        $page = "accueil";
    $page .= '.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recap Treso 1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="addons/css/all.min.css">

    <!-- Custom style -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="datepicker/gijgo.css">
<!--    <link rel="stylesheet" href="addons/daterangepicker/daterangepicker.css">-->

</head>
<body class="bg-light">
<div class="container-fluid">
    <h4 class="display-4 d-flex justify-content-center mt-1 mb-4 mx-auto pb-3 w-75 cadre retroshadow">Recap Trésorerie - 2018</h4>

    <div id="content">
        <?php include $page; ?>
    </div>
</div>

<script src="bootstrap/jquery-3.3.1.min.js"></script>
<script src="bootstrap/popper-1.14.3.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>

<script src="datepicker/gijgo.js"></script>
<!--<script src="addons/daterangepicker/moment.min.js"></script>-->
<!--<script src="addons/daterangepicker/daterangepicker.js"></script>-->

<script>
    /*$(".datepicker").each(function(){
        $(this).datepicker({
            uiLibrary: "bootstrap4",
            iconsLibrary: "fontawesome",
            size: "small",
            format: "dd-mm-yyyy"
        });
    });*/
    let nombre = $('#nombre'),
        nature_ = $('#nature'),
        saisir = $('#saisir'),
        valider = $('#valider'),
        check = false,
        choix = 1;

    $('.form-check-input').click(function () {
        choix = $(this).val();
    });
    $(document).ready(function () {
        /*$('#periode').daterangepicker({
            startDate: moment().startOf(),
            locale: {
                format: 'DD-MM-YYYY'
            }
        });*/
        $('#nature').prop('disabled', true);
        $('#nombre').prop('disabled', true);
        $('#saisir').prop('disabled', true);

        $('#valider').prop('disabled', true);

    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    saisir.click(function () {
        let n = nombre.val(),
            nature = nature_.val();

        $.ajax({
            type: 'POST',
            url: 'ajax_saisie_operations.php',
            data: {
                nbr: n,
                nature: nature
            },
            success: function (resultat) {
                let feedback = $('#feedback');

                feedback.empty();
                feedback.html(resultat);
                check = true;
            }
        });

        valider.prop('disabled', false);

        /* TODO: An alternative way to use AJAX
        $.post(
            'ajax.php',
            {nbr: n},
            function (data) {
                alert(data);
        });*/
    });

    $("[id*='menu_']").click(function () {

        let banque,
            monnaie,
            pays,
            str = $(this).attr('id'),
            arr_banque = $(this).attr('id').split("_"),
            id_banque = arr_banque[4];

        arr = str.split("_");
        monnaie = arr[1];
        banque = arr[2];
        pays = arr[3];

        let text = banque + ' ' + pays + ' - ' + monnaie;
        $('.titre').html(text);

        $.ajax({
            type: 'POST',
            url: 'ajax_banq_info.php',
            data: {
                monnaie: monnaie,
                banque: banque,
                pays: pays
            },
            success: function (data) {
                json_data = JSON.parse(data);
                // console.log(data);
                let entite = json_data[0].entite,
                    solde_xof = json_data[0].solde_xof,
                    solde_devise = json_data[0].solde_devise,
                    sign = "";

                switch (monnaie) {
                    case "usd":
                        sign = '<i class="fas fa-dollar-sign text-uppercase"></i>';
                        break;
                    case "euro":
                        sign = '<i class="fas fa-euro-sign text-uppercase"></i>';
                        break;
                    default:
                        sign = '<strong><span class="text-uppercase">' + monnaie + '</span></strong>';
                }

                $('#monnaie_devise').html(sign);
                $('#entite').html('#' + entite);
                $('#nature').prop('disabled', false);
                $('#nombre').prop('disabled', false);
                $('#saisir').prop('disabled', false);

                $('#solde_xof').attr('placeholder', solde_xof);
                $('#solde_devise').attr('placeholder', solde_devise);
                $('#idbanque').html(id_banque);
                $('#feedback').empty();

                valider.prop('disabled', true);
            }
        });
    });

    function ajoutBanque() {
        let libelle_banque = $('#libelle').val(),
            pays_banque = $('#pays').val(),
            entite_banque = $('#entite').val(),
            monnaie_banque = $('#monnaie').val(),
            info, action;

        if ($.trim(libelle_banque) === "") {
            console.log("empty");
        }
        else {
            info = "libelle_banque=" + libelle_banque +
                "&pays_banque=" + pays_banque +
                "&entite_banque=" + entite_banque +
                "&monnaie_banque=" + monnaie_banque,
            action = "ajout_banque";

            $.ajax({
                type: 'POST',
                url: 'updatedata.php?action=' + action,
                data: info,
                success: function (data) {
                    $('#content-response').html(data);
                    $('#form_banque').trigger('reset');
                    $('#modal-response').modal('show');
                    /*setTimeout(function () {
                        $('#modal-response').modal('hide');
                    }, 2500);*/
                }
            });
        }
    }

    function ajoutOperation() {
        let piece_op = [],
            compte_op = [],
            libelle_op = [],
            datesaisie_op = new Date(),
            date_op = [],
            designation_op = [],
            cours_op = [],
            devise_op = [],
            xof_op = [],
            observation_op = [],
            nature = $("#nature").val(),
            id_banque = $("#idbanque").text(),
            nbr = $("#nbr_").text();

        datesaisie_op = datesaisie_op.getFullYear() + "-" + (datesaisie_op.getMonth()+1) + "-" + datesaisie_op.getDate();

        for (let i = 0; i < nbr; i = i + 1) {
            piece_op[i] = $('[id*="piece"]')[i].value;
            compte_op[i] = $('[id*="compte"]')[i].value;
            libelle_op[i] = $('[id*="libelle"]')[i].value;
            designation_op[i] = $('[id*="operation"]')[i].value.trim();
            cours_op[i] = $('[id*="cours"]')[i].value;
            devise_op[i] = $('[id*="mtt_devise"]')[i].value;
            xof_op[i] = $('[id*="mtt_xof"]')[i].value;
            observation_op[i] = $('[id*="observation"]')[i].value;

            date_op[i] = $('[id*="date"]')[i].value;
            /*let date_test = $('[id*="date"]')[i].value,
                arr_date = date_test.split('-');
            date_op[i] = arr_date[2] + '-' + arr_date[1] + '-' + arr_date[0];*/
        }

        let json_piece_op = JSON.stringify(piece_op),
            json_compte_op = JSON.stringify(compte_op),
            json_libelle_op = JSON.stringify(libelle_op),
            json_date_op = JSON.stringify(date_op),
            json_designation_op = JSON.stringify(designation_op),
            json_cours_op = JSON.stringify(cours_op),
            json_devise_op = JSON.stringify(devise_op),
            json_xof_op = JSON.stringify(xof_op),
            json_observation_op = JSON.stringify(observation_op),
            info = "nbr=" + nbr +
                "&id_banque=" + id_banque +
                "&id_type_operation=" + nature +
                "&piece_operation=" + json_piece_op +
                "&compte_operation=" + json_compte_op +
                "&tag_operation=" + json_libelle_op +
                "&date_saisie_operation=" + datesaisie_op +
                "&date_operation=" + json_date_op +
                "&designation_operation=" + json_designation_op +
                "&cours_operation=" + json_cours_op +
                "&montant_operation=" + json_devise_op +
                "&montant_xof_operation=" + json_xof_op +
                "&observation_operation=" + json_observation_op,
            action = "ajout_operation";

        $.ajax({
            type: 'POST',
            url: 'updatedata.php?action=' + action,
            data: info,
            success: function (data) {
                $('#content-response').html(data);
                $('#feedback').empty();
                $('#modal-response').modal('show');
                valider.prop('disabled', true);
                /*setTimeout(function () {
                    $('.alert-success').slideToggle('slow');
                }, 2500);*/
            }
        });
    }
    
    function test() {
        let debut = $('#debut').val(),
            fin = $('#fin').val(),

        arr = debut.split('-');
        debut = arr[2] + "-" + arr[1] + "-" + arr[0];

        arr = fin.split('-');
        fin = arr[2] + "-" + arr[1] + "-" + arr[0];

        // console.log(choix);

        $.ajax({
            type: 'POST',
            url: 'ajax_consultation.php',
            data: {
                debut: debut,
                fin: fin,
                choix: choix
            },
            success: function (data) {
                // console.log(data);
                $('#feedback_consultation').html(data);
                // console.log($('#solde_avt').val());
                $('#solde_avant').val($('#solde_avt').val());
                $('#solde_apres').val($('#solde_apr').val());
            }
        })
    }

</script>
</body>
</html>