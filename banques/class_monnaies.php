<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 15-Oct-18
     * Time: 1:38 PM
     */

    class class_monnaies {
        protected $id_monnaie;
        protected $sigle_monnaie;
        protected $libelle_monnaie;

        protected $connection;
    }

    class monnaies extends class_monnaies {

        /**
         * monnaies constructor.
         */
        public function __construct() {
            $this->connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
            if ($this->connection->connect_error)
                die($this->connection->connect_error);
        }

        /**
         * @return mixed
         */
        public function getIdMonnaie() {
            return $this->id_monnaie;
        }

        /**
         * @param mixed $id_monnaie
         */
        public function setIdMonnaie($id_monnaie) {
            $this->id_monnaie = $id_monnaie;
        }

        /**
         * @return mixed
         */
        public function getSigleMonnaie() {
            return $this->sigle_monnaie;
        }

        /**
         * @param mixed $sigle_monnaie
         */
        public function setSigleMonnaie($sigle_monnaie) {
            $this->sigle_monnaie = $sigle_monnaie;
        }

        /**
         * @return mixed
         */
        public function getLibelleMonnaie() {
            return $this->libelle_monnaie;
        }

        /**
         * @param mixed $libelle_monnaie
         */
        public function setLibelleMonnaie($libelle_monnaie) {
            $this->libelle_monnaie = $libelle_monnaie;
        }

        function setData($sigle_monnaie, $libelle_monnaie) {
            try {
                $this->setSigleMonnaie(stripcslashes($sigle_monnaie));
                $this->setLibelleMonnaie(stripcslashes($libelle_monnaie));

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        function getData() {
            try {
                return $arr_monnaie = array($this->getSigleMonnaie(), $this->getLibelleMonnaie());
            } catch (Exception $e) {
                return false;
            }
        }

        function saveData() {
            $sql = "SELECT id_monnaie FROM monnaies ORDER BY id_monnaie DESC LIMIT 1";
            $resultat = $this->connection->query($sql);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

                $id = "";
                foreach ($lignes as $ligne) {
                    $id = stripslashes($ligne['id_monnaie']);
                }

                $id = substr($id, -2);
                $id++;
            } else {
                $id = 1;
            }

            $this->id_monnaie = $id;

            $sql = "INSERT INTO monnaies(id_monnaie, sigle_monnaie, libelle_monnaie) AND 
                    VALUES ('$this->id_monnaie','$this->sigle_monnaie','$this->libelle_monnaie')";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }