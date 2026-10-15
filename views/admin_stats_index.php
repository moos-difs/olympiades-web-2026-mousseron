<?php 
$pageTitle = "Statistiques - Studio Toon";
ob_start(); 
?>

<!-- En-tête -->
<div class="page-entete" style="max-width: 1000px; margin: 0 auto 2rem auto; background: var(--violet); color: #fff; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="color: #fff; font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 2rem;">📈 Analytics & Performances</h1>
            <p class="page-entete__intro" style="color: #fff; margin: 0.5rem 0 0; font-weight: 700;">Suivi détaillé des ventes, du chiffre d'affaires journalier et des temps d'attente.</p>
        </div>
        <div>
            <a href="index.php?action=accueil" style="background: var(--cyan); color: var(--noir); padding: 0.8rem 1.2rem; border: 3px solid var(--noir); font-weight: 900; text-decoration: none; box-shadow: 4px 4px 0 var(--noir); display: inline-block; text-transform: uppercase;">
                ← Accueil
            </a>
        </div>
    </div>
</div>

<div style="max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 2.5rem;">

    <!-- 1. CHIFFRE D'AFFAIRES GLOBAL & Kpis -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div style="background: var(--vert); border: 4px solid var(--noir); box-shadow: 6px 6px 0 var(--noir); padding: 1.8rem;">
            <span style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; background: var(--noir); color: #fff; padding: 3px 8px;">Chiffre d'affaires Global</span>
            <div style="font-family: 'Archivo Black', Impact, sans-serif; font-size: 2.8rem; color: var(--noir); margin-top: 1rem;">
                <?= number_format($kpis['chiffre_affaires'] ?? 0, 2, ',', ' ') ?> €
            </div>
            <p style="margin: 0.5rem 0 0; font-weight: 700; font-size: 0.9rem;">Recettes cumulées du parc</p>
        </div>

        <div style="background: var(--cyan); border: 4px solid var(--noir); box-shadow: 6px 6px 0 var(--noir); padding: 1.8rem;">
            <span style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; background: var(--noir); color: #fff; padding: 3px 8px;">Total Billets Vendus</span>
            <div style="font-family: 'Archivo Black', Impact, sans-serif; font-size: 2.8rem; color: var(--noir); margin-top: 1rem;">
                <?= $kpis['total_billets'] ?? '0' ?> places
            </div>
            <p style="margin: 0.5rem 0 0; font-weight: 700; font-size: 0.9rem;">Volume global de réservations</p>
        </div>
    </div>

    <!-- 2. GRAPHIQUE : BILLETS VENDUS ET CA PAR JOUR -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            📅 Billets & Chiffre d'Affaires par Jour de Visite
        </h2>

        <?php if (!empty($ventes_jour)): ?>
            <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-top: 1.5rem;">
                <?php foreach ($ventes_jour as $ligne): ?>
                    <?php 
                        $billets = (int)$ligne['total_billets_jour'];
                        $caJour = (float)$ligne['ca_jour'];
                        // Calcul d'un pourcentage pour la barre visuelle (base max arbitraire 500 billets/jour pour l'échelle)
                        $pct = min(100, ($billets / 500) * 100);
                    ?>
                    <div style="background: #fff; border: 2px solid var(--noir); padding: 1rem; box-shadow: 4px 4px 0 var(--noir);">
                        <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 1rem; margin-bottom: 8px;">
                            <span>🗓️ <?= date('d/m/Y', strtotime($ligne['date_visite'])) ?></span>
                            <span style="color: var(--violet);"><?= number_format($caJour, 2, ',', ' ') ?> €</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 5px;">
                            <span>Volume : <strong><?= $billets ?></strong> billets</span>
                        </div>
                        <!-- Barre graphique néo-brutaliste -->
                        <div style="background: #eee; border: 2px solid var(--noir); width: 100%; height: 18px;">
                            <div style="background: var(--orange); width: <?= $pct ?>%; height: 100%; border-right: 2px solid var(--noir);"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="font-weight: 700; text-align: center; padding: 2rem; background: #fff; border: 2px dashed var(--noir);">
                Aucune donnée journalière disponible.
            </p>
        <?php endif; ?>
    </div>

    <!-- 3. TEMPS D'ATTENTE MOYEN PAR ATTRACTION -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            ⏱️ Temps d'Attente Moyen par Attraction
        </h2>

        <?php if (!empty($attractions_stats)): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem;">
                <?php foreach ($attractions_stats as $att): ?>
                    <?php 
                        $temps = (int)$att['temps_attente_actuel'];
                        // Échelle visuelle max 120 min pour la barre
                        $pctAttente = min(100, ($temps / 120) * 100);
                        $couleurTemps = $temps > 60 ? 'var(--rose)' : ($temps > 30 ? 'var(--orange)' : 'var(--vert)');
                    ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 0.9rem; margin-bottom: 3px;">
                            <span><?= htmlspecialchars($att['nom']) ?></span>
                            <span><?= $temps ?> minutes</span>
                        </div>
                        <div style="background: #fff; border: 2px solid var(--noir); width: 100%; height: 20px; box-shadow: 3px 3px 0 var(--noir);">
                            <div style="background: <?= $couleurTemps ?>; width: <?= $pctAttente ?>%; height: 100%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="font-weight: 700; text-align: center; padding: 1.5rem; background: #fff; border: 2px dashed var(--noir);">
                Aucune attraction enregistrée.
            </p>
        <?php endif; ?>
    </div>

    <!-- GRAPHIQUE COMBINÉ : Billets Vendus vs Billets Validés par Jour -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem; margin-top: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            📊 Comparatif : Billets Vendus vs Validés par Jour
        </h2>
        
        <div style="display: flex; gap: 1.5rem; justify-content: center; margin: 1rem 0; font-weight: 800; font-size: 0.85rem;">
            <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 15px; height: 15px; background: var(--cyan); border: 2px solid var(--noir); display: inline-block;"></span> Billets Vendus</span>
            <span style="display: flex; align-items: center; gap: 5px;"><span style="width: 15px; height: 15px; background: #ff6b1a; border: 2px solid var(--noir); display: inline-block; border-radius: 50%;"></span> Billets Validés (Entrés)</span>
        </div>

        <?php if (!empty($comparatif_jours)): ?>
            <?php 
                // Trouver la valeur maximale pour l'échelle du graphique (minimum 10 pour éviter division par 0)
                $maxVal = 10;
                foreach ($comparatif_jours as $cj) {
                    $maxVal = max($maxVal, (int)$cj['total_vendus'], (int)$cj['total_valides']);
                }
            ?>
            
            <!-- Conteneur du graphique en colonnes -->
            <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 250px; border-bottom: 3px solid var(--noir); border-left: 3px solid var(--noir); padding: 1rem 0.5rem 0 0.5rem; background: #fff; margin-top: 1rem; position: relative; gap: 10px;">
                
                <?php foreach ($comparatif_jours as $cj): ?>
                    <?php 
                        $vendus = (int)$cj['total_vendus'];
                        $valides = (int)$cj['total_valides'];
                        $hauteurVendus = ($vendus / $maxVal) * 100;
                        $hauteurValides = ($valides / $maxVal) * 100;
                    ?>
                    <div style="display: flex; flex-direction: column; align-items: center; flex: 1; height: 100%; justify-content: flex-end; position: relative;">
                        
                        <!-- Groupe de barres pour le jour -->
                        <div style="display: flex; align-items: flex-end; gap: 4px; width: 100%; height: 100%; justify-content: center; position: relative;">
                            
                            <!-- Barre Billets Vendus (Cyan) -->
                            <div title="Vendus : <?= $vendus ?>" style="width: 45%; height: <?= $hauteurVendus ?>%; background: var(--cyan); border: 2px solid var(--noir); position: relative; transition: height 0.3s;">
                                <span style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); font-size: 0.7rem; font-weight: 900;"><?= $vendus ?></span>
                            </div>

                            <!-- Barre / Point Billets Validés (Orange) -->
                            <div title="Validés : <?= $valides ?>" style="width: 45%; height: <?= $hauteurValides ?>%; background: #ff6b1a; border: 2px solid var(--noir); position: relative; transition: height 0.3s;">
                                <span style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); font-size: 0.7rem; font-weight: 900; color: #ff6b1a;"><?= $valides ?></span>
                            </div>
                        </div>

                        <!-- Légende de la date en bas -->
                        <div style="margin-top: 8px; font-size: 0.75rem; font-weight: 800; transform: rotate(-20deg); white-space: nowrap;">
                            <?= date('d/m', strtotime($cj['date_visite'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <p style="font-weight: 700; text-align: center; padding: 2rem; background: #fff; border: 2px dashed var(--noir); margin-top: 1rem;">
                Aucune donnée comparative disponible pour le moment.
            </p>
        <?php endif; ?>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>