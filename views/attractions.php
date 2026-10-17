<?php 
/**
 * Vue : Liste des Attractions
 * Permet d'afficher toutes les attractions du parc et de filtrer par univers (Studio 2D, Le Chapiteau, etc.).
 */

$pageTitle = "Attractions - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/attractions.css">
<!-- En-tête de la page des attractions -->
<div class="page-entete">
    <h1>Nos Attractions</h1>
    <p class="page-entete__intro">Filtre par univers pour trouver ton prochain frisson.</p>

    <!-- Filtres par univers -->
    <div class="filtres-univers-container">
        <a 
            href="index.php?action=attractions" 
            class="bouton bouton-filtre <?= empty($current_univers) ? 'bouton-filtre-actif' : 'bouton-filtre-tous' ?>"
        >
            Tous
        </a>
        <a 
            href="index.php?action=attractions&univers=Studio+2D" 
            class="bouton bouton-filtre <?= ($current_univers == 'Studio 2D') ? 'bouton-filtre-actif' : 'bouton-filtre-studio2d' ?>"
        >
            Studio 2D
        </a>
        <a 
            href="index.php?action=attractions&univers=Le+Chapiteau" 
            class="bouton bouton-filtre <?= ($current_univers == 'Le Chapiteau') ? 'bouton-filtre-actif' : 'bouton-filtre-chapiteau' ?>"
        >
            Le Chapiteau
        </a>
    </div>
</div>

<!-- Liste des attractions -->
<div class="liste-realisations">
    <?php if (!empty($attractions)): ?>
        <?php foreach ($attractions as $a): ?>
            <article class="carte-realisation">
                
                <h3><?= htmlspecialchars($a['nom'] ?? '') ?></h3>
                
                <p class="carte-realisation__meta">
                    Univers : <?= htmlspecialchars($a['univers_nom'] ?? '') ?> | Statut :
                    <?php 
                        $statut = $a['statut'] ?? 'Inconnu';
                        $classeStatut = 'statut-inconnu';
                        if ($statut === 'Ouvert') $classeStatut = 'statut-ouvert';
                        elseif ($statut === 'Maintenance') $classeStatut = 'statut-maintenance';
                        elseif ($statut === 'Ferme') $classeStatut = 'statut-ferme';
                    ?>
                    <span class="badge-statut-attraction <?= $classeStatut ?>">
                        <?= htmlspecialchars($statut) ?>
                    </span>
                </p>

                <p class="carte-realisation__extrait">
                    <?= htmlspecialchars(substr($a['description'] ?? '', 0, 100)) ?>...
                </p>

                <a href="index.php?action=detail&id=<?= htmlspecialchars($a['id'] ?? 0) ?>" class="carte-realisation__lien">
                    Découvrir l'attraction →
                </a>

            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="vide">Aucune attraction trouvée dans cet univers !</p>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>