<?php
require_once "vue/Vue.php";
class vueModifLivre extends Vue {
	function affiche($idLivre, $colName, $colValue){
                include "headerLivre.html";
                include "menu.php";

                echo '<style>#ajoutLivre { background-color: red; }</style>';
                echo '<div class="covered-img">';
                echo ' <div class="container">';
                echo'<div id="messagee"></div>';
                echo'<div id="msg"></div>';
                echo "<form method='post' class='verif_form_livre' action='index.php?operation=update&action=validLivre&colName=" . $colName . "&id=".$_SESSION['token']. "&livreId=" . $idLivre . "' required>";
                echo " <div class='form-group'>";
                echo " <div class='form-row'>";
                echo "<label class='col-md-3' for='nom'><b style='color: red;'>" . $colName . "<b></label>";
                echo '<input id="nom" type="text" class="form-control" name="value" placeholder="' . $colValue . '" required>';
                echo '</div>';
                echo '</div>';

                echo '<button class="btn btn-primary" type="submit">Modifier</button>';
                echo "</form>"; 

                echo '</div>';
                echo '</div>';

                include "footer.html";
        }
}