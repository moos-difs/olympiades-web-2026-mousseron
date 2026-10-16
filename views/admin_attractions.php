<?php
$pageTitle = "Attractions - Studio Toon";
ob_start();
?>
<?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === "admin") : ?>
    <div class="page-entete">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1>🛠️ Gestion Admin</h1>
                <p class="page-entete__intro" style="margin-bottom: 0;">Ajoutez, modifiez ou supprimez les attractions du parc.</p>
            </div>
            <!-- Bouton pour créer une nouvelle attraction -->
            <a href="index.php?action=admin_create" class="bouton" style="background: var(--vert); color: var(--noir); margin-top: 0;">+ Ajouter une attraction</a>
        </div>
    </div>

    <!-- Tableau de gestion CRUD -->
    <div class="table-responsive" style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 1.5rem; overflow-x: auto;">
        <table class="crud-table" style="width: 100%; border-collapse: collapse; text-align: left; font-family: var(--police-texte);">
            <thead>
                <tr style="border-bottom: 4px solid var(--noir); background: var(--cyan);">
                    <th style="padding: 0.8rem; border: 2px solid var(--noir);">ID</th>
                    <th style="padding: 0.8rem; border: 2px solid var(--noir);">Nom</th>
                    <th style="padding: 0.8rem; border: 2px solid var(--noir);">Univers</th>
                    <th style="padding: 0.8rem; border: 2px solid var(--noir);">Taille Min</th>
                    <th style="padding: 0.8rem; border: 2px solid var(--noir);">Statut</th>
                    <th style="padding: 0.8rem; border: 2px solid var(--noir);">Attente</th>
                    <th style="padding: 0.8rem; border: 2px solid var(--noir); text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attractions)): ?>
                    <?php foreach ($attractions as $a): ?>
                    <tr style="border-bottom: 2px solid var(--noir);">
                        <td style="padding: 0.8rem; border: 2px solid var(--noir); font-weight: bold;"><?= $a['id'] ?></td>
                        <td style="padding: 0.8rem; border: 2px solid var(--noir); font-weight: bold;"><?= htmlspecialchars($a['nom']) ?></td>
                        <td style="padding: 0.8rem; border: 2px solid var(--noir);"><?= htmlspecialchars($a['univers_nom']) ?></td>
                        <td style="padding: 0.8rem; border: 2px solid var(--noir);"><?= htmlspecialchars($a['taille_minimale']) ?> cm</td>
                        <td style="padding: 0.8rem; border: 2px solid var(--noir);">
                            <span style="color: <?= $a['statut'] == 'Ouvert' ? 'var(--vert)' : ($a['statut'] == 'Maintenance' ? 'var(--orange)' : 'var(--rose)') ?>; font-weight: 700;">
                                <?= htmlspecialchars($a['statut']) ?>
                            </span>
                        </td>
                        <td style="padding: 0.8rem; border: 2px solid var(--noir); font-weight: bold; color: var(--violet);">
                            <?= htmlspecialchars($a['attente']) ?> min
                        </td>                        
                        <td style="padding: 0.8rem; border: 2px solid var(--noir); text-align: center;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <!-- Bouton Modifier -->
                                <a href="index.php?action=admin_edit&id=<?= $a['id'] ?>" style="background: var(--cyan); color: var(--noir); padding: 0.3rem 0.6rem; border: 2px solid var(--noir); font-weight: 700; font-size: 0.85rem;">Modifier</a>
                                <!-- Bouton Supprimer -->
                                <a href="index.php?action=admin_delete&id=<?= $a['id'] ?>" onclick="return confirm('Es-tu sûr de vouloir supprimer cette attraction ?');" style="background: var(--rose); color: #fff; padding: 0.3rem 0.6rem; border: 2px solid var(--noir); font-weight: 700; font-size: 0.85rem;">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; font-weight: 700; color: var(--violet);">Aucune attraction enregistrée dans la base de données.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require 'views/base.php';
?>