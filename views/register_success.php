<?php 
$pageTitle = "Compte Créé - Studio Toon";
ob_start(); 
?>
<div class="fiche-veille" style="max-width: 600px; margin: 3rem auto; background: var(--vert); text-align: center; padding: 3rem 2rem; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); transform: rotate(-0.5deg);">
    <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--noir); -webkit-text-stroke: 1px var(--noir);">🎉 COMPTE CRÉÉ !</h2>
    <p style="font-size: 1.1rem; font-weight: 700; color: var(--noir); margin-bottom: 2rem;">
        Ton inscription a bien été validée. Bienvenue dans l'univers déjanté de Studio Toon ! « Ici, c'est toi le dessin animé. »
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="index.php?action=login" class="bouton" style="background: var(--noir); color: #fff; margin-top: 0;">Se connecter →</a>
        <a href="index.php?action=accueil" class="bouton" style="background: var(--cyan); color: var(--noir); margin-top: 0;">Retour à l'accueil</a>
    </div>
</div>
<?php 
$content = ob_get_clean();
require 'views/base.php';
?>