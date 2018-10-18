<?php
    /**
     * Created by PhpStorm.
     * User: angem
     * Date: 20-Sep-18
     * Time: 9:30 AM
     */

    abstract class class_banques {
        protected $id_banque;
        protected $libelle_banque;
        protected $entite_banque;
        protected $abbr_banque;
        /*protected $monnaie_banque;*/

        protected $connection;
    }

    class banques extends class_banques {

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
         * @return mixed
         */
        public function getAbbrBanque() {
            return $this->abbr_banque;
        }
    
        /**
         * @param mixed $abbr_banque
         */
        public function setAbbrBanque() {
            // Creation abbr banque
            $arr = str_split($this->getLibelleBanque());
            $i = 3;
            /*$abbr = array($arr[0], $arr[1], $arr[2], $arr[$i]);
            $n = count($arr);
            
            do {
                // vérification de l'existence de l'abbreviation
                $temp = $arr[0] . $arr[1] . $arr[$i];
                $sql = "SELECT abbr_banque FROM banques WHERE abbr_banque = '$temp'";
                $resultat = $this->connection->query($sql);
                if ($resultat->num_rows > 0)
                    $i++;
                else
                    break;
            }
            while ($i <= $n);*/
            $temp = $arr[0] . $arr[1] . $arr[2] . $arr[$i];
            $this->abbr_banque = $temp;
        }

        function setData($libelle_banque, $entite_banque) {
            try {
                $this->setLibelleBanque(stripcslashes($libelle_banque));
                $this->setEntiteBanque(stripcslashes($entite_banque));
                $this->setAbbrBanque();

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        function getData() {
            try {
//                return $arr_banque = array($this->getIdBanque(), $this->getLibelleBanque(), $this->getEntiteBanque(), $this->getPaysBanque(), $this->getMonnaieBanque());
                return $arr_banque = array($this->getIdBanque(), $this->getLibelleBanque(), $this->getEntiteBanque(), $this->getAbbrBanque());
            } catch (Exception $e) {
                return false;
            }
        }

        function saveData() {
            $req = "SELECT id_banque FROM banques ORDER BY id_banque DESC LIMIT 1";
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

            $sql = "INSERT INTO banques(id_banque, 
                                        libelle_banque, 
                                        entite_banque,
                                        abbr_banque)
                    VALUES ('$this->id_banque',
                            '$this->libelle_banque',
                            '$this->entite_banque',
                            '$this->abbr_banque')";

//            echo $sql;
            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function updateData($id) {
            $sql = "UPDATE banques SET 
                      libelle_banque = '" . $this->libelle_banque . "',
                      entite_banque = '" . $this->entite_banque . "',
                    WHERE id_banque = '" . $id . "'";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function deleteData($id) {
            $sql = "DELETE FROM banques WHERE id_banque = '" . $id . "'";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }
    }