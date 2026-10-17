<?php 
/**
 * Vue : Administration - Réservations du Parc
 * Tableau de bord administrateur permettant le suivi global de toutes les ventes de billets.
 */

$pageTitle = "Administration - Réservations du Parc";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/admin_reservations.css">
<!-- En-tête de la page d'administration -->
<div class="page-entete">
    <div class="entete-admin-reservations-flex">
        <div>
            <h1>🛠️ Gestion des Réservations</h1>
            <p class="page-entete__intro intro-marge-nulle">Suivi global des ventes de billets du parc.</p>
        </div>
        <a href="index.php?action=admin_attractions" class="bouton bouton-cyan bouton-marge-nulle">
            Gérer les attractions
        </a>
    </div>
</div>

<!-- Tableau récapitulatif des réservations -->
<div class="table-responsive conteneur-tableau-admin">
    <table class="crud-table table-reservations-admin">
        <thead>
            <tr class="entete-tableau-orange">
                <th class="cellule-tableau">ID Résa</th>
                <th class="cellule-tableau">Client</th>
                <th class="cellule-tableau">Date de Visite</th>
                <th class="cellule-tableau">Montant</th>
                <th class="cellule-tableau">Date commande</th>
                <th class="cellule-tableau text-center">Mail de confirmation</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $r): ?>
                <tr class="ligne-tableau-reservation">
                    <td class="cellule-tableau poids-gras">#<?= htmlspecialchars($r['id'] ?? '') ?></td>
                    <td class="cellule-tableau">
                        <strong><?= htmlspecialchars($r['utilisateur_nom'] ?? 'Inconnu') ?></strong><br>
                        <small class="email-client-admin"><?= htmlspecialchars($r['utilisateur_email'] ?? '') ?></small>
                    </td>
                    <td class="cellule-tableau poids-gras couleur-date-visite">
                        <?= isset($r['date_visite']) ? date('d/m/Y', strtotime($r['date_visite'])) : '—' ?>
                    </td>
                    <td class="cellule-tableau poids-gras">
                        <?= number_format($r['prix_total'] ?? 0, 2, ',', ' ') ?> €
                    </td>
                    <td class="cellule-tableau taille-texte-petite">
                        <?= isset($r['created_at']) ? date('d/m/Y H:i', strtotime($r['created_at'])) : '—' ?>
                    </td>
                    <td class="cellule-tableau text-center">
                        <a href="index.php?action=voir_mail&id=<?= htmlspecialchars($r['id'] ?? '') ?>" target="_blank" class="bouton-voir-mail-admin">
                            📄 Voir le mail
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="cellule-tableau message-vide-admin">
                        Aucune réservation enregistrée pour le moment.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>