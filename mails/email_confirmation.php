<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande - Studio Toon</title>
</head>
<body style="margin: 0; padding: 0; background-color: #fff35c; font-family: 'Space Grotesk', Arial, sans-serif; color: #101010; line-height: 1.5;">

    <div style="max-width: 600px; margin: 30px auto; background: #ffffff; border: 4px solid #101010; box-shadow: 8px 8px 0 #101010; padding: 30px;">
        
        <!-- En-tête de l'e-mail -->
        <div style="background: #00ff9c; border: 3px solid #101010; padding: 20px; text-align: center; margin-bottom: 25px; box-shadow: 4px 4px 0 #101010;">
            <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 1.8rem; color: #101010;">🎬 STUDIO TOON</h1>
            <p style="margin: 5px 0 0; font-weight: 700; font-size: 0.9rem;">« Ici, c'est toi le dessin animé. »</p>
        </div>

        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; color: #ff2ea6; font-size: 1.4rem; margin-top: 0;">🎉 Confirmation de commande</h2>
        <p style="font-weight: 600; font-size: 1rem;">Bonjour <strong><?= htmlspecialchars($client_nom) ?></strong>,</p>
        <p style="font-weight: 500;">Merci pour ton achat ! Ton paiement a bien été validé. Voici le récapitulatif de ta commande pour le parc et tes e-billets d'accès.</p>

        <!-- Récapitulatif global -->
        <div style="background: #00d9ff; border: 3px solid #101010; padding: 15px; margin: 20px 0; font-weight: 700;">
            <p style="margin: 0 0 5px;">📅 Date de visite : <span style="background: #fff; padding: 2px 6px; border: 2px solid #101010;"><?= date('d/m/Y', strtotime($date_visite)) ?></span></p>
            <p style="margin: 0; font-size: 1.1rem;">💰 Total payé : <span style="color: #ff2ea6;"><?= number_format($prix_total, 2, ',', ' ') ?> €</span> (Commande n°<?= $reservation_id ?>)</p>
        </div>

        <h3 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin-top: 30px; border-bottom: 3px solid #101010; padding-bottom: 5px;">🎟️ Vos E-Billets Uniques</h3>
        <p style="font-size: 0.9rem; font-weight: 600; color: #7c3aff;">Chaque billet ci-dessous possède un code unique et un QR code infalsifiable à présenter aux tourniquets du parc.</p>

        <!-- Boucle sur les e-billets -->
        <?php foreach ($e_billets as $billet): ?>
        <div style="background: #fff35c; border: 3px solid #101010; box-shadow: 5px 5px 0 #101010; padding: 20px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed #101010; padding-bottom: 10px; margin-bottom: 12px;">
                <div>
                    <span style="font-family: 'Archivo Black', Impact, sans-serif; font-size: 1.1rem; color: #ff6b1a; display: block;"><?= htmlspecialchars($billet['libelle']) ?></span>
                    <span style="font-size: 0.8rem; font-weight: 700;">Billet n° <?= $billet['numero_billet'] ?></span>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 800; background: #101010; color: #fff; padding: 3px 6px;">Code Unique</span>
                    <div style="font-family: monospace; font-weight: 900; font-size: 1rem; color: #7c3aff; margin-top: 3px;"><?= $billet['code_unique'] ?></div>
                </div>
            </div>

            <div style="text-align: center; background: #fff; border: 2px solid #101010; padding: 10px; display: inline-block; width: 100%; box-sizing: border-box;">
                <!-- Génération dynamique du QR code via API publique encodant le code unique du billet -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=<?= urlencode($billet['code_unique']) ?>" alt="QR Code Billet" style="width: 130px; height: 130px; display: block; margin: 0 auto;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #555; display: block; margin-top: 5px;">Scannez-moi à l'entrée</span>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Pied de page du mail -->
        <div style="text-align: center; margin-top: 30px; font-size: 0.85rem; font-weight: 700; color: #555; border-top: 3px solid #101010; padding-top: 15px;">
            <p style="margin: 0;">Studio Toon - Parcs et Loisirs Déjantés</p>
            <p style="margin: 5px 0 0;">Conservez cet e-mail précieusement sur votre smartphone le jour de votre visite.</p>
        </div>

    </div>

</body>
</html>