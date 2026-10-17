<?php 
/**
 * Vue : Confirmation de création de compte
 * Message de succès affiché immédiatement après l'inscription d'un nouvel utilisateur.
 */

$pageTitle = "Compte Créé - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/register_success.css">
<!-- Carte de confirmation de création de compte -->
<div id="carte-compte-cree" class="fiche-veille">
    <h2 class="titre-succes">🎉 COMPTE CRÉÉ !</h2>
    
    <p class="texte-succes">
        Ton inscription a bien été validée. Bienvenue dans l'univers déjanté de Studio Toon ! « Ici, c'est toi le dessin animé. »
    </p>

    <div class="actions-succes">
        <a href="index.php?action=login" id="bouton-vers-connexion" class="bouton">
            Se connecter →
        </a>
        <a href="index.php?action=accueil" id="bouton-vers-accueil" class="bouton">
            Retour à l'accueil
        </a>
    </div>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>