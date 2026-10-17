<?php 
/**
 * Vue : Formulaire d'Ajout / Modification d'une Attraction (Admin)
 * Permet aux administrateurs de créer une nouvelle attraction ou d'éditer une attraction existante.
 */

$isEdit = isset($attraction);
$pageTitle = $isEdit ? "Modifier : " . htmlspecialchars($attraction['nom'] ?? '') : "Ajouter une attraction - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/admin_attraction_form.css">
<!-- En-tête de la page de formulaire -->
<div id="entete-form-attraction" class="page-entete">
    <div class="fiche__fil-ariane">
        <a href="index.php?action=admin_attractions" class="lien-retour-admin">← Retour à la gestion admin</a>
    </div>
    
    <h1><?= $isEdit ? "🛠️ Modifier l'attraction" : "✨ Ajouter une attraction" ?></h1>
    <p class="page-entete__intro">Remplis les informations ci-dessous pour les bases de données du parc.</p>
</div>

<!-- Conteneur du Formulaire (Carte Néo-Brutaliste) -->
<div id="conteneur-form-attraction">
    
    <form 
        id="formulaire-attraction-admin" 
        action="index.php?action=<?= $isEdit ? 'admin_update_post&id=' . htmlspecialchars($attraction['id'] ?? '') : 'admin_create_post' ?>" 
        method="POST" 
        enctype="multipart/form-data"
    >
        
        <!-- Nom -->
        <div class="champ-formulaire-admin">
            <label for="champ-nom-attraction">Nom de l'attraction :</label>
            <input 
                type="text" 
                id="champ-nom-attraction" 
                name="nom" 
                value="<?= $isEdit ? htmlspecialchars($attraction['nom'] ?? '') : '' ?>" 
                required
            >
        </div>

        <!-- Univers -->
        <div class="champ-formulaire-admin">
            <label for="champ-univers-attraction">Univers du Parc :</label>
            <select id="champ-univers-attraction" name="univers_id" required class="select-univers-admin">
                <option value="1" <?= ($isEdit && ($attraction['univers_id'] ?? '') == 1) ? 'selected' : '' ?>>Studio 2D</option>
                <option value="2" <?= ($isEdit && ($attraction['univers_id'] ?? '') == 2) ? 'selected' : '' ?>>Le Chapiteau</option>
            </select>
        </div>

        <!-- Image (Nom du fichier) -->
        <div class="champ-formulaire-admin">
            <label for="champ-image-attraction">Image (Nom du fichier ex: falaise.jpg) :</label>
            <input 
                type="text" 
                id="champ-image-attraction" 
                name="image" 
                value="<?= $isEdit ? htmlspecialchars($attraction['image'] ?? '') : '' ?>" 
                required
            >
        </div>

        <!-- Gag Cartoon -->
        <div class="champ-formulaire-admin">
            <label for="champ-gag-attraction">Le Gag Cartoon :</label>
            <textarea 
                id="champ-gag-attraction" 
                name="gag" 
                rows="2" 
                required
            ><?= $isEdit ? htmlspecialchars($attraction['gag'] ?? '') : '' ?></textarea>
        </div>

        <!-- Description -->
        <div class="champ-formulaire-admin">
            <label for="champ-description-attraction">Description du décor :</label>
            <textarea 
                id="champ-description-attraction" 
                name="description" 
                rows="4" 
                required
            ><?= $isEdit ? htmlspecialchars($attraction['description'] ?? '') : '' ?></textarea>
        </div>

        <!-- Caractéristiques techniques -->
        <div class="champ-formulaire-admin">
            <label for="champ-caracteristiques-attraction">Caractéristiques techniques :</label>
            <input 
                type="text" 
                id="champ-caracteristiques-attraction" 
                name="caracteristiques" 
                placeholder="Ex: Hauteur : 65 m | Vitesse : ~100 km/h" 
                value="<?= $isEdit ? htmlspecialchars($attraction['caracteristiques'] ?? '') : '' ?>" 
                required
            >
        </div>

        <!-- Attente estimée -->
        <div class="champ-formulaire-admin">
            <label for="champ-attente-attraction">Temps d'attente :</label>
            <input 
                type="text" 
                id="champ-attente-attraction" 
                name="attente" 
                placeholder="Ex: 00:00:00" 
                value="<?= $isEdit ? htmlspecialchars($attraction['attente'] ?? '') : '' ?>" 
                required
            >
        </div>

        <!-- Taille Minimale & Statut (Grille 2 colonnes) -->
        <div class="grille-champs-double">
            <div class="champ-formulaire-admin">
                <label for="champ-taille-attraction">Taille min (cm) :</label>
                <input 
                    type="number" 
                    id="champ-taille-attraction" 
                    name="taille_minimale" 
                    value="<?= $isEdit ? htmlspecialchars($attraction['taille_minimale'] ?? '120') : '120' ?>" 
                    required
                >
            </div>
            <div class="champ-formulaire-admin">
                <label for="champ-statut-attraction">Statut :</label>
                <select id="champ-statut-attraction" name="statut" required class="select-statut-admin">
                    <option value="Ouvert" <?= ($isEdit && ($attraction['statut'] ?? '') == 'Ouvert') ? 'selected' : '' ?>>Ouvert</option>
                    <option value="Ferme" <?= ($isEdit && ($attraction['statut'] ?? '') == 'Ferme') ? 'selected' : '' ?>>Fermé</option>
                    <option value="Maintenance" <?= ($isEdit && ($attraction['statut'] ?? '') == 'Maintenance') ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" id="bouton-soumettre-attraction" class="bouton">
            <?= $isEdit ? "Enregistrer les modifications →" : "Créer l'attraction →" ?>
        </button>

    </form>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>