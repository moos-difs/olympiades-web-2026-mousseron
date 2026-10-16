<?php 
$pageTitle = "Plan du Parc - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 1000px; margin: 0 auto 2rem auto; background: var(--cyan); color: var(--noir); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem; text-align: center;">
    <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 2rem;">🗺️ Plan Interactif de Studio Toon</h1>
    <p class="page-entete__intro" style="font-weight: 700; margin-top: 0.5rem;">Clique sur les repères du plan pour découvrir les attractions en direct !</p>
</div>

<div style="max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">

    <!-- Conteneur de la Carte -->
    <div style="position: relative; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); background: #fff; overflow: hidden;">
    
        <!-- Image du plan -->
        <img src="static/images/plan_parc_vue_de_haut.png" alt="Plan du Parc Studio Toon" style="width: 100%; height: auto; display: block; object-fit: cover;">

        <!-- 1. La Falaise  -->
        <div onclick="ouvrirFicheParId(1)" class="epingle-carte" style="position: absolute; top: 29%; left: 25%; transform: translate(-50%, -50%); cursor: pointer; z-index: 10;">
            <div style="background: var(--rose); color: #fff; border: 3px solid var(--noir); width: 36px; height: 36px; border-radius: 50%; box-shadow: 4px 4px 0 var(--noir); display: flex; align-items: center; justify-content: center; font-family: 'Archivo Black', Impact, sans-serif; font-size: 1rem;">1</div>
        </div>

        <!-- 2. Le Canon de Cirque -->
        <div onclick="ouvrirFicheParId(3)" class="epingle-carte" style="position: absolute; top: 10%; left: 42%; transform: translate(-50%, -50%); cursor: pointer; z-index: 10;">
            <div style="background: var(--rose); color: #fff; border: 3px solid var(--noir); width: 36px; height: 36px; border-radius: 50%; box-shadow: 4px 4px 0 var(--noir); display: flex; align-items: center; justify-content: center; font-family: 'Archivo Black', Impact, sans-serif; font-size: 1rem;">2</div>
        </div>

        <!-- 3. Le Trou Portable -->
        <div onclick="ouvrirFicheParId(2)" class="epingle-carte" style="position: absolute; top: 28%; left: 68%; transform: translate(-50%, -50%); cursor: pointer; z-index: 10;">
            <div style="background: var(--rose); color: #fff; border: 3px solid var(--noir); width: 36px; height: 36px; border-radius: 50%; box-shadow: 4px 4px 0 var(--noir); display: flex; align-items: center; justify-content: center; font-family: 'Archivo Black', Impact, sans-serif; font-size: 1rem;">3</div>
        </div>

        <!-- 4. L'Enclume Express -->
        <div onclick="ouvrirFicheParId(4)" class="epingle-carte" style="position: absolute; top: 45%; left: 78%; transform: translate(-50%, -50%); cursor: pointer; z-index: 10;">
            <div style="background: var(--rose); color: #fff; border: 3px solid var(--noir); width: 36px; height: 36px; border-radius: 50%; box-shadow: 4px 4px 0 var(--noir); display: flex; align-items: center; justify-content: center; font-family: 'Archivo Black', Impact, sans-serif; font-size: 1rem;">4</div>
        </div>

    </div>

    <!-- Panneau d'information dynamique -->
    <div id="panneau-infos" style="display: none; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem; position: relative;">
        <button onclick="fermerPanneau()" style="position: absolute; top: 15px; right: 15px; background: var(--rose); color: #fff; border: 2px solid var(--noir); font-weight: 900; padding: 5px 10px; cursor: pointer; box-shadow: 2px 2px 0 var(--noir);">✕ FERMER</button>
        
        <div id="contenu-infos">
            <!-- Injecté dynamiquement -->
        </div>
    </div>

</div>

<style>
.epingle-carte {
    transition: transform 0.2s ease;
}
.epingle-carte:hover {
    transform: translate(-50%, -50%) scale(1.15);
}
</style>

<!-- Injection sécurisée des données de la BDD vers JavaScript -->
<script>
const attractionsDB = <?= json_encode($attractions); ?>;

function ouvrirFicheParId(id) {
    // On cherche l'attraction correspondante dans le tableau PHP converti en JS
    const att = attractionsDB.find(a => a.id == id);
    if (!att) return;

    const panneau = document.getElementById('panneau-infos');
    const contenu = document.getElementById('contenu-infos');
    
    const estFerme = att.statut === 'Ferme' || att.statut === 'Maintenance';
    const couleurStatutBadge = estFerme ? 'var(--rose)' : 'var(--vert)';
    const tempsAffichage = estFerme ? '—' : att.attente + ' min';

    contenu.innerHTML = `
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: center;">
            <img src="static/images/${att.image}" alt="${att.nom}" style="width: 220px; height: 140px; object-fit: cover; border: 3px solid var(--noir); box-shadow: 4px 4px 0 var(--noir);">
            <div style="flex: 1;">
                <span style="background: var(--violet); color: #fff; font-size: 0.75rem; font-weight: 900; padding: 3px 6px; text-transform: uppercase; border: 2px solid var(--noir);">Univers : ${att.univers_nom || 'Studio Toon'}</span>
                <h2 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0.5rem 0; font-size: 1.8rem; text-transform: uppercase; color: var(--noir);">${att.nom}</h2>
                <p style="font-weight: 700; color: var(--violet); margin: 0 0 0.5rem 0;">🎬 Le Gag : "${att.gag}"</p>
            </div>
        </div>
        <hr style="border: 0; border-top: 3px dashed var(--noir); margin: 1.5rem 0;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-weight: 800;">
            <div style="background: #fff; border: 2px solid var(--noir); padding: 0.8rem; box-shadow: 3px 3px 0 var(--noir);">
                ⏱️ Temps d'attente : <span style="color: var(--orange);">${tempsAffichage}</span>
            </div>
            <div style="background: #fff; border: 2px solid var(--noir); padding: 0.8rem; box-shadow: 3px 3px 0 var(--noir);">
                📏 Taille mini : ${att.taille_minimale} m
            </div>
            <div style="background: ${couleurStatutBadge}; border: 2px solid var(--noir); padding: 0.8rem; box-shadow: 3px 3px 0 var(--noir);">
                🟢 Statut : ${att.statut}
            </div>
        </div>
        <div style="margin-top: 1rem; background: #fff35c; border: 2px solid var(--noir); padding: 0.8rem; font-weight: 700; box-shadow: 3px 3px 0 var(--noir);">
            📐 <strong>Description :</strong> ${att.description}
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