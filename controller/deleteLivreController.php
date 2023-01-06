<?php

class deleteLivreController
{
    public function __construct()
    {
        session_start();
        error_reporting(1);
        require_once "controller/Controller.php";
        require_once "vue/vueDeleteLivre.php";


        if (Controller::auth()) {
            $v = new vueDeleteLivre();
            $v->affiche();
        } else {
            Constantes::redirect('index.php?error=login');
        }
    }
}
