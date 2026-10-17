<?php 
/**
 * Vue : Détail d'une Attraction
 * Affiche la fiche complète d'une attraction (image, gag cartoon, description du décor,
 * caractéristiques techniques et informations pratiques).
 */

$pageTitle = htmlspecialchars($attraction['nom'] ?? 'Attraction') . " - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/attraction_detail.css">
<div class="fiche">
    <!-- Fil d'ariane / Retour -->
    <div class="fiche__fil-ariane">
        <a href="index.php?action=attractions" class="lien-retour-attractions">← Retour aux attractions</a>
    </div>

    <!-- En-tête de la fiche -->
    <h1 class="fiche__titre"><?= htmlspecialchars($attraction['nom'] ?? '') ?></h1>
    <div class="fiche__meta">
        Univers : <span class="univers-nom-highlight"><?= htmlspecialchars($attraction['univers_nom'] ?? 'Studio Toon') ?></span>
    </div>

    <!-- Cadre néo-brutaliste enveloppant l'image -->
    <div id="cadre-image-attraction">
        <img 
            src="static/images/<?= htmlspecialchars($attraction['image'] ?? 'default.png') ?>" 
            alt="<?= htmlspecialchars($attraction['nom'] ?? 'Attraction') ?>" 
            class="image-attraction-detail"
        >
    </div>

    <!-- 1. Le Gag Cartoon -->
    <div class="fiche__bloc">
        <h2>🎬 Le Gag Cartoon</h2>
        <p class="texte-gag-cartoon"><?= nl2br(htmlspecialchars($attraction['gag'] ?? '')) ?></p>
    </div>

    <!-- 2. Description du décor -->
    <div class="fiche__bloc">
        <h2>Description du décor</h2>
        <p class="texte-description-decor"><?= nl2br(htmlspecialchars($attraction['description'] ?? '')) ?></p>
    </div>

    <!-- 3. Caractéristiques techniques -->
    <div class="fiche__bloc">
        <h2>Caractéristiques techniques</h2>
        <p class="texte-caracteristiques"><?= htmlspecialchars($attraction['caracteristiques'] ?? '') ?></p>
    </div>

    <!-- 4. Informations pratiques -->
    <div class="fiche__bloc">
        <h2>Informations pratiques</h2>
        <ul class="liste-competences">
            <li>
                <strong>Temps d'attente estimé :</strong> 
                <?php if (($attraction['statut'] ?? '') === 'Ouvert'): ?>
                    <?= htmlspecialchars($attraction['attente'] ?? '0') ?> min
                <?php else : ?>
                    Attraction non disponible
                <?php endif; ?>
            </li>
            <li>
                <strong>Taille minimale requise :</strong> <?= htmlspecialchars($attraction['taille_minimale'] ?? '0') ?> cm
            </li>
            <li>
                <strong>Statut actuel :</strong> 
                <?php 
                    $statut = $attraction['statut'] ?? 'Inconnu';
                    $classeStatut = ($statut === 'Ouvert') ? 'statut-ouvert-detail' : 'statut-ferme-detail';
                ?>
                <span class="badge-statut-detail <?= $classeStatut ?>">
                    <?= htmlspecialchars($statut) ?>
                </span>
            </li>
        </ul>
    </div>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>