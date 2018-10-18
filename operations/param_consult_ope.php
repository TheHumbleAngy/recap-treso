<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 09-Oct-18
     * Time: 9:32 PM
     */
?>
<div id="wrapper_consultation" class="shadow gradient">
    <a id="retour" class="mx-2" role="button" data-toggle="tooltip" data-placement="right"
       title="Accueil" href="index.php">
        <i class="fas fa-home fa-1-5x"></i>
    </a>

    <?php
        $connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
    ?>
    <div style="padding: 20px 80px 40px;">
        <div class="container-fluid">
            <form>
                <div class="row">
                    <div class="col-8">
                        <div class="row my-3">
                            <div class="col-4">
                                <label for="param_entite">Entité</label>
                            </div>
                            <div class="col-8">
                                <select class="custom-select custom-select-sm" name="entite" id="param_entite">
                                    <option selected>Sélectionner...</option>
                                    <?php
                                        $sql_entite = "SELECT DISTINCT entite_banque FROM banque ORDER BY entite_banque";

                                        if ($resultat = $connection->query($sql_entite)) {
                                            if ($resultat->num_rows > 0) {
                                                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);
                                                foreach ($lignes as $ligne) {
                                                    $entite = stripcslashes($ligne['entite_banque']);
                                                    ?>
                                                    <option value="<?php echo $entite; ?>"><?php echo strtoupper($entite); ?></option>
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
