<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 09-Oct-18
     * Time: 9:32 PM
     */
?>
<div id="wrapper_consultation" class="shadow gradient">
    <a class="retour mx-2" role="button" data-toggle="tooltip" data-placement="right"
       title="Accueil" href="index.php"><i class="fas fa-home fa-1-5x"></i></a>

    <?php
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
    ?>
    <div style="padding: 20px 80px;">
        <div class="container-fluid">
            <form>
                <div class="row">
                    <div class="col-8">
                        <div class="row my-3">
                            <div class="col-4">
                                <label for="param_entite">Banque</label>
                            </div>
                            <div class="col-8">
                                <select class="custom-select custom-select-sm" name="entite" id="param_entite">
                                    <option selected>Sélectionner...</option>
                                    <?php
                                        $sql = "
SELECT DISTINCT libelle_banque, libelle_pays, sigle_monnaie, entite_banque
FROM banque_pays_monnaie bpm 
  INNER JOIN banques b on bpm.id_banque = b.id_banque 
  INNER JOIN pays p on bpm.id_pays = p.id_pays
  INNER JOIN monnaies m on bpm.id_monnaie = m.id_monnaie
ORDER BY libelle_banque, libelle_pays, sigle_monnaie";

                                        if ($resultat = $connection->query($sql)) {
                                            if ($resultat->num_rows > 0) {
                                                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($lignes as $ligne) {
                                                    $entite = stripcslashes($ligne['entite_banque']);
                                                    $libelle_banque = stripcslashes($ligne['libelle_banque']);
                                                    $libelle_pays = stripcslashes($ligne['libelle_pays']);
                                                    $sigle_monnaie = stripcslashes($ligne['sigle_monnaie']);
                                                    ?>
                                                    <option value="<?php echo $entite; ?>"><?php echo strtoupper($libelle_banque) . ' ' . strtoupper($libelle_pays) . ' ' . strtoupper($sigle_monnaie); ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="details"></div>
                        <div class="d-flex justify-content-start">
                            <button type="button" id="proceder" class="btn btn-primary btn-sm btn-lg my-2 faa-parent animated-hover">
                                Procéder
                                <i class="fa fa-arrow-right ml-2 faa-passing"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex flex-column justify-content-center" style="color: #1A74B8">
                        <i class="fas fa-cog faa-spin animated fa-2x mx-auto"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>