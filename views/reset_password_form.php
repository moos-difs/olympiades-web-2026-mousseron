<?php 
$pageTitle = "Nouveau mot de passe - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 500px; margin: 0 auto 2rem auto; background: var(--vert); color: var(--noir);">
    <h1>🔐 Nouveau mot de passe</h1>
    <p class="page-entete__intro">Choisis un nouveau mot de passe pour ton compte Studio Toon.</p>
</div>

<div style="max-width: 500px; margin: 0 auto; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2.5rem;">

    <?php if (isset($message_erreur)): ?>
        <div style="background: var(--rose); color: #fff; border: 3px solid var(--noir); padding: 1rem; margin-bottom: 1.5rem; font-weight: 800; box-shadow: 4px 4px 0 var(--noir);">
            ❌ <?= htmlspecialchars($message_erreur) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($user) && $user): ?>
    <form action="index.php?action=traiter_reset_password" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- On transmet le token caché pour l'identifier côté serveur -->
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

        <div>
            <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 0.9rem;">
                Nouveau mot de passe :
            </label>
            <input type="password" name="nouveau_password" required placeholder="••••••••" style="width: 100%; padding: 0.8rem 1rem; border: 3px solid var(--noir); font-weight: 700; background: #fff; box-sizing: border-box;">
        </div>

        <div>
            <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 0.9rem;">
                Confirmer le mot de passe :
            </label>
            <input type="password" name="confirmation_password" required placeholder="••••••••" style="width: 100%; padding: 0.8rem 1rem; border: 3px solid var(--noir); font-weight: 700; background: #fff; box-sizing: border-box;">
        </div>

        <button type="submit" class="bouton" style="background: var(--cyan); color: var(--noir); width: 100%; padding: 1rem; font-size: 1.1rem; cursor: pointer; text-align: center; border: 3px solid var(--noir); box-shadow: 4px 4px 0 var(--noir); font-weight: 900; text-transform: uppercase;">
            Mettre à jour →
        </button>
    </form>
    <?php else: ?>
        <div style="text-align: center;">
            <a href="index.php?action=forgot_password" style="font-weight: 800; color: var(--noir); text-decoration: underline;">
                ← Demander un nouveau lien de réinitialisation
            </a>
        </div>
    <?php endif; ?>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>