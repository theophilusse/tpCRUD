<?php

include("metier/Livre.php");
include("PDO/LivreDB.php");

class validLivreController
{
    private $pdodb;

    public function __construct()
    {
        session_start();
        //error_reporting(1);

        $token = $_SESSION['token'];
        $operation = isset($_GET['operation']) ? strval($_GET['operation']): '';
        if ($operation != "insert" && $operation != "delete" && $operation != "update")
        {
            echo "Erreur";
            return ;
        }
        //parametre de connexion à la bae de donnée
        $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
        $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $pdodb = new PDO($strConnection, Constantes::USER, Constantes::PASSWORD, $arrExtraParam); //Ligne 3; Instancie la connexion
        $pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($operation == "insert")
        {
            $livreTitre = $_POST['nom'];
            $livreEditions = $_POST['edition'];
            $livreInfo = $_POST['info'];
            $livreAuteur = $_POST['auteur'];
            $livre = new Livre($livreTitre, $livreEditions, $livreInfo, $livreAuteur);
            $ldb = new LivreDB($pdodb);
            $ldb->ajout($livre);
            echo "successSsSsS" . $token;
        }
        else if ($operation == "delete")
        {
            $livreId = $_POST['id'];
            $ldb = new LivreDB($pdodb);
            try { $ldb->selectLivre($livreId); }
            catch (Exception $e)
            {
                echo "Erreur";
                return ;
            }
            $ldb->suppression($livreId);
            echo "Suppression reussie";
        }
        else if ($operation == "update")
        {
            try {
                $idLivre = $_GET['livreId'];
                $newValue = $_POST['value'];
                $colName = $_GET['colName'];
            } catch (Exception $e)
            {
                echo "Erreur";
                return ;
            }
            if (empty($newValue))
                $newValue = '';
            if (empty($colName) || empty($idLivre) ||
                ($colName != "titre" && $colName != "edition" && $colName != "auteur" && $colName != "information"))
            {
                echo "Erreur";
                return ;
            }    
            $ldb = new LivreDB($pdodb);
            $livre = null;
            try { $livre = $ldb->selectLivre($idLivre); }
            catch (Exception $e)
            {
                echo "Erreur";
                return ;
            }
            if ($livre == null)
            {
                echo "Erreur";
                return ;
            }
            switch ($colName)
            {
                case "titre": $livre->setTitre($newValue); break;
                case "edition": $livre->setEdition($newValue); break;
                case "auteur": $livre->setAuteur($newValue); break;
                case "information": $livre->setInformation($newValue); break;
                default: echo "Erreur"; return ;
            }
            $ldb->suppression($idLivre);
            $ldb->ajout($livre);
            echo "Modification reussie";
        }
    }
}
