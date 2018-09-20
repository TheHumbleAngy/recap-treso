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
        protected $monnaie_banque;

        protected $connection;
    }

    class banque extends class_banque {

        public function __construct() {
            $this->connection = mysqli_connect('localhost', 'root', '', 'recap_treso');
            if ($this->connection->connect_error)
                die($this->connection->connect_error);
        }

        function setData() {
            $this->libelle_banque = htmlspecialchars($_POST['libelle_banque'], ENT_QUOTES);
            $this->pays_banque = htmlspecialchars($_POST['pays_banque'], ENT_QUOTES);
            $this->monnaie_banque = htmlspecialchars($_POST['monnaie_banque'], ENT_QUOTES);

            return TRUE;
        }

        function getData() {
            $_id_banque = $this->id_banque;
            $_libelle_banque = $this->libelle_banque;
            $_pays_banque = $this->pays_banque;
            $_monnaie_banque = $this->monnaie_banque;

            $arr_banque = array($_id_banque, $_libelle_banque, $_pays_banque, $_monnaie_banque);

            return $arr_banque;
        }

        function saveData() {
            $req = "SELECT id_banque FROM banque ORDER BY id_banque DESC LIMIT 1";
            $resultat = $this->connection->query($req);

            if ($resultat->num_rows > 0) {
                $lignes = $resultat->fetch_all(MYSQLI_ASSOC);

                $_id_banque = "";
                foreach ($lignes as $ligne) {
                    $_id_banque = stripslashes($ligne['id_banque']);
                }

                $_id_banque = substr($_id_banque, -2);
                $_id_banque++;
            }
            else {
                $_id_banque = 1;
            }

            $b = "BANQ";
            $dat = date("Y");
            $dat = substr($dat, -2);
            $format = '%02d';
            $code = $dat . "" . $b . "" . sprintf($format, $_id_banque);

            $this->id_banque = $code;

            $sql = "INSERT INTO banque(id_banque, libelle_banque, pays_banque, monnaie_banque)
                    VALUES ('$this->id_banque','$this->libelle_banque','$this->pays_banque','$this->monnaie_banque')";

            if ($result = mysqli_query($this->connection, $sql))
                return TRUE;
            else
                return FALSE;
        }

        function updateData($id) {
            $sql = "UPDATE banque SET 
                      libelle_banque = '" . $this->libelle_banque . "',
                      pays_banque = '" . $this->pays_banque . "',
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