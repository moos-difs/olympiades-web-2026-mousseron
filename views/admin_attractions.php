<?php 
/**
 * Vue : Gestion Admin des Attractions (CRUD)
 * Interface administrateur permettant d'ajouter, modifier ou supprimer les attractions du parc.
 */

$pageTitle = "Administration des Attractions - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/admin_attractions.css">
<?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === "admin") : ?>

    <!-- En-tête de la page d'administration -->
    <div class="page-entete">
        <div class="entete-admin-attractions-flex">
            <div>
                <h1>🛠️ Gestion Admin</h1>
                <p class="page-entete__intro intro-marge-nulle">Ajoutez, modifiez ou supprimez les attractions du parc.</p>
            </div>
            <!-- Bouton pour créer une nouvelle attraction -->
            <a href="index.php?action=admin_create" class="bouton bouton-vert-admin bouton-marge-nulle">
                + Ajouter une attraction
            </a>
        </div>
    </div>

    <!-- Tableau de gestion CRUD -->
    <div class="table-responsive conteneur-tableau-crud">
        <table class="crud-table table-attractions-admin">
            <thead>
                <tr class="entete-tableau-cyan">
                    <th class="cellule-tableau">ID</th>
                    <th class="cellule-tableau">Nom</th>
                    <th class="cellule-tableau">Univers</th>
                    <th class="cellule-tableau">Taille Min</th>
                    <th class="cellule-tableau">Statut</th>
                    <th class="cellule-tableau">Attente</th>
                    <th class="cellule-tableau text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($attractions)): ?>
                    <?php foreach ($attractions as $a): ?>
                    <tr class="ligne-tableau-crud">
                        <td class="cellule-tableau poids-gras"><?= htmlspecialchars($a['id'] ?? '') ?></td>
                        <td class="cellule-tableau poids-gras"><?= htmlspecialchars($a['nom'] ?? '') ?></td>
                        <td class="cellule-tableau"><?= htmlspecialchars($a['univers_nom'] ?? '') ?></td>
                        <td class="cellule-tableau"><?= htmlspecialchars($a['taille_minimale'] ?? '') ?> cm</td>
                        <td class="cellule-tableau">
                            <?php 
                                $statut = $a['statut'] ?? 'Inconnu';
                                $classeStatut = 'statut-admin-ouvert';
                                if ($statut === 'Maintenance') $classeStatut = 'statut-admin-maintenance';
                                elseif ($statut === 'Ferme') $classeStatut = 'statut-admin-ferme';
                            ?>
                            <span class="badge-statut-admin <?= $classeStatut ?>">
                                <?= htmlspecialchars($statut) ?>
                            </span>
                        </td>
                        <td class="cellule-tableau poids-gras texte-violet">
                            <?= htmlspecialchars($a['attente'] ?? '0') ?> min
                        </td>                       
                        <td class="cellule-tableau text-center">
                            <div class="actions-ligne-crud">
                                <!-- Bouton Modifier -->
                                <a href="index.php?action=admin_edit&id=<?= htmlspecialchars($a['id'] ?? '') ?>" class="bouton-action-crud bouton-modifier-crud">
                                    Modifier
                                </a>
                                <!-- Bouton Supprimer -->
                                <a href="index.php?action=admin_delete&id=<?= htmlspecialchars($a['id'] ?? '') ?>" onclick="return confirm('Es-tu sûr de vouloir supprimer cette attraction ?');" class="bouton-action-crud bouton-supprimer-crud">
                                    Supprimer
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="cellule-tableau message-vide-admin">
                            Aucune attraction enregistrée dans la base de données.
                        </td>
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