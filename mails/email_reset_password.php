<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération des données dynamiques stockées en session lors de la soumission du formulaire
$user_prenom = $_SESSION['mail_preview']['user_prenom'] ?? 'Tooner';
$reset_link = $_SESSION['mail_preview']['reset_link'] ?? '#';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de ton mot de passe - Studio Toon</title>
</head>
<body style="margin: 0; padding: 0; background-color: #fff35c; font-family: 'Space Grotesk', Arial, sans-serif; color: #101010; line-height: 1.5;">

    <div style="max-width: 600px; margin: 30px auto; background: #ffffff; border: 4px solid #101010; box-shadow: 8px 8px 0 #101010; padding: 30px;">
        
        <!-- En-tête -->
        <div style="background: #ff2ea6; border: 3px solid #101010; padding: 20px; text-align: center; margin-bottom: 25px; box-shadow: 4px 4px 0 #101010;">
            <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 1.8rem; color: #ffffff;">🔑 STUDIO TOON</h1>
            <p style="margin: 5px 0 0; font-weight: 700; font-size: 0.9rem; color: #101010;">« Ici, c'est toi le dessin animé. »</p>
        </div>

        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; color: #101010; font-size: 1.4rem; margin-top: 0;">Oups ! Un trou de mémoire ? 🤯</h2>
        
        <p style="font-weight: 600; font-size: 1rem;">Bonjour <strong><?= htmlspecialchars($user_prenom) ?></strong>,</p>
        
        <p style="font-weight: 500;">Nous avons reçu une demande de réinitialisation de mot de passe pour ton compte <strong>Studio Toon</strong>.</p>
        <p style="font-weight: 500;">Pas de panique ! Clique simplement sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>

        <!-- Bouton d'action principal avec le VRAI lien fonctionnel -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="<?= htmlspecialchars($reset_link) ?>" 
               style="display: inline-block; background: #00ff9c; color: #101010; font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; text-decoration: none; font-size: 1.1rem; padding: 15px 25px; border: 3px solid #101010; box-shadow: 5px 5px 0 #101010; font-weight: 900;">
               🔄 Réinitialiser mon mot de passe
            </a>
        </div>

        <!-- Alerte d'expiration & Sécurité -->
        <div style="background: #00d9ff; border: 3px solid #101010; padding: 15px; margin: 25px 0; font-weight: 700; font-size: 0.9rem;">
            ⏳ <strong>Attention :</strong> Ce lien est à usage unique et expirera dans <strong>15 minutes</strong>.
        </div>

        <div style="background: #f4f4f4; border: 2px dashed #101010; padding: 15px; margin-top: 20px; font-size: 0.85rem;">
            <p style="margin: 0 0 5px; font-weight: 800; color: #ff6b1a;">⚠️ Tu n'as pas demandé cette réinitialisation ?</p>
            <p style="margin: 0; font-weight: 500; color: #333;">Si tu n'es pas à l'origine de cette demande, tu peux ignorer cet e-mail en toute sécurité. Ton mot de passe actuel restera inchangé.</p>
        </div>

        <!-- Lien de secours en texte brut -->
        <p style="font-size: 0.75rem; color: #666; margin-top: 25px; word-break: break-all;">
            Si le bouton ne fonctionne pas, copie et colle le lien suivant dans ton navigateur :<br>
            <a href="<?= htmlspecialchars($reset_link) ?>" style="color: #7c3aff; font-weight: 700;"><?= htmlspecialchars($reset_link) ?></a>
        </p>

        <!-- Pied de page -->
        <div style="text-align: center; margin-top: 30px; font-size: 0.85rem; font-weight: 700; color: #555; border-top: 3px solid #101010; padding-top: 15px;">
            <p style="margin: 0;">Studio Toon - Support Technologique & Magique</p>
            <p style="margin: 5px 0 0;">Ceci est un message automatique, merci de ne pas y répondre directement.</p>
        </div>

    </div>

</body>
</html>