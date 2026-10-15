<?php
$pageTitle = "Connexion - Studio Toon";
ob_start();
?>
    <div class="page-entete" style="max-width: 500px; margin: 2rem auto;">
        <h1>Connexion</h1>
        <p class="page-entete__intro">Entre tes identifiants pour accéder à ton espace.</p>

        <?php if (isset($erreur)): ?>
            <p style="color: var(--rose); font-weight: bold; background: #fff; padding: 0.5rem; border: 2px solid var(--noir);"><?= $erreur ?></p>
        <?php endif; ?>

        <form action="index.php?action=login_post" method="POST" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label style="display: block; font-weight: 700; margin-bottom: 0.3rem;">Email :</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.7rem; border: 3px solid var(--noir); font-family: var(--police-texte);">
            </div>
            <div>
                <label style="display: block; font-weight: 700; margin-bottom: 0.3rem;">Mot de passe :</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.7rem; border: 3px solid var(--noir); font-family: var(--police-texte);">
            </div>
            <button type="submit" class="bouton" style="background: var(--vert); color: var(--noir); cursor: pointer; border: 3px solid var(--noir); box-shadow: 4px 4px 0 var(--noir);">Se connecter →</button>
        </form>
    </div>
<?php
$content = ob_get_clean();
require 'views/base.php';
?>