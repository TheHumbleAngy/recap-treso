<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 21-Sep-18
     * Time: 11:17 AM
     */
?>

<div class="">

    <?php
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');

        $sql_banque = "SELECT DISTINCT libelle_banque, pays_banque FROM banque ORDER BY libelle_banque";
        if ($resultat = $connection->query($sql_banque)) {
            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                ?>

                <ul class="nav nav-tabs">
                    <?php
                        foreach ($lignes as $ligne) {
                            $libelle = stripcslashes($ligne['libelle_banque']);
                            $pays = stripcslashes($ligne['pays_banque']);
                            $abbr = substr($libelle, 0, 4);
                            ?>
                            <li class="nav-item dropdown" id="<?php echo 'nav_' . $libelle . '_' . $pays; ?>">

                    <span hidden id="<?php echo 'banque_' . $libelle; ?>"><?php echo $libelle; ?></span>
                    <span hidden id="<?php echo 'pays_' . $pays; ?>"><?php echo $pays; ?></span>

                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="true"
                                   id="<?php echo 'banq_' . $libelle . '_' . $pays; ?>"
                                   aria-expanded="false">

                        <span class="text-uppercase"><?php echo $abbr . ". " . $pays; ?></span>

                                </a>
                                <div class="dropdown-menu">
                                    <?php
                                        $sql_banque = "SELECT DISTINCT monnaie_banque FROM banque WHERE libelle_banque = '$libelle' AND pays_banque = '$pays'";
                                        if ($resultat = $connection->query($sql_banque)) {
                                            if ($resultat->num_rows > 0) {
                                                $listes = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($listes as $liste) {
                                                    $monnaie = stripcslashes($liste['monnaie_banque']);
                                                    ?>

                                                    <span hidden id="<?php echo 'monnaie_' . $monnaie; ?>"><?php echo $monnaie; ?></span>

                                                    <a class="dropdown-item"
                                                       id="<?php echo 'menu_' . $monnaie . '_' . $libelle . '_' . $pays; ?>"
                                                       href="<?php echo '#' . $monnaie . '_' . $libelle . '-tab'; ?>"
                                                       data-toggle="tab"
                                                       aria-controls="<?php echo $monnaie . '_' . $libelle; ?>"
                                                       aria-expanded="true">

                                            <span class="text-uppercase"><?php echo $monnaie; ?></span>

                                                    </a>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </li>
                            <?php
                        } ?>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane active"
                         id="<?php echo '#' . $monnaie . '_' . $libelle . '-tab'; ?>"
                         role="tabpanel"
                         aria-labelledby="<?php echo 'menu_' . $monnaie . '_' . $libelle . '_' . $pays; ?>"
                         aria-expanded="false">
                        <h5 class="display-5 d-flex justify-content-center my-4 message text-uppercase">
                            <?php echo $libelle . ' - ' . $monnaie; ?>
                        </h5>

                        <div class="row">
                            <div class="col-3 my-2">
                                <form class="form-inline">
                                    <label for="entite">Entité :</label>
                                    <input type="text" class="form-control form-control-sm mx-2 text-uppercase"
                                           id="entite" placeholder="" readonly>
                                </form>
                            </div>
                            <div class="col-4 my-2">
                                <form class="form-inline">
                                    <label for="solde">Solde Précédent :</label>
                                    <input type="text" class="form-control form-control-sm mx-2 text-right" id="solde" readonly>
                                    <label for="solde" id="monnaie"><i class="fas fa-dollar-sign"></i></label>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 my-2">
                                <form class="form-inline">
                                    <label for="nature">Nature :</label>
                                    <select class="form-control form-control-sm mx-2" id="nature">
                                        <option>Sélectionner...</option>
                                        <option value="0">Dépense</option>
                                        <option value="1">Recette</option>
                                    </select>
                                </form>
                            </div>
                            <div class="col-md-3 my-2">
                                <form class="form-inline">
                                    <label for="nombre">Nombre de lignes :</label>
                                    <input type="number" class="form-control form-control-sm mx-2" id="nombre" min="1"
                                           style="width: 15%">
                                    <button class="btn btn-sm btn-outline-primary px-3" type="button" id="saisir"
                                            data-toggle="tooltip" data-placement="right"
                                            title="Cliquez ici pour commencer à saisir">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="d-flex">
                                <div class="p-2 bd-highlight mx-auto">
                                    <button class="btn btn-block btn-outline-primary px-5" id="valider" disabled>
                                        Valider
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="feedback"></div>

                    </div>
                </div>

                <?php
            }
        }
    ?>

</div>

<script>

</script>