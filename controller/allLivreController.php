<?php

class allLivreController
{
    public function __construct()
    {
        session_start();
        error_reporting(1);
        require_once "controller/Controller.php";
        require_once "vue/vueAllLivre.php";


        if (Controller::auth()) {
            $v = new vueAllLivre();
            $v->affiche();
        } else {
            Constantes::redirect('index.php?error=login');
        }
    }
}

?>