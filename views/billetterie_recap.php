<?php 
$pageTitle = "Commande Validée - Studio Toon";
ob_start(); 
?>

<div class="fiche-veille" style="max-width: 650px; margin: 2rem auto; background: var(--vert); padding: 2.5rem; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); transform: rotate(-0.4deg);">
    <h2 style="font-size: 2.2rem; color: var(--noir); margin-bottom: 0.5rem; -webkit-text-stroke: 1px var(--noir);">🎉 COMMANDE VALIDÉE !</h2>
    <p style="font-weight: 700; font-size: 1.1rem; color: var(--noir);">
        Paiement accepté ! Votre réservation n°<strong><?= $recap['reservation_id'] ?></strong> est confirmée pour le <span style="background: #fff; padding: 0.2rem 0.5rem; border: 2px solid var(--noir);"><?= date('d/m/Y', strtotime($recap['date_visite'])) ?></span>.
    </p>

    <div style="background: #fff; border: 3px solid var(--noir); padding: 1.5rem; margin: 1.5rem 0; text-align: left;">
        <h3 style="margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 0.5rem;">Détail de vos billets :</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
            <?php foreach ($recap['billets'] as $b): ?>
            <li style="display: flex; justify-content: space-between; font-weight: 600; border-bottom: 1px dashed var(--noir); padding-bottom: 0.4rem;">
                <span><?= $b['quantite'] ?>x <?= htmlspecialchars($b['libelle']) ?></span>
                <strong><?= number_format($b['sous_total'], 2, ',', ' ') ?> €</strong>
            </li>
            <?php endforeach; ?>
        </ul>
        <div style="margin-top: 1.5rem; font-size: 1.3rem; font-weight: 800; display: flex; justify-content: space-between; border-top: 3px solid var(--noir); padding-top: 0.8rem;">
            <span>TOTAL PAYÉ :</span>
            <span style="color: var(--rose);"><?= number_format($recap['prix_total'], 2, ',', ' ') ?> €</span>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="index.php?action=mes_reservations" class="bouton" style="background: var(--noir); color: #fff; margin-top: 0;">Voir mes réservations →</a>
        <a href="index.php?action=accueil" class="bouton" style="background: var(--cyan); color: var(--noir); margin-top: 0;">Accueil</a>
    </div>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>