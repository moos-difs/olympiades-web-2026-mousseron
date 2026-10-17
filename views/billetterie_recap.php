<?php 
/**
 * Vue : Commande Validée
 * Message de confirmation affiché après un paiement réussi, avec le récapitulatif des billets commandés.
 */

$pageTitle = "Commande Validée - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/billetterie_recap.css">
<!-- Carte principale de confirmation de commande -->
<div id="carte-commande-validee" class="fiche-veille">
    <h2 class="titre-validation">🎉 COMMANDE VALIDÉE !</h2>
    
    <p class="texte-validation">
        Paiement accepté ! Votre réservation n°<strong><?= htmlspecialchars($recap['reservation_id'] ?? '') ?></strong> est confirmée pour le <span class="badge-date-visite"><?= date('d/m/Y', strtotime($recap['date_visite'] ?? 'now')) ?></span>.
    </p>

    <!-- Détail de la commande -->
    <div class="panneau-details-commande">
        <h3 class="titre-details-commande">Détail de vos billets :</h3>
        
        <ul class="liste-billets-recens">
            <?php if (!empty($recap['billets'])): ?>
                <?php foreach ($recap['billets'] as $b): ?>
                <li class="ligne-billet-recap">
                    <span><?= htmlspecialchars($b['quantite']) ?>x <?= htmlspecialchars($b['libelle']) ?></span>
                    <strong><?= number_format($b['sous_total'] ?? 0, 2, ',', ' ') ?> €</strong>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <div class="total-paye-container">
            <span>TOTAL PAYÉ :</span>
            <span class="montant-total-valeur"><?= number_format($recap['prix_total'] ?? 0, 2, ',', ' ') ?> €</span>
        </div>
    </div>

    <!-- Actions de redirection -->
    <div class="actions-validation">
        <a href="index.php?action=mes_reservations" id="bouton-vers-reservations" class="bouton">
            Voir mes réservations →
        </a>
        <a href="index.php?action=accueil" id="bouton-vers-accueil-val" class="bouton">
            Accueil
        </a>
    </div>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>