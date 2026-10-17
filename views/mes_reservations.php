<?php 
/**
 * Vue : Mes Réservations
 * Affiche l'historique de toutes les visites et commandes passées par l'utilisateur.
 */

$pageTitle = "Mes Réservations - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/mes_reservations.css">
<!-- En-tête de la page -->
<div class="page-entete">
    <h1>🎟️ Mes Réservations</h1>
    <p class="page-entete__intro">Retrouvez l'historique de toutes vos visites au parc Studio Toon.</p>
</div>

<!-- Liste des réservations de l'utilisateur -->
<div class="liste-realisations">
    <?php if (!empty($reservations)): ?>
        <?php foreach ($reservations as $r): ?>
        <article class="carte-realisation">
            
            <h3 class="carte-realisation__titre">
                Réservation n° <?= htmlspecialchars($r['id']) ?>
            </h3>

            <p class="carte-realisation__meta">
                Date de visite : <strong class="date-visite-valeur"><?= date('d/m/Y', strtotime($r['date_visite'])) ?></strong> | 
                Commandé le : <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
            </p>

            <p class="carte-realisation__extrait">
                Montant total payé : <span class="badge-montant-total"><?= number_format($r['prix_total'] ?? 0, 2, ',', ' ') ?> €</span>
            </p>

        </article>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Message affiché si l'utilisateur n'a aucune réservation -->
        <p class="vide">
            Vous n'avez encore effectué aucune réservation pour le moment ! 
            <a href="index.php?action=billetterie" class="lien-rose">Réservez dès maintenant !</a>
        </p>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>