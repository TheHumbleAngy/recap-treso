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
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="addons/css/all.min.css">
    <link rel="stylesheet" href="addons/css/font-awesome-animation.min.css">

    <!-- Custom style -->
    <link rel="stylesheet" href="css/styles.css">

</head>
<body class="bg-light">
<img src="images/banniere_01.png" class="img-fluid mb-2 sticky-top" alt="">
<div class="container-fluid">
    
    <div id="content">
        <?php include $page; ?>
    </div>
    <button type="button" class="btn btn-outline-primary" id="goTop" title="Retour en haut" onclick="topFunction()">
        <i class="fas fa-arrow-up fa-2x faa-vertical animated"></i>
    </button>
</div>

<script src="css/bootstrap/jquery-3.3.1.min.js"></script>
<script src="css/bootstrap/popper-1.14.3.min.js"></script>
<script src="css/bootstrap/js/bootstrap.js"></script>

<!-- Custom js file -->
<script src="js/main.js"></script>
</body>
</html>