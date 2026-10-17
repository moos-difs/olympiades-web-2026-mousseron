<?php 
/**
 * Vue : Page d'Erreur (Oups)
 * Page affichée lorsqu'une erreur survient (page introuvable, action invalide, etc.).
 */

$pageTitle = "Oups - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/error.css">
<!-- Carte principale d'erreur -->
<div id="carte-erreur" class="fiche-veille">
    <h2 class="titre-erreur">OUPSSSSS !</h2>
    
    <p class="texte-erreur">
        <?= isset($message_erreur) ? htmlspecialchars($message_erreur) : "Une erreur est survenue." ?>
    </p>

    <div class="actions-erreur">
        <a href="index.php?action=attractions" id="bouton-vers-attractions" class="bouton">
            Voir les attractions disponibles →
        </a>
    </div>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>