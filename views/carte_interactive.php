<?php 
/**
 * Vue : Plan Interactif du Parc
 * Permet aux visiteurs de visualiser le plan de Studio Toon et de cliquer sur les épingles
 * pour afficher les informations en direct de chaque attraction.
 */

$pageTitle = "Plan du Parc - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/carte_interactive.css">
<!-- En-tête de la page Plan Interactif -->
<div id="entete-plan-interactif" class="page-entete">
    <h1>🗺️ Plan Interactif de Studio Toon</h1>
    <p class="page-entete__intro">Clique sur les repères du plan pour découvrir les attractions en direct !</p>
</div>

<div id="conteneur-plan-interactif">

    <!-- Conteneur de la Carte -->
    <div id="carte-plan-container">
    
        <!-- Image du plan du parc -->
        <img 
            src="static/images/plan_parc_vue_de_haut.png" 
            alt="Plan du Parc Studio Toon" 
            id="image-plan-parc"
        >

        <!-- Épingle 1 : La Falaise -->
        <div onclick="ouvrirFicheParId(1)" class="epingle-carte epingle-falaise" title="La Falaise">
            <div class="epingle-numero">1</div>
        </div>

        <!-- Épingle 2 : Le Canon de Cirque -->
        <div onclick="ouvrirFicheParId(3)" class="epingle-carte epingle-canon" title="Le Canon de Cirque">
            <div class="epingle-numero">2</div>
        </div>

        <!-- Épingle 3 : Le Trou Portable -->
        <div onclick="ouvrirFicheParId(2)" class="epingle-carte epingle-trou-portable" title="Le Trou Portable">
            <div class="epingle-numero">3</div>
        </div>

        <!-- Épingle 4 : L'Enclume Express -->
        <div onclick="ouvrirFicheParId(4)" class="epingle-carte epingle-enclume" title="L'Enclume Express">
            <div class="epingle-numero">4</div>
        </div>

    </div>

    <!-- Panneau d'information dynamique (masqué par défaut) -->
    <div id="panneau-infos" class="panneau-infos-cache">
        <button type="button" onclick="fermerPanneau()" id="bouton-fermer-panneau">
            ✕ FERMER
        </button>
        
        <div id="contenu-infos">
            <!-- Le contenu de la fiche attraction sera injecté dynamiquement ici via JavaScript -->
        </div>
    </div>

</div>

<!-- Injection sécurisée des données de la BDD vers JavaScript -->
<script>
const attractionsDB = <?= json_encode($attractions ?? []); ?>;

function ouvrirFicheParId(id) {
    // Recherche l'attraction correspondante dans le tableau PHP converti en JS
    const att = attractionsDB.find(a => a.id == id);
    if (!att) return;

    const panneau = document.getElementById('panneau-infos');
    const contenu = document.getElementById('contenu-infos');
    
    const estFerme = att.statut === 'Ferme' || att.statut === 'Maintenance';
    const classeStatutBadge = estFerme ? 'badge-statut-ferme' : 'badge-statut-ouvert';
    const tempsAffichage = estFerme ? '—' : att.attente + ' min';

    contenu.innerHTML = `
        <div class="panneau-entete-flex">
            <img src="static/images/${att.image}" alt="${att.nom}" class="panneau-image-attraction">
            <div class="panneau-infos-texte">
                <span class="badge-univers">Univers : ${att.univers_nom || 'Studio Toon'}</span>
                <h2 class="panneau-titre-attraction">${att.nom}</h2>
                <p class="panneau-gag">🎬 Le Gag : "${att.gag}"</p>
            </div>
        </div>
        <hr class="separateur-tiret">
        <div class="panneau-grille-criteres">
            <div class="critere-carte">
                ⏱️ Temps d'attente : <span class="texte-attente-valeur">${tempsAffichage}</span>
            </div>
            <div class="critere-carte">
                📏 Taille mini : ${att.taille_minimale} m
            </div>
            <div class="critere-carte ${classeStatutBadge}">
                🟢 Statut : ${att.statut}
            </div>
        </div>
        <div class="panneau-description-bloc">
            📐 <strong>Description :</strong> ${att.description}
        </div>
        <div class="panneau-action-container">
            <a href="index.php?action=detail&id=${att.id}" class="bouton-voir-fiche">
                Voir la fiche complète →
            </a>
        </div>
    `;
    
    panneau.style.display = 'block';
    panneau.scrollIntoView({ behavior: 'smooth' });
}

function fermerPanneau() {
    document.getElementById('panneau-infos').style.display = 'none';
}
</script>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>