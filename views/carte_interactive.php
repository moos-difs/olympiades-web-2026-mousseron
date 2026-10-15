<?php 
$pageTitle = "Plan du Parc - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 1000px; margin: 0 auto 2rem auto; background: var(--cyan); color: var(--noir); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem; text-align: center;">
    <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 2rem;">🗺️ Plan Interactif de Studio Toon</h1>
    <p class="page-entete__intro" style="font-weight: 700; margin-top: 0.5rem;">Clique sur les épingles de la carte pour découvrir les attractions, leurs caractéristiques et leurs temps d'attente !</p>
</div>

<div style="max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">

    <!-- Conteneur de la Carte -->
    <div style="position: relative; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); background: #fff; overflow: hidden;">
    
    <!-- Ton nouveau plan vu de haut -->
    <img src="static/images/plan_parc_vue_de_haut.png" alt="Plan du Parc Vu de Haut" style="width: 100%; height: auto; display: block; object-fit: cover;">

    <!-- 1. La Falaise (Haut-gauche) -->
    <div onclick="ouvrirFicheAttraction({id: 1, nom: 'La Falaise', univers: 'Studio 2D / Sensation', image: 'falaise.jpg', gag: 'Le personnage court dans le vide, ne tombe pas immédiatement...', attente: '25', taille_minimale: '1,20', statut: 'Ouvert', infos: 'Hauteur : 65m | Vitesse : ~100 km/h'})" 
         style="position: absolute; top: 25%; left: 20%; transform: translate(-50%, -50%); cursor: pointer; text-align: center; z-index: 10;">
        <div style="background: var(--vert); border: 3px solid var(--noir); width: 32px; height: 32px; border-radius: 50%; box-shadow: 3px 3px 0 var(--noir); display: flex; align-items: center; justify-content: center;">📍</div>
        <span style="background: var(--noir); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 2px 6px; border: 1px solid var(--noir);">La Falaise</span>
    </div>

    <!-- 2. Le Trou Portable (Centre) -->
    <div onclick="ouvrirFicheAttraction({id: 2, nom: 'Le Trou Portable', univers: 'Le Chapiteau / Famille', image: 'trou_portable.jpg', gag: 'Un simple trou peint sur un mur que l’on traverse...', attente: '15', taille_minimale: '0,90', statut: 'Ouvert', infos: 'Durée : 6 min'})" 
         style="position: absolute; top: 50%; left: 45%; transform: translate(-50%, -50%); cursor: pointer; text-align: center; z-index: 10;">
        <div style="background: var(--vert); border: 3px solid var(--noir); width: 32px; height: 32px; border-radius: 50%; box-shadow: 3px 3px 0 var(--noir); display: flex; align-items: center; justify-content: center;">📍</div>
        <span style="background: var(--noir); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 2px 6px; border: 1px solid var(--noir);">Le Trou Portable</span>
    </div>

    <!-- 3. Le Canon de Cirque (Droite / Chapiteau) -->
    <div onclick="ouvrirFicheAttraction({id: 3, nom: 'Le Canon de Cirque', univers: 'Le Chapiteau / Sensation', image: 'canon_cirque.jpg', gag: 'Le train est propulsé hors d’un canon géant...', attente: '40', taille_minimale: '1,30', statut: 'Ouvert', infos: 'Vitesse : 0 à 110 km/h'})" 
         style="position: absolute; top: 40%; left: 75%; transform: translate(-50%, -50%); cursor: pointer; text-align: center; z-index: 10;">
        <div style="background: var(--orange); border: 3px solid var(--noir); width: 32px; height: 32px; border-radius: 50%; box-shadow: 3px 3px 0 var(--noir); display: flex; align-items: center; justify-content: center;">📍</div>
        <span style="background: var(--noir); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 2px 6px; border: 1px solid var(--noir);">Le Canon de Cirque</span>
    </div>

    <!-- 4. L'Enclume Express (Bas-droite / Forge) -->
    <div onclick="ouvrirFicheAttraction({id: 4, nom: 'L’Enclume Express', univers: 'Studio 2D / Aventure', image: 'entree_parc.png', gag: 'Une énorme enclume suspendue qui tombe toujours au bon moment...', attente: '20', taille_minimale: '1,10', statut: 'Ouvert', infos: 'Parcours scénique'})" 
         style="position: absolute; top: 75%; left: 80%; transform: translate(-50%, -50%); cursor: pointer; text-align: center; z-index: 10;">
        <div style="background: var(--vert); border: 3px solid var(--noir); width: 32px; height: 32px; border-radius: 50%; box-shadow: 3px 3px 0 var(--noir); display: flex; align-items: center; justify-content: center;">📍</div>
        <span style="background: var(--noir); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 2px 6px; border: 1px solid var(--noir);">L'Enclume Express</span>
    </div>

