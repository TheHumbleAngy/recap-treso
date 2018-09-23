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

</head>
<body>
<div class="container-fluid">
    <h3 class="display-3 d-flex justify-content-center mb-5">Recap Trésorerie - 2018</h3>

    <div id="content">
        <?php include $page; ?>
    </div>
</div>

<script src="bootstrap/jquery-3.3.1.min.js"></script>
<script src="bootstrap/popper-1.14.3.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    let nombre = $('#nombre'),
        nature_ = $('#nature'),
        saisir = $('#saisir'),
        valider = $('#valider'),
        check = false;

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
                $('#feedback').html(resultat);
                check = true;
            }
        });

        valider.prop('disabled', false);

        /* An alternative way to use AJAX
        $.post(
            'ajax.php',
            {nbr: n},
            function (data) {
                alert(data);
        });*/
    });

    function goBack() {
        window.history.back();
    }

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
        $('.message').html(text);

        $.ajax({
            type: 'POST',
            url: 'ajax_banq_info.php',
            data: {
                monnaie: monnaie,
                banque: banque,
                pays: pays
            },
            success: function (data) {
                // console.log(data);
                json_data = JSON.parse(data);
                let entite = json_data[0].entite,
                    solde = json_data[0].solde,
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

                $('#monnaie').html(sign);
                $('#entite').attr('placeholder', entite);

                $('#solde').attr('placeholder', solde);
                $('#idbanque').html(id_banque);
                // $('#feedback').html(data);
                // check = true;

            }
        });
    });

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
            date_op[i] = $('[id*="date"]')[i].value;
            designation_op[i] = $('[id*="operation"]')[i].value;
            cours_op[i] = $('[id*="cours"]')[i].value;
            devise_op[i] = $('[id*="devise"]')[i].value;
            xof_op[i] = $('[id*="xof"]')[i].value;
            observation_op[i] = $('[id*="observation"]')[i].value;
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
            info = "nbr=" + nbr + "&id_banque=" + id_banque + "&id_type_operation=" + nature + "&piece_operation=" + json_piece_op + "&compte_operation=" + json_compte_op + "&tag_operation=" + json_libelle_op + "&date_saisie_operation=" + datesaisie_op + "&date_operation=" + json_date_op + "&designation_operation=" + json_designation_op + "&cours_operation=" + json_cours_op + "&montant_operation=" + json_devise_op + "&montant_xof_operation=" + json_xof_op + "&observation_operation=" + json_observation_op,
            action = "ajout_operation";

        $.ajax({
            type: 'POST',
            url: 'updatedata.php?action=' + action,
            data: info,
            success: function (data) {
                $('.feedback').html(data);
                setTimeout(function () {
                    $('.alert-success').slideToggle('slow');
                }, 2500);
            }
        });
    }
</script>
</body>
</html>