<?php 
$pageTitle = "Inscription - Studio Toon";
ob_start(); 
?>
<div class="page-entete" style="max-width: 500px; margin: 2rem auto;">
    <h1>Créer un compte</h1>
    <p class="page-entete__intro">Rejoins Studio Toon et profite de toutes les fonctionnalités du parc !</p>

    <?php if (isset($erreur)): ?>
        <p style="color: var(--rose); font-weight: bold; background: #fff; padding: 0.8rem; border: 3px solid var(--noir); margin-bottom: 1rem; box-shadow: 4px 4px 0 var(--noir);"><?= $erreur ?></p>
    <?php endif; ?>

    <form action="index.php?action=create_account_post" method="POST" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 1.2rem; background: var(--fond-carte); padding: 2rem; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir);">
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Nom :</label>
            <input type="text" name="nom" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); background: #fff;">
        </div>
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Email :</label>
            <input type="email" name="email" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); background: #fff;">
        </div>
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Mot de passe :</label>
            <input type="password" name="password" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); background: #fff;">
        </div>
        
        <button type="submit" class="bouton" style="background: var(--vert); color: var(--noir); cursor: pointer; border: 3px solid var(--noir); box-shadow: 4px 4px 0 var(--noir); text-align: center; margin-top: 0.5rem;">S'inscrire →</button>
    </form>
    
    <p style="text-align: center; margin-top: 1.5rem; font-weight: 600;">
        Déjà un compte ? <a href="index.php?action=login" style="color: var(--rose);">Connecte-toi ici !</a>
    </p>
</div>
<?php 
$content = ob_get_clean();
require 'views/base.php';
?>