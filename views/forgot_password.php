<?php 
/**
 * Vue : Mot de passe oublié
 * Permet à un utilisateur de saisir son adresse e-mail pour recevoir un lien de réinitialisation.
 */

$pageTitle = "Mot de passe oublié - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/forgot_password.css">
<!-- En-tête de la page -->
<div id="entete-mdp-oublie" class="page-entete">
    <h1>🔑 Mot de passe oublié ?</h1>
    <p class="page-entete__intro">Pas de panique ! Saisis ton e-mail et on t'envoie un lien de réinitialisation.</p>
</div>

<!-- Conteneur principal -->
<div id="conteneur-mdp-oublie">

    <!-- Affichage du message de succès et du lien de test (mode dev) -->
    <?php if (isset($message_succes)): ?>
        <div id="alerte-succes-mdp" class="alerte-succes">
            ✅ <?= htmlspecialchars($message_succes) ?>
            <br><br>
            <a href="mails/email_reset_password.php?token=<?= urlencode($token_genere ?? '') ?>" target="_blank" class="bouton-voir-mail">
                📩 Voir le mail généré !
            </a>
        </div>
    <?php endif; ?>

    <!-- Affichage du message d'erreur -->
    <?php if (isset($message_erreur)): ?>
        <div id="alerte-erreur-mdp" class="alerte-erreur">
            ❌ <?= htmlspecialchars($message_erreur) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'envoi de la demande -->
    <form id="formulaire-mdp-oublie" action="index.php?action=traiter_mdp_oublie" method="POST">
        <div class="champ-formulaire">
            <label for="champ-email-oublie">
                Adresse e-mail de ton compte :
            </label>
            <input 
                type="email" 
                id="champ-email-oublie" 
                name="email" 
                placeholder="charlie@studiotoon.fr" 
                required 
                autofocus
                autocomplete="email"
            >
        </div>

        <button type="submit" id="bouton-envoyer-mdp" class="bouton">
            Envoyer le lien de secours →
        </button>
    </form>

    <hr class="separateur-tiret">

    <!-- Lien de retour vers la page de connexion -->
    <div class="bloc-lien-connexion">
        <a href="index.php?action=login" class="lien-retour-connexion">
            ← Retour à la page de connexion
        </a>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>