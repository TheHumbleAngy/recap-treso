<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 9:30 AM
     */

    abstract class class_banque {
        protected $id_banque;
        protected $libelle_banque;
        protected $pays_banque;
        protected $entite_banque;
        protected $monnaie_banque;

        protected $connection;
    }

    class banque extends class_banque {

        public function __construct() {
            $this->connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
            if ($this->connection->connect_error)
                die($this->connection->connect_error);
        }

        /**
         * @return mixed
         */
        public function getIdBanque() {
            return $this->id_banque;
        }

        /**
         * @param mixed $id_banque
         */
        public function setIdBanque($id_banque) {
            $this->id_banque = $id_banque;
        }

        /**
         * @return mixed
         */
        public function getLibelleBanque() {
            return $this->libelle_banque;
        }

        /**
         * @param mixed $libelle_banque
         */
        public function setLibelleBanque($libelle_banque) {
            $this->libelle_banque = $libelle_banque;
        }

        /**
         * @return mixed
         */
        public function getPaysBanque() {
            return $this->pays_banque;
        }

        /**
         * @param mixed $pays_banque
         */
        public function setPaysBanque($pays_banque) {
            $this->pays_banque = $pays_banque;
        }

        /**
         * @return mixed
         */
        public function getMonnaieBanque() {
            return $this->monnaie_banque;
        }

        /**
         * @param mixed $monnaie_banque
         */
        public function setMonnaieBanque($monnaie_banque) {
            $this->monnaie_banque = $monnaie_banque;
        }

        /**
         * @return mixed
         */
        public function getEntiteBanque() {
            return $this->entite_banque;
        }

        /**
         * @param mixed $entite_banque
         */
        public function setEntiteBanque($entite_banque) {
            $this->entite_banque = $entite_banque;
        }

        function setData($libelle_banque, $pays_banque, $entite_banque, $monnaie_banque) {
            try {
                $this->setLibelleBanque(stripcslashes($libelle_banque));
                $this->setPaysBanque($pays_banque);
                $this->setEntiteBanque(stripcslashes($entite_banque));
                $this->setMonnaieBanque($monnaie_banque);

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        function getData() {
            try {
                return $arr_banque = array($this->getIdBanque(), $this->getLibelleBanque(), $this->getEntiteBanque(), $this->getPaysBanque(), $this->getMonnaieBanque());;
            } catch (Exception $e) {
                return false;
            }
        }

        function saveData() {
            $req = "SELECT id_banque FROM banque ORDER BY id_banque DESC LIMIT 1";
            $resultat = $this->connection->query($req);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

                $id_banque = "";
                foreach ($lignes as $ligne) {
                    $id_banque = stripslashes($ligne['id_banque']);
                }

                $id_banque = substr($id_banque, -2);
                $id_banque++;
            } else {
                $id_banque = 1;
            }

            $b = "BANQ";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%02d';

            $this->id_banque = $dat . $b . sprintf($format, $id_banque);

            $sql = "INSERT INTO banque(id_banque, 
                                        libelle_banque, 
                                        pays_banque, 
                                        entite_banque, 
                                        monnaie_banque)
                    VALUES ('$this->id_banque',
                            '$this->libelle_banque',
                            '$this->pays_banque',
                            '$this->entite_banque',
                            '$this->monnaie_banque')";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function updateData($id) {
            $sql = "UPDATE banque SET 
                      libelle_banque = '" . $this->libelle_banque . "',
                      pays_banque = '" . $this->pays_banque . "',
                      entite_banque = '" . $this->entite_banque . "',
                      monnaie_banque = '" . $this->monnaie_banque . "',
                    WHERE id_banque = '" . $id . "'";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function deleteData($id) {
            $sql = "DELETE FROM banque WHERE id_banque = '" . $id . "'";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }