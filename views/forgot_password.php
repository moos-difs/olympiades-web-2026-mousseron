<?php 
$pageTitle = "Mot de passe oublié - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 500px; margin: 0 auto 2rem auto; background: var(--violet); color: #fff;">
    <h1 style="color: #fff;">🔑 Mot de passe oublié ?</h1>
    <p class="page-entete__intro" style="color: #fff;">Pas de panique ! Saisis ton e-mail et on t'envoie un lien de réinitialisation.</p>
</div>

<div style="max-width: 500px; margin: 0 auto; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2.5rem;">

    <!-- Affichage des messages de confirmation ou d'erreur -->
    <?php if (isset($message_succes)): ?>
    <div style="background: var(--vert); color: var(--noir); border: 3px solid var(--noir); padding: 1rem; margin-bottom: 1.5rem; font-weight: 800; box-shadow: 4px 4px 0 var(--noir);">
        ✅ <?= htmlspecialchars($message_succes) ?>
        <br><br>
        <a href="mails/email_reset_password.php?token=<?= urlencode($token_genere) ?>" target="_blank" style="background: var(--noir); color: #fff; padding: 0.4rem 0.8rem; text-decoration: none; display: inline-block;">
            📩 Voir le mail généré !
        </a>
    </div>
<?php endif; ?>

    <?php if (isset($message_erreur)): ?>
        <div style="background: var(--rose); color: #fff; border: 3px solid var(--noir); padding: 1rem; margin-bottom: 1.5rem; font-weight: 800; box-shadow: 4px 4px 0 var(--noir);">
            ❌ <?= htmlspecialchars($message_erreur) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'envoi -->
    <form action="index.php?action=traiter_mdp_oublie" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div>
            <label for="email" style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 0.9rem;">
                Adresse e-mail de ton compte :
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="charlie@studiotoon.fr" 
                required 
                autofocus
                style="width: 100%; padding: 0.8rem 1rem; border: 3px solid var(--noir); font-weight: 700; font-size: 1rem; background: #fff; box-sizing: border-box;"
            >
        </div>

        <button 
            type="submit" 
            class="bouton" 
            style="background: var(--cyan); color: var(--noir); width: 100%; padding: 1rem; font-size: 1.1rem; cursor: pointer; text-align: center; border: 3px solid var(--noir); box-shadow: 4px 4px 0 var(--noir); font-weight: 900; text-transform: uppercase;"
        >
            Envoyer le lien de secours →
        </button>
    </form>

    <hr style="border: 0; border-top: 3px dashed var(--noir); margin: 2rem 0;">

    <div style="text-align: center;">
        <a href="index.php?action=login" style="font-weight: 800; color: var(--noir); text-decoration: underline;">
            ← Retour à la page de connexion
        </a>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>