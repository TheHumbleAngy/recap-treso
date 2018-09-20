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
    <h3 class="display-3 d-flex justify-content-center mb-5">Recap Trésorerie 2018</h3>

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
        saisir = $('#saisir'),
        valider = $('#valider'),
        check = false;

    saisir.click(function () {
        let n = nombre.val();

        // console.log("before AJAX n is " + n);

        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: {
                nbr: n
            },
            success: function (resultat) {
                // console.log('feedback');
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
</script>
</body>
</html>