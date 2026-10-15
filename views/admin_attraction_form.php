<?php 
// Détermine si on est en mode modification ou en mode ajout
$isEdit = isset($attraction);
$pageTitle = $isEdit ? "Modifier : " . htmlspecialchars($attraction['nom']) : "Ajouter une attraction - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 700px; margin: 0 auto 2rem auto;">
    <div class="fiche__fil-ariane" style="margin-bottom: 1rem;">
        <a href="index.php?action=admin_attractions">← Retour à la gestion admin</a>
    </div>
    
    <h1><?= $isEdit ? "🛠️ Modifier l'attraction" : "✨ Ajouter une attraction" ?></h1>
    <p class="page-entete__intro">Remplis les informations ci-dessous pour les bases de données du parc.</p>
</div>

<!-- Formulaire dans une carte néo-brutaliste -->
<div style="max-width: 700px; margin: 0 auto; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2.5rem;">
    
    <form action="index.php?action=<?= $isEdit ? 'admin_update_post&id=' . $attraction['id'] : 'admin_create_post' ?>" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem; font-family: var(--police-texte);">
        
        <!-- Nom -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Nom de l'attraction :</label>
            <input type="text" name="nom" value="<?= $isEdit ? htmlspecialchars($attraction['nom']) : '' ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
        </div>

        <!-- Univers -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Univers du Parc :</label>
            <select name="univers_id" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: var(--cyan);">
                <option value="1" <?= ($isEdit && $attraction['univers_id'] == 1) ? 'selected' : '' ?>>Studio 2D</option>
                <option value="2" <?= ($isEdit && $attraction['univers_id'] == 2) ? 'selected' : '' ?>>Le Chapiteau</option>
            </select>
        </div>

        <!-- Image (Nom du fichier) -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Image (Nom du fichier ex: falaise.jpg) :</label>
            <input type="text" name="image" value="<?= $isEdit ? htmlspecialchars($attraction['image']) : '' ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
        </div>

        <!-- Gag Cartoon -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Le Gag Cartoon :</label>
            <textarea name="gag" rows="2" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;"><?= $isEdit ? htmlspecialchars($attraction['gag']) : '' ?></textarea>
        </div>

        <!-- Description -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Description du décor :</label>
            <textarea name="description" rows="4" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;"><?= $isEdit ? htmlspecialchars($attraction['description']) : '' ?></textarea>
        </div>

        <!-- Caractéristiques techniques -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Caractéristiques techniques :</label>
            <input type="text" name="caracteristiques" placeholder="Ex: Hauteur : 65 m | Vitesse : ~100 km/h" value="<?= $isEdit ? htmlspecialchars($attraction['caracteristiques']) : '' ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
        </div>

        <!-- Attente estimée -->
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Temps d'attente :</label>
            <input type="text" name="attente" placeholder="Ex: 00:00:00" value="<?= $isEdit ? htmlspecialchars($attraction['attente']) : '' ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
        </div>

        <!-- Taille Minimale & Statut (sur deux colonnes) -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Taille min (cm) :</label>
                <input type="number" name="taille_minimale" value="<?= $isEdit ? htmlspecialchars($attraction['taille_minimale']) : '120' ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.9rem;">Statut :</label>
                <select name="statut" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: var(--vert);">
                    <option value="Ouvert" <?= ($isEdit && $attraction['statut'] == 'Ouvert') ? 'selected' : '' ?>>Ouvert</option>
                    <option value="Ferme" <?= ($isEdit && $attraction['statut'] == 'Ferme') ? 'selected' : '' ?>>Fermé</option>
                    <option value="Maintenance" <?= ($isEdit && $attraction['statut'] == 'Maintenance') ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="bouton" style="background: var(--rose); color: #fff; margin-top: 1rem; padding: 0.9rem; font-size: 1rem; cursor: pointer; text-align: center;">
            <?= $isEdit ? "Enregistrer les modifications →" : "Créer l'attraction →" ?>
        </button>

    </form>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>