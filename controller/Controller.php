<?php

include_once"vue/vueAuthentification.php";

class Controller
{
    public function __construct()
    {
        // Active tout les warning. Utile en phase de développement
        // En phase de production, remplacer E_ALL par 0
        error_reporting(1);

        //appel de la vue authentification

        $v = new vueAuthentification();
        $v->affiche();
    }

    public static function auth()
    {
        if (!isset($_SESSION['token']) || !isset($_SESSION['token_time']) ||
            !isset($_GET['id']) || $_SESSION['token'] != $_GET['id'])
            return false;
        $timestamp_ancien = time() - (30 * 60); // On stocke le timestamp il y a 30 minutes
        if ($_SESSION['token_time'] < $timestamp_ancien) // Si le jeton n'est pas expiré
            return false;
        setcookie("Vannes", $_SESSION['token'], time() + 1800); //insertion cookies du token
        return true;
    }
}
