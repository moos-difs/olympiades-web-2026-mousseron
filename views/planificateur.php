<?php 
/**
 * Vue : Planificateur de Journée
 * Permet de générer un parcours d'attractions sur-mesure optimisé selon la taille du visiteur.
 */

$pageTitle = "Planificateur de Journée - Studio Toon";
$taille_visiteur = $taille_visiteur ?? '1.20';
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/planificateur.css">
<!-- En-tête de la page -->
<div id="entete-planificateur" class="page-entete">
    <h1 class="titre-planificateur">🎯 Mon Planificateur de Visite</h1>
    <p class="page-entete__intro intro-planificateur">
        Indiquez votre taille ci-dessous pour générer votre parcours sur-mesure et éviter les files d'attente !
    </p>
</div>

<div id="conteneur-planificateur">

    <!-- 1. Formulaire de saisie de la taille -->
    <section class="carte-section">
        <h2 class="titre-section">
            📏 Critères de la visite
        </h2>

        <form id="formulaire-planificateur" method="GET" action="index.php">
            <input type="hidden" name="action" value="planificateur">
            
            <div class="champ-formulaire-taille">
                <label for="champ-taille">Votre taille (en mètres) :</label>
                <input 
                    type="number" 
                    id="champ-taille"
                    step="0.01" 
                    min="0.50" 
                    max="2.50" 
                    name="taille_visiteur" 
                    value="<?= htmlspecialchars($taille_visiteur ?? '1.20') ?>" 
                    required
                >
            </div>

            <button type="submit" id="bouton-generer-parcours" class="bouton">
                Générer mon parcours →
            </button>
        </form>
    </section>

    <!-- 2. Résultat du parcours optimisé -->
    <section class="carte-section">
        <h2 class="titre-section">
            🚀 Votre Ordre de Visite Optimisé (Taille : <?= htmlspecialchars($taille_visiteur) ?>m)
        </h2>

        <div class="liste-parcours">
            <?php if (!empty($parcours)): ?>
                <?php $index = 1; foreach ($parcours as $att): ?>
                    <article class="carte-attraction-parcours">
                        
                        <div class="infos-attraction-main">
                            <div class="badge-index-parcours"><?= $index++ ?></div>
                            <div>
                                <h3 class="nom-attraction"><?= htmlspecialchars($att['nom']) ?></h3>
                                <p class="univers-attraction">
                                    Univers : <?= htmlspecialchars($att['univers_nom'] ?? 'Studio Toon') ?>
                                </p>
                            </div>
                        </div>

                        <div class="meta-attraction-parcours">
                            <span class="badge-attente">
                                ⏱️ <?= htmlspecialchars($att['attente']) ?> min d'attente
                            </span>
                            <span class="badge-taille">
                                📏 Taille mini : <?= htmlspecialchars($att['taille_minimale']) ?>m
                            </span>
                            <a href="index.php?action=attraction_detail&id=<?= htmlspecialchars($att['id']) ?>" class="bouton-voir-attraction">
                                Voir →
                            </a>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carte-vide">
                    <p class="texte-vide">
                        Aucune attraction ne correspond à cette taille ou toutes sont actuellement fermées.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>