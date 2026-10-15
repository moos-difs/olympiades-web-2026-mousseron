<?php 
$pageTitle = "Mes Réservations - Studio Toon";
ob_start(); 
?>

<div class="page-entete">
    <h1>🎟️ Mes Réservations</h1>
    <p class="page-entete__intro">Retrouvez l'historique de toutes vos visites au parc Studio Toon.</p>
</div>

<div class="liste-realisations">
    <?php if (!empty($reservations)): ?>
        <?php foreach ($reservations as $r): ?>
        <div class="carte-realisation" style="background: var(--fond-carte);">
            <h3>Réservation n° <?= $r['id'] ?></h3>
            <p class="carte-realisation__meta">
                Date de visite : <strong style="color: var(--rose);"><?= date('d/m/Y', strtotime($r['date_visite'])) ?></strong> | 
                Commandé le : <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
            </p>
            <p class="carte-realisation__extrait" style="font-size: 1.1rem; font-weight: 800;">
                Montant total payé : <span style="color: var(--vert); background: var(--noir); padding: 0.2rem 0.5rem;"><?= number_format($r['prix_total'], 2, ',', ' ') ?> €</span>
            </p>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="vide">Vous n'avez encore effectué aucune réservation pour le moment ! <a href="index.php?action=billetterie" style="color: var(--rose); text-decoration: underline wavy;">Réservez dès maintenant</a></p>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>