<?php
require_once "vue/Vue.php";
class vueDeleteLivre extends Vue {
	function affiche(){
                include "headerLivreDelete.html";
                include "menu.php";

                echo '<style>#deleteLivre { background-color: red; }</style>';
                echo '<div class="covered-img">';
                echo ' <div class="container">';
                echo'<div id="messagee"></div>';
                echo'<div id="msg"></div>';
                echo "<form method='post' class='verif_form_livre' action='index.php?operation=delete&action=validLivre&id=".$_SESSION['token']."' required>";
                echo " <div class='form-group'>";
                echo " <div class='form-row'>";
                echo "<label class='col-md-3' for='nom'>Id</label>";
                echo '<input id="id" type="text" class="form-control" name="id" placeholder="Entrer l\'Id" required>';
                echo '</div>';
                echo '</div>';
                echo '<button class="btn btn-primary" type="submit">Supprimer</button>';
                echo "</form>";
                echo '</div>';
                echo '</div>';

                include "footer.html";
        }	
	
}