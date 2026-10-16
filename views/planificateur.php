<?php 
$pageTitle = "Planificateur de Journée - Studio Toon";
ob_start(); 
?>

<!-- En-tête -->
<div class="page-entete" style="max-width: 900px; margin: 0 auto 2rem auto; background: var(--violet); color: #fff; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
    <h1 style="color: #fff; font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 2rem;">🎯 Mon Planificateur de Visite</h1>
    <p class="page-entete__intro" style="color: #fff; margin: 0.5rem 0 0; font-weight: 700;">Indiquez votre taille ci-dessous pour générer votre parcours sur-mesure et éviter les files d'attente !</p>
</div>

<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">

    <!-- Formulaire de saisie directe de la taille -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            📏 Critères de la visite
        </h2>

        <form method="GET" action="index.php" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap; margin-top: 1.5rem;">
            <input type="hidden" name="action" value="planificateur">
            
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 0.9rem;">Votre taille (en mètres) :</label>
                <input type="number" step="0.01" min="0.50" max="2.50" name="taille_visiteur" value="<?= htmlspecialchars($taille_visiteur ?? '1.20') ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 700; background: #fff;">
            </div>

            <button type="submit" style="background: var(--cyan); color: var(--noir); padding: 0.9rem 1.5rem; border: 3px solid var(--noir); font-weight: 900; text-transform: uppercase; box-shadow: 4px 4px 0 var(--noir); cursor: pointer;">
                Générer mon parcours →
            </button>
        </form>
    </div>

    <!-- Résultat du parcours optimisé -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            🚀 Votre Ordre de Visite Optimisé (Taille : <?= htmlspecialchars($taille_visiteur) ?>m)
        </h2>

        <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem;">
            <?php if (!empty($parcours)): ?>
                <?php $index = 1; foreach ($parcours as $att): ?>
                    <div style="background: #fff; border: 3px solid var(--noir); padding: 1.2rem; box-shadow: 4px 4px 0 var(--noir); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="background: var(--violet); color: #fff; font-family: 'Archivo Black', Impact, sans-serif; width: 40px; height: 40px; border: 2px solid var(--noir); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 2px 2px 0 var(--noir);"><?= $index++ ?></div>
                            <div>
                                <h3 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0; font-size: 1.1rem; text-transform: uppercase;"><?= htmlspecialchars($att['nom']) ?></h3>
                                <p style="margin: 0.2rem 0 0 0; font-weight: 700; font-size: 0.85rem; color: #555;">Univers : <?= htmlspecialchars($att['univers_nom'] ?? 'Studio Toon') ?></p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                            <span style="background: var(--vert); border: 2px solid var(--noir); padding: 0.4rem 0.8rem; font-weight: 800; font-size: 0.85rem;">⏱️ <?= htmlspecialchars($att['attente']) ?> min d'attente</span>
                            <span style="background: #fff35c; border: 2px solid var(--noir); padding: 0.4rem 0.8rem; font-weight: 800; font-size: 0.85rem;">📏 Taille mini : <?= htmlspecialchars($att['taille_minimale']) ?>m</span>
                            <a href="index.php?action=attraction_detail&id=<?= htmlspecialchars($att['id']) ?>" style="background: var(--cyan); color: var(--noir); padding: 0.4rem 0.8rem; border: 2px solid var(--noir); font-weight: 900; text-decoration: none; font-size: 0.85rem; text-transform: uppercase; box-shadow: 2px 2px 0 var(--noir);">Voir →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="background: #fff; border: 3px solid var(--noir); padding: 1.5rem; text-align: center; box-shadow: 4px 4px 0 var(--noir);">
                    <p style="font-weight: 800; margin: 0; color: #555;">Aucune attraction ne correspond à cette taille ou toutes sont actuellement fermées.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>