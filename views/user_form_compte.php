<?php 
/**
 * Vue : Formulaire d'inscription
 * Permet aux nouveaux utilisateurs de créer un compte.
 */

$pageTitle = "Inscription - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/user_form_compte.css">
<div id="page-inscription" class="page-entete">

    <h1>Créer un compte</h1>
    <p class="page-entete__intro">Rejoins Studio Toon et profite de toutes les fonctionnalités du parc !</p>

    <!-- Affichage du message d'erreur si la validation échoue -->
    <?php if (isset($erreur)): ?>
        <p id="message-erreur" class="alerte-erreur">
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <form id="formulaire-inscription" action="index.php?action=create_account_post" method="POST">
        
        <div class="champ-formulaire">
            <label for="champ-nom">Nom :</label>
            <input type="text" id="champ-nom" name="nom" required autocomplete="name">
        </div>

        <div class="champ-formulaire">
            <label for="champ-email">Email :</label>
            <input type="email" id="champ-email" name="email" required autocomplete="email">
        </div>

        <div class="champ-formulaire">
            <label for="champ-mot-de-passe">Mot de passe :</label>
            <input type="password" id="champ-mot-de-passe" name="password" required autocomplete="new-password">
        </div>
        
        <button type="submit" id="bouton-inscription" class="bouton">S'inscrire →</button>

    </form>
    
    <!-- Lien vers la page de connexion -->
    <p id="lien-connexion">
        Déjà un compte ? <a href="index.php?action=login" class="lien-rose">Connecte-toi ici !</a>
    </p>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>