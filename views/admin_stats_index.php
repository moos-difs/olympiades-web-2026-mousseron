<?php 
/**
 * Vue : Analytics & Performances (Statistiques Admin)
 * Tableau de bord d'analyse du chiffre d'affaires, des billets vendus vs restants,
 * des temps d'attente moyens et du comparatif d'accès au parc.
 */

$pageTitle = "Statistiques - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/admin_stats_index.css">
<!-- En-tête de la page Analytics -->
<div id="entete-stats" class="page-entete">
    <div class="entete-stats-flex">
        <div>
            <h1 class="titre-stats">📈 Analytics & Performances</h1>
            <p class="page-entete__intro intro-stats">
                Suivi détaillé des ventes, du chiffre d'affaires journalier et des temps d'attente.
            </p>
        </div>
        <div>
            <a href="index.php?action=accueil" class="bouton-retour-accueil">
                ← Accueil
            </a>
        </div>
    </div>
</div>

<div id="conteneur-stats-dashboard">

    <!-- 1. CHIFFRE D'AFFAIRES GLOBAL & KPIS -->
    <section class="grille-kpis">
        <div class="carte-kpi kpi-ca">
            <span class="badge-kpi-titre">Chiffre d'affaires Global</span>
            <div class="valeur-kpi">
                <?= number_format($kpis['chiffre_affaires'] ?? 0, 2, ',', ' ') ?> €
            </div>
            <p class="description-kpi">Recettes cumulées du parc</p>
        </div>

        <div class="carte-kpi kpi-billets">
            <span class="badge-kpi-titre">Total Billets Vendus</span>
            <div class="valeur-kpi">
                <?= htmlspecialchars($kpis['total_billets'] ?? '0') ?> places
            </div>
            <p class="description-kpi">Volume global de réservations</p>
        </div>
    </section>

    <!-- 2. BILLETS RESTANTS À VENDRE PAR JOUR (Jauge 2000 places) -->
    <section class="carte-section-stats">
        <h2 class="titre-section-stats">
            🎟️ Places Restantes par Jour (Jauge max : 2000)
        </h2>

        <?php if (!empty($billets_restants)): ?>
            <div class="grille-places-restantes">
                <?php foreach ($billets_restants as $b): ?>
                    <?php 
                        $restants = (int)($b['total_restants'] ?? 0);
                        $couleurStockClass = $restants < 100 ? 'stock-critique' : ($restants < 500 ? 'stock-moyen' : 'stock-bon');
                    ?>
                    <div class="carte-place-jour">
                        <div class="date-place-jour">
                            🗓️ <?= date('d/m/Y', strtotime($b['date_visite'])) ?>
                        </div>
                        <div class="valeur-places-restantes <?= $couleurStockClass ?>">
                            <?= $restants ?> places
                        </div>
                        <div class="detail-places-vendues">
                            (<?= htmlspecialchars($b['total_vendus'] ?? 0) ?> vendus / 2000)
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="message-bloc-vide">
                Aucune réservation enregistrée pour calculer les stocks restants.
            </p>
        <?php endif; ?>
    </section>

    <!-- 3. GRAPHIQUE / BARRES : BILLETS VENDUS ET CA PAR JOUR -->
    <section class="carte-section-stats">
        <h2 class="titre-section-stats">
            📅 Billets & Chiffre d'Affaires par Jour de Visite
        </h2>

        <?php if (!empty($ventes_jour)): ?>
            <div class="liste-ventes-journalieres">
                <?php foreach ($ventes_jour as $ligne): ?>
                    <?php 
                        $billets = (int)($ligne['total_billets_jour'] ?? 0);
                        $caJour = (float)($ligne['ca_jour'] ?? 0);
                        $pct = min(100, ($billets / 500) * 100);
                    ?>
                    <div class="carte-vente-jour">
                        <div class="entete-vente-jour">
                            <span>🗓️ <?= date('d/m/Y', strtotime($ligne['date_visite'])) ?></span>
                            <span class="montant-ca-jour"><?= number_format($caJour, 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="volume-billets-jour">
                            <span>Volume : <strong><?= $billets ?></strong> billets</span>
                        </div>
                        <div class="barre-progression-fond">
                            <div class="barre-progression-remplissage" style="width: <?= $pct ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="message-bloc-vide">
                Aucune donnée journalière disponible.
            </p>
        <?php endif; ?>
    </section>

    <!-- 4. TEMPS D'ATTENTE MOYEN PAR ATTRACTION -->
    <section class="carte-section-stats">
        <h2 class="titre-section-stats">
            ⏱️ Temps d'Attente Moyen par Attraction
        </h2>

        <?php if (!empty($attractions_stats)): ?>
            <div class="liste-attractions-stats">
                <?php foreach ($attractions_stats as $att): ?>
                    <?php 
                        $temps = (int)($att['temps_attente_actuel'] ?? 0);
                        $pctAttente = min(100, ($temps / 120) * 100);
                        $couleurTempsClass = $temps > 60 ? 'temps-long' : ($temps > 30 ? 'temps-moyen' : 'temps-court');
                    ?>
                    <div class="ligne-attraction-stat">
                        <div class="entete-attraction-stat">
                            <span><?= htmlspecialchars($att['nom'] ?? '') ?></span>
                            <span><?= $temps ?> minutes</span>
                        </div>
                        <div class="barre-attente-fond">
                            <div class="barre-attente-remplissage <?= $couleurTempsClass ?>" style="width: <?= $pctAttente ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="message-bloc-vide">
                Aucune attraction enregistrée.
            </p>
        <?php endif; ?>
    </section>

    <!-- 5. COMPARATIF : BILLETS VENDUS VS VALIDÉS PAR DATE (AVEC FILTRE) -->
    <section class="carte-section-stats espace-haut-stats">
        <h2 class="titre-section-stats">
            📊 Comparatif : Billets Vendus vs Validés par Date
        </h2>
        
        <!-- Formulaire de filtre par date -->
        <form method="GET" action="index.php" class="formulaire-filtre-stats">
            <input type="hidden" name="action" value="admin_stats">
            
            <label for="select-date-visite" class="label-filtre-date">Sélectionner une date :</label>
            <select name="date_visite" id="select-date-visite" class="select-filtre-date">
                <option value="">-- Toutes les dates --</option>
                <?php if (!empty($dates_disponibles)): ?>
                    <?php foreach ($dates_disponibles as $d): ?>
                        <option value="<?= htmlspecialchars($d['date_visite']) ?>" <?= (isset($_GET['date_visite']) && $_GET['date_visite'] == $d['date_visite']) ? 'selected' : '' ?>>
                            <?= date('d/m/Y', strtotime($d['date_visite'])) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <button type="submit" class="bouton bouton-filtrer-stats">
                Filtrer →
            </button>

            <?php if (!empty($_GET['date_visite'])): ?>
                <a href="index.php?action=admin_stats" class="lien-reset-filtre">Réinitialiser</a>
            <?php endif; ?>
        </form>

        <!-- Légende du graphique -->
        <div class="legende-graphique-comparatif">
            <span class="element-legende">
                <span class="carre-legende carre-vendus"></span> Billets Vendus
            </span>
            <span class="element-legende">
                <span class="carre-legende carre-valides"></span> Billets Validés (Entrés)
            </span>
        </div>

        <?php if (!empty($comparatif_jours)): ?>
            <?php 
                $maxVal = 10;
                foreach ($comparatif_jours as $cj) {
                    $maxVal = max($maxVal, (int)($cj['total_vendus'] ?? 0), (int)($cj['total_valides'] ?? 0));
                }
            ?>
            
            <!-- Graphique en bâtons comparatif -->
            <div class="conteneur-graphique-batons">
                <?php foreach ($comparatif_jours as $cj): ?>
                    <?php 
                        $vendus = (int)($cj['total_vendus'] ?? 0);
                        $valides = (int)($cj['total_valides'] ?? 0);
                        $hauteurVendus = ($vendus / $maxVal) * 100;
                        $hauteurValides = ($valides / $maxVal) * 100;
                    ?>
                    <div class="colonne-comparative">
                        <div class="batons-flex-container">
                            <!-- Bâton des billets vendus -->
                            <div title="Vendus : <?= $vendus ?>" class="baton-graphique baton-vendus" style="height: <?= $hauteurVendus ?>%;">
                                <span class="valeur-baton-top"><?= $vendus ?></span>
                            </div>
                            <!-- Bâton des billets validés -->
                            <div title="Validés : <?= $valides ?>" class="baton-graphique baton-valides" style="height: <?= $hauteurValides ?>%;">
                                <span class="valeur-baton-top couleur-texte-valides"><?= $valides ?></span>
                            </div>
                        </div>

                        <div class="etiquette-date-graphique">
                            <?= date('d/m/Y', strtotime($cj['date_visite'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="message-bloc-vide marge-haut-vide">
                Aucune donnée comparative disponible pour la date sélectionnée.
            </p>
        <?php endif; ?>
    </section>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>