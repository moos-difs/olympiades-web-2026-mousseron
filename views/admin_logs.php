<?php
/**
 * Vue : Journal d'Audit Administrateur
 * Interface administrateur permettant de suivre toutes les opérations 
 * critiques effectuées sur la plateforme Studio Toon.
 */

$pageTitle = "Journal d'Audit - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/admin_logs.css">

<!-- En-tête de la page du journal d'audit -->
<div id="entete-journal-audit" class="page-entete">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1>📜 Journal d'Audit Administrateur</h1>
            <p class="page-entete__intro">Suivi en temps réel de toutes les opérations critiques effectuées par les administrateurs du parc.</p>
        </div>
        <a href="index.php?action=accueil" class="bouton">
            ⬅ Tableau de bord
        </a>
    </div>
</div>

<!-- Conteneur principal du journal -->
<div id="conteneur-journal-audit" class="logs-wrapper">
    
    <div class="logs-table-container">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Date / Heure</th>
                    <th>Administrateur</th>
                    <th>Action</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="4" class="logs-empty">Aucune action enregistrée pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="logs-date">
                            <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                        </td>
                        <td class="logs-admin-nom">
                            <?= htmlspecialchars($log['admin_nom'] ?? 'Admin') ?>
                        </td>
                        <td>
                            <?php 
                                $badgeColor = '#00ff9c';
                                if ($log['action'] === 'MODIF_ATTENTE') $badgeColor = '#00d9ff';
                                if ($log['action'] === 'SUPPRESSION_ATTRACTION') $badgeColor = '#ff2ea6';
                                if ($log['action'] === 'MAINTENANCE_PARC') $badgeColor = '#ff6b1a';
                            ?>
                            <span class="logs-badge" style="background: <?= $badgeColor ?>;">
                                <?= htmlspecialchars($log['action']) ?>
                            </span>
                        </td>
                        <td class="logs-details">
                            <?= htmlspecialchars($log['details']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>