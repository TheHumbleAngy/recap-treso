<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 15-Oct-18
     * Time: 12:09 PM
     */

    abstract class class_pays {
        protected $id_pays;
        protected $libelle_pays;
        protected $continent_pays;
        protected $zone_geographique;
        protected $zone_eco;

        protected $connection;
    }

    class pays extends class_pays {

        /**
         * pays constructor.
         */
        public function __construct() {
            $this->connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
            if ($this->connection->connect_error)
                die($this->connection->connect_error);
        }

        /**
         * @return mixed
         */
        public function getIdPays() {
            return $this->id_pays;
        }

        /**
         * @param mixed $id_pays
         */
        public function setIdPays($id_pays) {
            $this->id_pays = $id_pays;
        }

        /**
         * @return mixed
         */
        public function getLibellePays() {
            return $this->libelle_pays;
        }

        /**
         * @param mixed $libelle_pays
         */
        public function setLibellePays($libelle_pays) {
            $this->libelle_pays = $libelle_pays;
        }

        /**
         * @return mixed
         */
        public function getContinentPays() {
            return $this->continent_pays;
        }

        /**
         * @param mixed $continent_pays
         */
        public function setContinentPays($continent_pays) {
            $this->continent_pays = $continent_pays;
        }

        /**
         * @return mixed
         */
        public function getZoneGeographique() {
            return $this->zone_geographique;
        }

        /**
         * @param mixed $zone_geographique
         */
        public function setZoneGeographique($zone_geographique) {
            $this->zone_geographique = $zone_geographique;
        }

        /**
         * @return mixed
         */
        public function getZoneEco() {
            return $this->zone_eco;
        }

        /**
         * @param mixed $zone_eco
         */
        public function setZoneEco($zone_eco) {
            $this->zone_eco = $zone_eco;
        }

        function setData($libelle_pays, $continent_pays, $zone_geographique, $zone_eco) {
            try {
                $this->setLibellePays(stripcslashes($libelle_pays));
                $this->setContinentPays(stripcslashes($continent_pays));
                $this->setZoneGeographique(stripcslashes($zone_geographique));
                $this->setZoneEco(stripcslashes($zone_eco));

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        function getData() {
            try {
                return $arr_pays = array($this->getIdPays(), $this->getLibellePays(), $this->getContinentPays(), $this->getZoneGeographique(), $this->getZoneEco());
            } catch (Exception $e) {
                return false;
            }
        }

        function saveData() {
            $sql = "SELECT id_pays FROM pays ORDER BY id_pays DESC LIMIT 1";
            $resultat = $this->connection->query($sql);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

                $id = "";
                foreach ($lignes as $ligne) {
                    $id = stripslashes($ligne['id_pays']);
                }

                $id = substr($id, -2);
                $id++;
            } else {
                $id = 1;
            }

            $this->id_pays = $id;

            $sql = "INSERT INTO pays(id_pays, libelle_pays, continent_pays, zone_geographique, zone_eco)
                    VALUES ('$this->id_pays','$this->libelle_pays','$this->continent_pays','$this->zone_geographique','$this->zone_eco')";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function updateData($id) {
            $sql = "UPDATE pays SET 
                      libelle_pays = '" . $this->libelle_pays . "',
                      continent_pays = '" . $this->continent_pays . "',
                      zone_geographique = '" . $this->zone_geographique . "',
                      zone_eco = '" . $this->zone_eco . "'
                    WHERE id_pays = '" . $id . "'";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }