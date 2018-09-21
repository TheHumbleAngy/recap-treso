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

        valider.prop('disabled', check);

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
            str = $(this).attr('id');

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

                // console.log($('#monnaie').html(monnaie));
                $('#monnaie').html(sign);

                $('#entite').attr('placeholder', entite);
                $('#solde').attr('placeholder', solde);

                // $('#feedback').html(data);
                // check = true;
            }
        });
    });


</script>
</body>
</html>