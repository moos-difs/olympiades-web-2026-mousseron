<?php 
$pageTitle = "Temps d'attente en Live - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="background: var(--cyan); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem; margin-bottom: 2rem;">
    <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0;">⏱️ Live du Parc</h1>
    <p class="page-entete__intro" style="font-weight: 700; margin-top: 0.5rem;">Temps d'attente en temps réel avec mise à jour instantanée sans rechargement.</p>
    <div style="margin-top: 1rem; font-weight: 800; font-size: 0.9rem;">
        Dernière mise à jour : <span id="derniere-maj" style="background: #fff; padding: 0.2rem 0.5rem; border: 2px solid var(--noir);">--:--:--</span>
    </div>
</div>

<div class="liste-realisations" id="grille-live" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
    <p class="vide">Chargement du live en cours...</p>
</div>

<script>
// 1. Horloge en temps réel (met à jour l'heure chaque seconde)
setInterval(function() {
    const maintenant = new Date();
    document.getElementById('derniere-maj').textContent = maintenant.toLocaleTimeString();
}, 1000);

function fetchTempsLive() {
    fetch('index.php?action=api_live')
        .then(response => response.json())
        .then(data => {
            const grille = document.getElementById('grille-live');
            // Met à jour avec l'heure renvoyée par le serveur lors du fetch
            if (data.derniere_maj) {
                document.getElementById('derniere-maj').textContent = data.derniere_maj;
            }

            if (!data.attractions || data.attractions.length === 0) {
                grille.innerHTML = '<p class="vide">Aucune attraction disponible pour le moment.</p>';
                return;
            }

            let html = '';
            data.attractions.forEach(att => {
                const estFerme = att.statut === 'Ferme' || att.statut === 'Maintenance';
                const couleurStatut = estFerme ? 'background: #ff2ea6; color: #fff;' : 'background: #00ff9c; color: #101010;';
                const texteAttente = estFerme ? '—' : att.attente + ' min';
                
                html += `
                    <div id="attraction-${att.id}" style="background: #fff; border: 4px solid var(--noir); box-shadow: 6px 6px 0 var(--noir); padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                                <h3 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0; font-size: 1.2rem;">${att.nom}</h3>
                                <span class="badge-statut" style="font-size: 0.75rem; font-weight: 900; padding: 3px 8px; border: 2px solid var(--noir); text-transform: uppercase; ${couleurStatut}">
                                    ${att.statut}
                                </span>
                            </div>
                        </div>
                        <div style="margin-top: 1rem; border-top: 3px dashed var(--noir); padding-top: 0.8rem; text-align: center;">
                            <span style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; display: block; color: #555;">Temps d'attente</span>
                            <span class="valeur-attente" style="font-family: 'Archivo Black', Impact, sans-serif; font-size: 2rem; color: var(--violet, #7c3aff);">
                                ${texteAttente}
                            </span>
                        </div>
                    </div>
                `;
            });

            grille.innerHTML = html;
        })
        .catch(err => {
            console.error('Erreur lors de la récupération du live :', err);
        });
}

// Chargement initial des données au chargement de la page
fetchTempsLive();

// Connexion au serveur WebSocket pour intercepter les modifs admin instantanément
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Connecté au serveur WebSocket en direct.');
};

ws.onmessage = function(event) {
    try {
        const data = JSON.parse(event.data);
        
        if (data.type === 'UPDATE_ATTRACTION') {
            console.log("Mise à jour en direct reçue pour :", data.nom);
            
            let carte = document.getElementById('attraction-' + data.id);
            if (carte) {
                const estFerme = data.statut === 'Ferme' || data.statut === 'Maintenance';
                const texteAttente = estFerme ? '—' : data.attente + ' min';
                
                // Actualisation immédiate du temps et du statut sans recharger la page
                carte.querySelector('.valeur-attente').textContent = texteAttente;
                
                const badgeStatut = carte.querySelector('.badge-statut');
                badgeStatut.textContent = data.statut;
                if (estFerme) {
                    badgeStatut.style.background = '#ff2ea6';
                    badgeStatut.style.color = '#fff';
                } else {
                    badgeStatut.style.background = '#00ff9c';
                    badgeStatut.style.color = '#101010';
                }
                
                document.getElementById('derniere-maj').textContent = new Date().toLocaleTimeString();
            } else {
                fetchTempsLive();
            }
        }
    } catch (e) {
        if (event.data === "REFRESH_DATA") {
            fetchTempsLive();
        }
    }
};

ws.onerror = function(error) {
    console.error('Erreur WebSocket :', error);
};
</script>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>