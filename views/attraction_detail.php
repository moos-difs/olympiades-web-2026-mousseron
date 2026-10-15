<?php
$pageTitle = htmlspecialchars($attraction['nom']) . " - Studio Toon";
ob_start();
?>
    <div class="fiche">
        <div class="fiche__fil-ariane">
            <a href="index.php?action=attractions">← Retour aux attractions</a>
        </div>

        <h1 class="fiche__titre"><?= htmlspecialchars($attraction['nom']) ?></h1>
        <div class="fiche__meta">
            Univers : <span style="color: var(--rose);"><?= htmlspecialchars($attraction['univers_nom']) ?></span>
        </div>

        <div style="margin-bottom: 2rem;">
            <img src="static/images/<?= htmlspecialchars($attraction['image']) ?>" alt="<?= htmlspecialchars($attraction['nom']) ?>" class="preuve__image" style="width: 100%; max-height: 400px; object-fit: cover;">
        </div>

        <div class="fiche__bloc">
            <h2>🎬 Le Gag Cartoon</h2>
            <p style="font-weight: 700; font-size: 1.1rem; color: var(--violet);"><?= nl2br(htmlspecialchars($attraction['gag'])) ?></p>
        </div>

        <div class="fiche__bloc">
            <h2>Description du décor</h2>
            <p style="font-weight: 500; font-size: 1.05rem;"><?= nl2br(htmlspecialchars($attraction['description'])) ?></p>
        </div>

        <div class="fiche__bloc">
            <h2>Caractéristiques techniques</h2>
            <p style="font-weight: 500; font-size: 1.05rem;"><?= htmlspecialchars($attraction['caracteristiques']) ?></p>
        </div>

        <div class="fiche__bloc">
            <h2>Informations pratiques</h2>
            <ul class="liste-competences">
                <li><strong>Temps d'attente estimé :</strong> <?= htmlspecialchars($attraction['attente']) ?> min</li>
                <li><strong>Taille minimale requise :</strong> <?= htmlspecialchars($attraction['taille_minimale']) ?> cm</li>
                <li>
                    <strong>Statut actuel :</strong> 
                    <span style="color: <?= $attraction['statut'] == 'Ouvert' ? 'var(--noir)' : 'var(--rose)' ?>;">
                        <?= htmlspecialchars($attraction['statut']) ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
<?php
$content = ob_get_clean();
require 'views/base.php';
?>