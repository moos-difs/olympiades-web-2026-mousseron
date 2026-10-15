<?php
$pageTitle = "Attractions - Studio Toon";
ob_start();
?>
    <div class="page-entete">
        <h1>Nos Attractions</h1>
        <p class="page-entete__intro">Filtre par univers pour trouver ton prochain frisson.</p>

        <div style="margin-top: 1.5rem; display: flex; gap: 0.8rem; flex-wrap: wrap;">
            <a href="index.php?action=attractions" class="bouton" style="margin-top:0; background: <?= empty($current_univers) ? 'var(--noir); color:#fff' : 'var(--cyan); color:var(--noir)' ?>;">Tous</a>
            <a href="index.php?action=attractions&univers=Studio+2D" class="bouton" style="margin-top:0; background: <?= ($current_univers == 'Studio 2D') ? 'var(--noir); color:#fff' : 'var(--vert); color:var(--noir)' ?>;">Studio 2D</a>
            <a href="index.php?action=attractions&univers=Le+Chapiteau" class="bouton" style="margin-top:0; background: <?= ($current_univers == 'Le Chapiteau') ? 'var(--noir); color:#fff' : 'var(--orange); color:var(--noir)' ?>;">Le Chapiteau</a>
        </div>
    </div>

    <div class="liste-realisations">
        <?php if (!empty($attractions)): ?>
            <?php foreach ($attractions as $a): ?>
                <div class="carte-realisation">
                    <h3><?= htmlspecialchars($a['nom']) ?></h3>
                    <p class="carte-realisation__meta">
                        Univers : <?= htmlspecialchars($a['univers_nom']) ?> | Statut :
                        <span style="color: <?= $a['statut'] == 'Ouvert' ? 'var(--vert)' : ($a['statut'] == 'Maintenance' ? 'var(--orange)' : 'var(--rose)') ?>;">
                    <?= htmlspecialchars($a['statut']) ?>
                </span>
                    </p>
                    <p class="carte-realisation__extrait"><?= htmlspecialchars(substr($a['description'], 0, 100)) ?>...</p>
                    <a href="index.php?action=detail&id=<?= $a['id'] ?>" class="carte-realisation__lien">Découvrir l'attraction →</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="vide">Aucune attraction trouvée dans cet univers !</p>
        <?php endif; ?>
    </div>
<?php
$content = ob_get_clean();
require 'views/base.php';
?>