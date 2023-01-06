<?php

class modifLivreController
{
    public function __construct()
    {
        session_start();
        error_reporting(1);
        require_once "controller/Controller.php";
        require_once "vue/vueModifLivre.php";
        require_once "PDO/LivreDB.php";
        require_once "metier/Livre.php";

        if (Controller::auth()) {
            $v = new vueModifLivre();
            $colName = $_GET['colName'];
            $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
            $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            $pdodb = new PDO($strConnection, Constantes::USER, Constantes::PASSWORD, $arrExtraParam); //Ligne 3; Instancie la connexion
            $pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $ldb = new LivreDB($pdodb);
            $livreId = $_GET['livreId'];
            if (empty($livreId))
            {
                echo "Erreur";
                return ;
            }
            $ldb = new LivreDB($pdodb);
            $livre = null;
            try { $livre = $ldb->selectLivre($livreId); }
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
            if (empty($colName) ||
                ($colName != "titre" && $colName != "edition" && $colName != "information" && $colName != "auteur"))
            {
                echo "Erreur";
                Constantes::redirect('index.php?error=login');
            }
            else
            {
                switch ($colName)
                {
                    case "titre": $colValue = $livre->getTitre(); break;
                    case "edition": $colValue = $livre->getEdition(); break;
                    case "information": $colValue = $livre->getInformation(); break;
                    case "auteur": $colValue = $livre->getAuteur(); break;
                    default: echo "Erreur"; return ;
                }
                $v->affiche($livreId, $colName, $colValue);
            }
        } else {
            Constantes::redirect('index.php?error=login');
        }
    }
}
