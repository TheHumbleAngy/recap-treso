<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 15-Oct-18
     * Time: 3:16 PM
     */

    class class_banques_pays_monnaies {
        protected $id_banque;
        protected $id_pays;
        protected $id_monnaie;
        protected $entite_banque;

        protected $connection;
    }

    class banques_pays_monnaies extends class_banques_pays_monnaies {

        /**
         * banque_pays_monnaie constructor.
         */
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
        public function getIdMonnaie() {
            return $this->id_monnaie;
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

        /**
         * @param mixed $id_monnaie
         */
        public function setIdMonnaie($id_monnaie) {
            $this->id_monnaie = $id_monnaie;
        }

        function setData($id_banque, $id_pays, $id_monnaie, $entite_banque) {
            try {
                $this->setIdBanque(stripcslashes($id_banque));
                $this->setIdPays(stripcslashes($id_pays));
                $this->setIdMonnaie(stripcslashes($id_monnaie));
                $this->setEntiteBanque(strtoupper(stripcslashes($entite_banque)));

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        function getData() {
            try {
                return $arr = array($this->getIdBanque(), $this->getIdPays(), $this->getIdMonnaie(), $this->getEntiteBanque());
            } catch (Exception $e) {
                return false;
            }
        }

        function saveData() {
            $sql = "INSERT INTO banque_pays_monnaie(id_banque, id_pays, id_monnaie, entite_banque) VALUES ('$this->id_banque', '$this->id_pays', '$this->id_monnaie', '$this->entite_banque')";

//            echo $sql;
            if ($result = mysqli_query($this->connection, $sql))
                return true;
            else
                return false;
        }
    }