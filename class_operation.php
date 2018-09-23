<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 22-Sep-18
     * Time: 11:46 PM
     */

    class class_operation {
        protected $id_operation;
        protected $id_banque;
        protected $id_type_operation;
        protected $piece_operation;
        protected $compte_operation;
        protected $tag_operation;
        protected $date_saisie_operation;
        protected $date_operation;
        protected $designation_operation;
        protected $cours_operation;
        protected $montant_operation;
        protected $montant_xof_operation;
        protected $observation_operation;

        protected $connection;
    }

    class operation extends class_operation {

        public function __construct() {
            $this->connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
            if ($this->connection->connect_error) {
                die($this->connection->connect_error);
            }
        }

        /**
         * @param mixed $id_banque
         */
        private function setIdBanque($id_banque) {
            $this->id_banque = $id_banque;
        }

        /**
         * @param mixed $id_type_operation
         */
        private function setIdTypeOperation($id_type_operation) {
            $this->id_type_operation = $id_type_operation;
        }

        /**
         * @param mixed $piece_operation
         */
        private function setPieceOperation($piece_operation) {
            $this->piece_operation = $piece_operation;
        }

        /**
         * @param mixed $compte_operation
         */
        private function setCompteOperation($compte_operation) {
            $this->compte_operation = $compte_operation;
        }

        /**
         * @param mixed $tag_operation
         */
        private function setTagOperation($tag_operation) {
            $this->tag_operation = $tag_operation;
        }

        /**
         * @param mixed $date_saisie_operation
         */
        private function setDateSaisieOperation($date_saisie_operation) {
            $this->date_saisie_operation = $date_saisie_operation;
        }

        /**
         * @param mixed $date_operation
         */
        private function setDateOperation($date_operation) {
            $this->date_operation = $date_operation;
        }

        /**
         * @param mixed $designation_operation
         */
        private function setDesignationOperation($designation_operation) {
            $this->designation_operation = $designation_operation;
        }

        /**
         * @param mixed $cours_operation
         */
        private function setCoursOperation($cours_operation) {
            $this->cours_operation = $cours_operation;
        }

        /**
         * @param mixed $montant_operation
         */
        private function setMontantOperation($montant_operation) {
            $this->montant_operation = $montant_operation;
        }

        /**
         * @param mixed $montant_xof_operation
         */
        private function setMontantXofOperation($montant_xof_operation) {
            $this->montant_xof_operation = $montant_xof_operation;
        }

        /**
         * @param mixed $observation_operation
         */
        private function setObservationOperation($observation_operation) {
            $this->observation_operation = $observation_operation;
        }

        /**
         * @return mixed
         */
        private function getIdBanque() {
            return $this->id_banque;
        }

        /**
         * @return mixed
         */
        private function getIdTypeOperation() {
            return $this->id_type_operation;
        }

        /**
         * @return mixed
         */
        private function getPieceOperation() {
            return $this->piece_operation;
        }

        /**
         * @return mixed
         */
        private function getCompteOperation() {
            return $this->compte_operation;
        }

        /**
         * @return mixed
         */
        private function getTagOperation() {
            return $this->tag_operation;
        }

        /**
         * @return mixed
         */
        private function getDateSaisieOperation() {
            return $this->date_saisie_operation;
        }

        /**
         * @return mixed
         */
        private function getDateOperation() {
            return $this->date_operation;
        }

        /**
         * @return mixed
         */
        private function getDesignationOperation() {
            return $this->designation_operation;
        }

        /**
         * @return mixed
         */
        private function getCoursOperation() {
            return $this->cours_operation;
        }

        /**
         * @return mixed
         */
        private function getMontantOperation() {
            return $this->montant_operation;
        }

        /**
         * @return mixed
         */
        private function getMontantXofOperation() {
            return $this->montant_xof_operation;
        }

        /**
         * @return mixed
         */
        private function getObservationOperation() {
            return $this->observation_operation;
        }

        public function setData($id_banque, $id_type_operation, $piece_operation, $compte_operation, $tag_operation, $date_saisie_operation, $date_operation, $designation_operation, $cours_operation, $montant_operation, $montant_xof_operation, $observation_operation) {
            try {
                $this->setIdBanque($id_banque);
                $this->setIdTypeOperation($id_type_operation);
                $this->setPieceOperation($piece_operation);
                $this->setCompteOperation($compte_operation);
                $this->setTagOperation($tag_operation);
                $this->setDateSaisieOperation($date_saisie_operation);
                $this->setDateOperation($date_operation);
                $this->setDesignationOperation($designation_operation);
                $this->setCoursOperation($cours_operation);
                $this->setMontantOperation($montant_operation);
                $this->setMontantXofOperation($montant_xof_operation);
                $this->setObservationOperation($observation_operation);

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        function getData() {
            try {
                return $arr_operation = array($this->getIdBanque(), $this->getIdTypeOperation(), $this->getPieceOperation(), $this->getCompteOperation(), $this->getTagOperation(), $this->getDateSaisieOperation(), $this->getDateOperation(), $this->getDesignationOperation(), $this->getCoursOperation(), $this->getMontantOperation(), $this->getMontantXofOperation(), $this->getObservationOperation());
            } catch (Exception $e) {
                return false;
            }
        }

        function saveData() {
            $req = "SELECT id_operation FROM operations ORDER BY id_operation DESC LIMIT 1";
            $resultat = $this->connection->query($req);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

                $_id_operation = "";
                foreach ($lignes as $ligne) {
                    $_id_operation = stripslashes($ligne['id_operation']);
                }

                $_id_operation = substr($_id_operation, -4);
                $_id_operation++;
            } else {
                $_id_operation = 1;
            }

            $b = "OP";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%04d';

            $req = "SELECT libelle_banque, pays_banque FROM banque WHERE id_banque = '$this->id_banque'";
            $resultat = $this->connection->query($req);

            $libelle_banque = "";
            $pays_banque = "";

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

                foreach ($lignes as $ligne) {
                    $libelle_banque = stripslashes($ligne['libelle_banque']);
                    $pays_banque = stripslashes($ligne['pays_banque']);
                }

                $libelle_banque = substr($libelle_banque, 0, 4);
                $pays_banque = substr($pays_banque, 0, 5);
            }

            $code = $dat . $b . strtoupper($libelle_banque) . strtoupper($pays_banque) . sprintf($format, $_id_operation);

            $this->id_operation = $code;

            $sql = "INSERT INTO operations (id_operation, id_banque, id_type_operation, piece_operation, compte_operation, tag_operation, date_saisie_operation, date_operation, designation_operation, cours_operation, montant_operation, montant_xof_operation, observation_operation) 
                    VALUES ('$this->id_operation','$this->id_banque','$this->id_type_operation','$this->piece_operation','$this->compte_operation','$this->tag_operation','$this->date_saisie_operation','$this->date_operation','$this->designation_operation','$this->cours_operation','$this->montant_operation','$this->montant_xof_operation','$this->observation_operation')";

//            echo $sql;
            if ($result = mysqli_query($this->connection, $sql)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }