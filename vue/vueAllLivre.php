<?php
include_once "vue/Vue.php";
include_once "PDO/LivreDB.php";

class vueAllLivre extends Vue {

    function liste()
    {
        session_start();
        error_reporting(1);

        $token = $_SESSION['token'];
        $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
        $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $pdodb = new PDO($strConnection, Constantes::USER, Constantes::PASSWORD, $arrExtraParam); //Ligne 3; Instancie la connexion
        $pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ldb = new LivreDB($pdodb);
        $livre = $ldb->selectAll();
        $total = count($livre);
        $i = 0;
        while ($i < $total)
        {
            $l = $ldb->convertPdoLiv($livre[$i]);
            $urlModif = "index.php?action=modifLivre&livreId=" . $l->getId() . "&id=" . $_SESSION['token'];
            $htmlLivre .= "<tr><th>Id: <b>" . $l->getId() . "</b></th>";
            $htmlLivre .= "<th>Titre: <a href='" . $urlModif . "&colName=titre" ."'>" . $l->getTitre() . "</a></th>"; //
            $htmlLivre .= "<th>Edition: <a href='" . $urlModif . "&colName=edition" ."'>" . $l->getEdition() . "</a></th>";
            $htmlLivre .= "<th>Info: <a href='" . $urlModif . "&colName=information" ."'>" . $l->getInformation() . "</a></th>";
            $htmlLivre .= "<th>Auteur: <a href='" . $urlModif . "&colName=auteur" ."'>" . $l->getAuteur() . "</a></th></tr>";
            $i++;
        }
        return ($htmlLivre);
    }

    function affiche(){
        include "header.html";
        include "menu.php";
        echo '<style>#allLivre { background-color: red; }</style>';
        echo '<div class="covered-img">';
        echo ' <div class="container">';
        echo '<h1 class="lead">Liste des livres</h1>';
        echo '<style>table { margin: 10px; margin-bottom: 100px; } tr { display: inline-block; margin: 15px; background-color: grey; padding: 10px; } th { display: block; } th > a { color: green; }</style>';
        echo '<table class=\'font-italic\'><tbody>';
        echo $this->liste();
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';

        include "footer.html";
    }
}