</div>

    <!-- Panneau d'information dynamique (Popup au clic sur une attraction) -->
    <div id="panneau-infos" style="display: none; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem; position: relative;">
        <button onclick="fermerPanneau()" style="position: absolute; top: 15px; right: 15px; background: var(--rose); color: #fff; border: 2px solid var(--noir); font-weight: 900; padding: 5px 10px; cursor: pointer; box-shadow: 2px 2px 0 var(--noir);">✕ FERMER</button>
        
        <div id="contenu-infos">
            <!-- Injecté dynamiquement par JavaScript -->
        </div>
    </div>

</div>

<!-- Script JS pour gérer l'affichage de la fiche au clic -->
<script>
function ouvrirFicheAttraction(att) {
    const panneau = document.getElementById('panneau-infos');
    const contenu = document.getElementById('contenu-infos');
    
    let couleurStatutBadge = (att.statut === 'Ouvert') ? 'var(--vert)' : 'var(--rose)';

    contenu.innerHTML = `
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: center;">
            <img src="static/images/${att.image}" alt="${att.nom}" style="width: 220px; height: 140px; object-fit: cover; border: 3px solid var(--noir); box-shadow: 4px 4px 0 var(--noir);">
            <div style="flex: 1;">
                <span style="background: var(--violet); color: #fff; font-size: 0.75rem; font-weight: 900; padding: 3px 6px; text-transform: uppercase; border: 2px solid var(--noir);">Univers : ${att.univers}</span>
                <h2 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0.5rem 0; font-size: 1.8rem; text-transform: uppercase; color: var(--noir);">${att.nom}</h2>
                <p style="font-weight: 700; color: var(--violet); margin: 0 0 0.5rem 0;">🎬 Le Gag : "${att.gag}"</p>
            </div>
        </div>
        <hr style="border: 0; border-top: 3px dashed var(--noir); margin: 1.5rem 0;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-weight: 800;">
            <div style="background: #fff; border: 2px solid var(--noir); padding: 0.8rem; box-shadow: 3px 3px 0 var(--noir);">
                ⏱️ Temps d'attente : <span style="color: var(--orange);">${att.attente} min</span>
            </div>
            <div style="background: #fff; border: 2px solid var(--noir); padding: 0.8rem; box-shadow: 3px 3px 0 var(--noir);">
                📏 Taille mini : ${att.taille_minimale} m
            </div>
            <div style="background: ${couleurStatutBadge}; border: 2px solid var(--noir); padding: 0.8rem; box-shadow: 3px 3px 0 var(--noir);">
                🟢 Statut : ${att.statut}
            </div>
        </div>
        <div style="margin-top: 1rem; background: #fff35c; border: 2px solid var(--noir); padding: 0.8rem; font-weight: 700; box-shadow: 3px 3px 0 var(--noir);">
            📐 <strong>Spécificités :</strong> ${att.infos}
        </div>
        <div style="margin-top: 1.5rem; text-align: right;">
            <a href="index.php?action=attraction_detail&id=${att.id}" style="background: var(--cyan); color: var(--noir); padding: 0.6rem 1.2rem; border: 3px solid var(--noir); font-weight: 900; text-decoration: none; box-shadow: 4px 4px 0 var(--noir); display: inline-block; text-transform: uppercase;">
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