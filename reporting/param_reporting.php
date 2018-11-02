<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 30-Oct-18
     * Time: 11:41 PM
     */
?>
<div id="wrapper_consultation" class="shadow gradient">
    <a class="retour mx-2" role="button" data-toggle="tooltip" data-placement="right"
       title="Accueil" href="index.php">
        <i class="fas fa-home fa-1-5x"></i>
    </a>

    <?php
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
    ?>
    <div style="padding: 20px 80px 40px;">
        <div class="container-fluid">
            <form action="index.php?page=reporting/form_reporting" method="post">
                <div class="form-group row my-3">
                    <div class="col">
                        <div class="custom-control custom-radio custom-control-inline mx-2">
                            <input type="radio" id="rdo_toutes" name="rdo_nature" class="custom-control-input" value="toutes" onclick="$('#liste_banques').hide(); console.log($('[name=\'rdo_nature\']:checked').val())">
                            <label class="custom-control-label" for="rdo_toutes">Toute les banques</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline mx-2">
                            <input type="radio" id="rdo_select" name="rdo_nature" class="custom-control-input" value="selection" onclick="$('#liste_banques').show(); console.log($('[name=\'rdo_nature\']:checked').val())">
                            <label class="custom-control-label" for="rdo_select">Sélection</label>
                        </div>
                    </div>
                </div>
                <div id="liste_banques">
                    <select id="lst_banq" class="custom-select" multiple onchange="assignListeBanque()">
                        <?php
                            $sql = "
SELECT DISTINCT libelle_banque, libelle_pays
FROM banques b 
  INNER JOIN banque_pays_monnaie bpm ON b.id_banque = bpm.id_banque
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
  INNER JOIN pays p ON bpm.id_pays = p.id_pays
  ORDER BY libelle_banque";

                            if ($resultat = $connection->query($sql)) {
                                if ($resultat->num_rows > 0) {
                                    $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                                    foreach ($lignes as $ligne) {
                                        $libelle_banque = stripcslashes($ligne['libelle_banque']);
                                        $monnaie = stripcslashes($ligne['sigle_monnaie']);
                                        $libelle_pays = stripcslashes($ligne['libelle_pays']);
                                        ?>
                                        <option value="<?php echo $libelle_banque . '_' . $libelle_pays . '_' . $monnaie; ?>"><?php echo strtoupper($libelle_banque . ' ' . $libelle_pays . ' ' . $monnaie); ?></option>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </select>
                    <label for="lst_banq"></label>
                    <label>
                        <textarea class="form-control" id="select_liste_banques" name="liste" hidden></textarea>
                    </label>
                </div>
                <div class="d-flex justify-content-start">
                    <button type="submit" id="proceder"
                       class="btn btn-primary btn-sm btn-lg my-2 faa-parent animated-hover">
                        Procéder
                        <i class="fa fa-arrow-right ml-2 faa-passing"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>