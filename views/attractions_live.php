<?php 
$pageTitle = "Temps d'attente en Live - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="background: var(--cyan);">
    <h1>⏱️ Live du Parc</h1>
    <p class="page-entete__intro">Temps d'attente en temps réel. Mise à jour automatique toutes les 30 secondes.</p>
    <div style="margin-top: 1rem; font-weight: 800; font-size: 0.9rem;">
        Dernière mise à jour : <span id="derniere-maj" style="background: #fff; padding: 0.2rem 0.5rem; border: 2px solid var(--noir);">--:--:--</span>
    </div>
</div>

<div class="liste-realisations" id="grille-live">
    <!-- Les données seront injectées dynamiquement ici en JS -->
    <p class="vide">Chargement du live en cours...</p>
</div>

<script>
let anciensTemps = {};

function fetchTempsLive() {
    fetch('index.php?action=api_live')
        .then(response => response.json())
        .then(data => {
            document.getElementById('derniere-maj').textContent = data.derniere_maj;
            const grille = document.getElementById('grille-live');
            grille.innerHTML = '';

            data.attractions.forEach(a => {
                let nouveauTemps = parseInt(a.attente) || 0;
                let ancienTemps = anciensTemps[a.id] !== undefined ? anciensTemps[a.id] : nouveauTemps;
                
                let styleBadge = 'background: var(--vert); color: var(--noir);';
                let contenuAffichage = '';

                // Vérification du statut : si Fermé ou en Maintenance, on affiche INDISPONIBLE avec une croix rouge
                if (a.statut === 'Ferme' || a.statut === 'Maintenance') {
                    styleBadge = 'background: var(--rose); color: #fff;';
                    contenuAffichage = 'INDISPONIBLE';
                } else {
                    let fleche = '';
                    if (nouveauTemps > ancienTemps) {
                        styleBadge = 'background: var(--rose); color: #fff;';
                        fleche = ' 📈 (↑)';
                    } else if (nouveauTemps < ancienTemps) {
                        styleBadge = 'background: var(--vert); color: var(--noir);';
                        fleche = ' 📉 (↓)';
                    }
                    contenuAffichage = `⏳ ${nouveauTemps} minutes${fleche}`;
                }

                anciensTemps[a.id] = nouveauTemps;

                // Création de la carte
                const carte = document.createElement('div');
                carte.className = 'carte-realisation';
                carte.innerHTML = `
                    <h3>${a.nom}</h3>
                    <p class="carte-realisation__meta">Statut : <strong>${a.statut}</strong></p>
                    <div style="font-size: 1.2rem; font-weight: 900; padding: 0.6rem 1rem; border: 3px solid var(--noir); display: inline-block; ${styleBadge}">
                        ${contenuAffichage}
                    </div>
                `;
                grille.appendChild(carte);
            });
        })
        .catch(err => console.error("Erreur lors du chargement du live :", err));
}

// Lancement immédiat puis toutes les 30 secondes
fetchTempsLive();
setInterval(fetchTempsLive, 30000);
</script>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>