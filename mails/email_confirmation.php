<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande - Studio Toon</title>
    <style>
        /* CSS global pour l'écran */
        body {
            margin: 0;
            padding: 0;
            background-color: #fff35c;
            font-family: 'Space Grotesk', Arial, sans-serif;
            color: #101010;
            line-height: 1.4;
        }

        .no-print {
            max-width: 650px;
            margin: 20px auto 0 auto;
            text-align: right;
        }

        .bouton-pdf {
            background-color: #ff2ea6;
            color: #ffffff;
            font-family: 'Space Grotesk', Arial, sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            border: 3px solid #101010;
            box-shadow: 4px 4px 0 #101010;
            padding: 10px 18px;
            cursor: pointer;
            display: inline-block;
        }

        .email-container {
            max-width: 650px;
            margin: 15px auto 30px auto;
            background: #ffffff;
            border: 4px solid #101010;
            box-shadow: 8px 8px 0 #101010;
            padding: 25px;
            box-sizing: border-box;
        }

        /* Cartes incoupables */
        .carte-billet {
            background: #fff35c;
            border: 3px solid #101010;
            box-shadow: 5px 5px 0 #101010;
            padding: 15px;
            margin-bottom: 20px;
            /* Empêche le saut de page à l'intérieur d'un billet */
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* RÈGLES DE CONVERSION & IMPRESSION PDF */
        @media print {
            @page {
                size: A4;
                margin: 10mm 10mm 10mm 10mm;
            }

            body {
                background-color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .email-container {
                box-shadow: none !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                border: 2px solid #101010 !important;
                padding: 15px !important;
            }

            .carte-billet {
                box-shadow: none !important;
                border: 2px solid #101010 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bouton pour déclencher le téléchargement PDF -->
    <div class="no-print">
        <button onclick="window.print()" class="bouton-pdf">
            📄 Télécharger / Imprimer en PDF
        </button>
    </div>

    <div class="email-container">
        
        <!-- En-tête -->
        <div style="background: #00ff9c; border: 3px solid #101010; padding: 15px; text-align: center; margin-bottom: 20px; box-shadow: 4px 4px 0 #101010;">
            <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 1.6rem; color: #101010;">🎬 STUDIO TOON</h1>
            <p style="margin: 4px 0 0; font-weight: 700; font-size: 0.85rem;">« Ici, c'est toi le dessin animé. »</p>
        </div>

        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; color: #ff2ea6; font-size: 1.3rem; margin-top: 0;">🎉 Confirmation de commande</h2>
        <p style="font-weight: 600; font-size: 0.95rem; margin: 5px 0;">Bonjour <strong><?= htmlspecialchars($client_nom) ?></strong>,</p>
        <p style="font-weight: 500; font-size: 0.9rem; margin-bottom: 15px;">Merci pour ton achat ! Ton paiement a bien été validé. Voici le récapitulatif de ta commande pour le parc et tes e-billets d'accès.</p>

        <!-- Récapitulatif global -->
        <div style="background: #00d9ff; border: 3px solid #101010; padding: 12px; margin: 15px 0; font-weight: 700;">
            <p style="margin: 0 0 5px; font-size: 0.9rem;">📅 Date de visite : <span style="background: #fff; padding: 2px 6px; border: 2px solid #101010;"><?= date('d/m/Y', strtotime($date_visite)) ?></span></p>
            <p style="margin: 0; font-size: 1rem;">💰 Total payé : <span style="color: #ff2ea6;"><?= number_format($prix_total, 2, ',', ' ') ?> €</span> (Commande n°<?= $reservation_id ?>)</p>
        </div>

        <h3 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin-top: 25px; border-bottom: 3px solid #101010; padding-bottom: 5px; font-size: 1.1rem;">🎟️ Vos E-Billets Uniques</h3>
        <p style="font-size: 0.85rem; font-weight: 600; color: #7c3aff; margin-bottom: 15px;">Chaque billet ci-dessous possède un code unique et un QR code infalsifiable à présenter aux tourniquets du parc.</p>

        <!-- Boucle sur les e-billets -->
        <?php foreach ($e_billets as $billet): ?>
        <div class="carte-billet">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px dashed #101010; padding-bottom: 8px; margin-bottom: 10px;">
                <div>
                    <span style="font-family: 'Archivo Black', Impact, sans-serif; font-size: 1rem; color: #ff6b1a; display: block;"><?= htmlspecialchars($billet['libelle']) ?></span>
                    <span style="font-size: 0.8rem; font-weight: 700;">Billet n° <?= $billet['numero_billet'] ?></span>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 0.7rem; text-transform: uppercase; font-weight: 800; background: #101010; color: #fff; padding: 2px 5px;">Code Unique</span>
                    <div style="font-family: monospace; font-weight: 900; font-size: 0.95rem; color: #7c3aff; margin-top: 2px;"><?= $billet['code_unique'] ?></div>
                </div>
            </div>

            <div style="text-align: center; background: #fff; border: 2px solid #101010; padding: 8px; display: block; box-sizing: border-box;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?= urlencode($billet['code_unique']) ?>" alt="QR Code Billet" style="width: 120px; height: 120px; display: block; margin: 0 auto;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #555; display: block; margin-top: 4px;">Scannez-moi à l'entrée</span>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Pied de page -->
        <div style="text-align: center; margin-top: 20px; font-size: 0.8rem; font-weight: 700; color: #555; border-top: 3px solid #101010; padding-top: 10px; page-break-inside: avoid;">
            <p style="margin: 0;">Studio Toon - Parcs et Loisirs Déjantés</p>
            <p style="margin: 3px 0 0;">Conservez cet e-mail précieusement sur votre smartphone le jour de votre visite.</p>
        </div>

    </div>

</body>
</html>