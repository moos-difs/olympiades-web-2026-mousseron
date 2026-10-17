<?php 
/**
 * Vue : Réinitialisation du mot de passe
 * Permet à un utilisateur de saisir son nouveau mot de passe via un token de réinitialisation.
 */

$pageTitle = "Nouveau mot de passe - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/reset_password_form.css">
<!-- En-tête de la page -->
<div id="entete-reset-pass" class="page-entete">
    <h1>🔐 Nouveau mot de passe</h1>
    <p class="page-entete__intro">Choisis un nouveau mot de passe pour ton compte Studio Toon.</p>
</div>

<!-- Conteneur principal du formulaire -->
<div id="conteneur-reset-pass">

    <!-- Message d'erreur en cas de problème de validation -->
    <?php if (isset($message_erreur)): ?>
        <div id="message-erreur-reset" class="alerte-erreur">
            ❌ <?= htmlspecialchars($message_erreur) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire si l'utilisateur/token est valide -->
    <?php if (isset($user) && $user): ?>
        <form id="formulaire-reset-pass" action="index.php?action=traiter_reset_password" method="POST">
            
            <!-- Jeton d'authentification transmis en champ caché -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="champ-formulaire">
                <label for="champ-nouveau-pass">
                    Nouveau mot de passe :
                </label>
                <input 
                    type="password" 
                    id="champ-nouveau-pass" 
                    name="nouveau_password" 
                    required 
                    placeholder="••••••••" 
                    autocomplete="new-password"
                >
            </div>

            <div class="champ-formulaire">
                <label for="champ-confirmer-pass">
                    Confirmer le mot de passe :
                </label>
                <input 
                    type="password" 
                    id="champ-confirmer-pass" 
                    name="confirmation_password" 
                    required 
                    placeholder="••••••••" 
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" id="bouton-valider-reset" class="bouton">
                Mettre à jour →
            </button>
        </form>
    <?php else: ?>
        <!-- Lien de secours si le token est invalide ou expiré -->
        <div class="bloc-lien-retour">
            <a href="index.php?action=forgot_password" class="lien-retour">
                ← Demander un nouveau lien de réinitialisation
            </a>
        </div>
    <?php endif; ?>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>