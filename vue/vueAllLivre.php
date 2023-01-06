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
            $htmlLivre .= "<li><p><b>" . $l->getId() . "</b></p>";
            $htmlLivre .= "<p>" . $l->getTitre() . "</p>";
            $htmlLivre .= "<p>" . $l->getEdition() . "</p>";
            $htmlLivre .= "<p>" . $l->getInformation() . "</p>";
            $htmlLivre .= "<p>" . $l->getAuteur() . "</p></li>";
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
        echo '<ul class=\'font-italic\'>';
        echo $this->liste();
        echo '</ul>';
        echo '</div>';
        echo '</div>';

        include "footer.html";
    }
}