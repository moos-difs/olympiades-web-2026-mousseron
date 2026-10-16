<?php 
$pageTitle = "Administration - Réservations du Parc";
ob_start(); 
?>

<div class="page-entete">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1>🛠️ Gestion des Réservations</h1>
            <p class="page-entete__intro" style="margin-bottom: 0;">Suivi global des ventes de billets du parc.</p>
        </div>
        <a href="index.php?action=admin_attractions" class="bouton" style="background: var(--cyan); color: var(--noir); margin-top: 0;">Gérer les attractions</a>
    </div>
</div>

<div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 1.5rem; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-family: var(--police-texte);">
        <thead>
            <tr style="border-bottom: 4px solid var(--noir); background: var(--orange);">
                <th style="padding: 0.8rem; border: 2px solid var(--noir);">ID Résa</th>
                <th style="padding: 0.8rem; border: 2px solid var(--noir);">Client</th>
                <th style="padding: 0.8rem; border: 2px solid var(--noir);">Date de Visite</th>
                <th style="padding: 0.8rem; border: 2px solid var(--noir);">Montant</th>
                <th style="padding: 0.8rem; border: 2px solid var(--noir);">Date commande</th>
                <th style="padding: 0.8rem; border: 2px solid var(--noir);">Mail de confirmation</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $r): ?>
                <tr style="border-bottom: 2px solid var(--noir);">
                    <td style="padding: 0.8rem; border: 2px solid var(--noir); font-weight: bold;">#<?= $r['id'] ?></td>
                    <td style="padding: 0.8rem; border: 2px solid var(--noir);">
                        <strong><?= htmlspecialchars($r['utilisateur_nom']) ?></strong><br>
                        <small style="color: var(--violet);"><?= htmlspecialchars($r['utilisateur_email']) ?></small>
                    </td>
                    <td style="padding: 0.8rem; border: 2px solid var(--noir); font-weight: bold; color: var(--rose);"><?= date('d/m/Y', strtotime($r['date_visite'])) ?></td>
                    <td style="padding: 0.8rem; border: 2px solid var(--noir); font-weight: bold;"><?= number_format($r['prix_total'], 2, ',', ' ') ?> €</td>
                    <td style="padding: 0.8rem; border: 2px solid var(--noir); font-size: 0.9rem;"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    <td style="padding: 0.8rem; border: 2px solid var(--noir); text-align: center;">
                        <a href="index.php?action=voir_mail&id=<?= $r['id'] ?>" target="_blank" style="background: var(--cyan); color: var(--noir); padding: 0.3rem 0.6rem; border: 2px solid var(--noir); font-weight: 700; font-size: 0.85rem; text-decoration: none;">📄 Voir le mail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; font-weight: 700; color: var(--violet);">Aucune réservation enregistrée pour le moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>