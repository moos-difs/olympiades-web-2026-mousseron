<?php 
/**
 * Vue : Connexion
 * Permet aux utilisateurs existants de s'identifier pour accéder à leur espace personnel.
 */

$pageTitle = "Connexion - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/login.css">
<!-- Conteneur principal de la page de connexion -->
<div id="page-connexion" class="page-entete">
    <h1>Connexion</h1>
    <p class="page-entete__intro">Entre tes identifiants pour accéder à ton espace.</p>

    <!-- Affichage du message d'erreur en cas d'échec d'authentification -->
    <?php if (isset($erreur)): ?>
        <p id="message-erreur-connexion" class="alerte-erreur">
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php endif; ?>

    <!-- Formulaire de connexion -->
    <form id="formulaire-connexion" action="index.php?action=login_post" method="POST">
        
        <div class="champ-formulaire">
            <label for="champ-email-connexion">Email :</label>
            <input 
                type="email" 
                id="champ-email-connexion" 
                name="email" 
                required 
                autocomplete="email"
            >
        </div>

        <div class="champ-formulaire">
            <label for="champ-password-connexion">Mot de passe :</label>
            <input 
                type="password" 
                id="champ-password-connexion" 
                name="password" 
                required 
                autocomplete="current-password"
            >
            <p class="page-entete__intro texte-aide-mdp">
                Trou de mémoire ? 
                <a href="index.php?action=reset_pass" class="lien-rose">Mot de passe oublié</a>
            </p>
        </div>

        <button type="submit" id="bouton-connexion" class="bouton">
            Se connecter →
        </button>

    </form>

    <!-- Lien vers la création de compte -->
    <p class="page-entete__intro texte-aide-inscription">
        Pas de compte ? 
        <a href="index.php?action=create_account" class="lien-rose">Crée-en un !</a>
    </p>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>