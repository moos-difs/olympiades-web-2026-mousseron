<?php 
/**
 * Vue : Temps d'attente en Live
 * Affiche les temps d'attente des attractions en temps réel avec des animations de variation
 * alimentées par WebSocket et appels AJAX.
 */

$pageTitle = "Temps d'attente en Live - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/attractions_live.css">
<!-- En-tête de la page Live -->
<div id="entete-live" class="page-entete">
    <h1 class="titre-live">⏱️ Live du Parc</h1>
    <p class="page-entete__intro intro-live">Temps d'attente en temps réel avec mise à jour instantanée par WebSocket.</p>
    <div class="maj-container">
        Dernière mise à jour : <span id="derniere-maj" class="badge-maj">--:--:--</span>
    </div>
</div>

<!-- Grille des attractions en direct -->
<div class="liste-realisations" id="grille-live">
    <p class="vide">Chargement du live en cours...</p>
</div>

<!-- Script de gestion du Live (AJAX & WebSocket) -->
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
                const classeStatut = estFerme ? 'badge-statut-ferme-live' : 'badge-statut-ouvert-live';
                const texteAttente = estFerme ? '—' : att.attente + ' min';
                const valeurNumerique = estFerme ? 0 : att.attente;
                
                html += `
                    <article id="attraction-${att.id}" class="carte-attraction-live">
                        <div>
                            <div class="carte-live-entete">
                                <h3 class="carte-live-titre">${att.nom}</h3>
                                <span class="badge-statut ${classeStatut}">
                                    ${att.statut}
                                </span>
                            </div>
                        </div>
                        <div class="carte-live-corps">
                            <span class="libelle-attente-live">Temps d'attente</span>
                            <span class="valeur-attente" data-temps="${valeurNumerique}">
                                ${texteAttente}
                            </span>
                        </div>
                    </article>
                `;
            });

            grille.innerHTML = html;
        })
        .catch(err => {
            console.error('Erreur lors de la récupération du live :', err);
        });
}

// Chargement initial des cartes
fetchTempsLive();

// Connexion au WebSocket pour la mise à jour en direct ciblée + animations de couleur
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Connecté au serveur WebSocket en direct.');
};

ws.onmessage = function(event) {
    try {
        const data = JSON.parse(event.data);
        
        if (data.type === 'UPDATE_ATTRACTION') {
            let carte = document.getElementById('attraction-' + data.id);
            if (carte) {
                const estFerme = data.statut === 'Ferme' || data.statut === 'Maintenance';
                const texteAttente = estFerme ? '—' : data.attente + ' min';
                const nouveauTempsNum = estFerme ? 0 : parseInt(data.attente);
                
                const elementValeur = carte.querySelector('.valeur-attente');
                const ancienTempsNum = parseInt(elementValeur.getAttribute('data-temps') || 0);
                
                // Met à jour le texte et l'attribut stocké
                elementValeur.textContent = texteAttente;
                elementValeur.setAttribute('data-temps', nouveauTempsNum);
                
                // Gestion des animations de couleur (Vert / Rouge) via les classes CSS isolées
                elementValeur.classList.remove('temps-hausse', 'temps-baisse');
                void elementValeur.offsetWidth; // Force le reflow CSS pour relancer l'animation
                
                if (!estFerme) {
                    if (nouveauTempsNum > ancienTempsNum) {
                        elementValeur.classList.add('temps-hausse'); // Flash rouge si hausse
                    } else if (nouveauTempsNum < ancienTempsNum) {
                        elementValeur.classList.add('temps-baisse'); // Flash vert si baisse
                    }
                }
                
                // Actualisation du badge statut
                const badgeStatut = carte.querySelector('.badge-statut');
                badgeStatut.textContent = data.statut;
                badgeStatut.className = estFerme ? 'badge-statut badge-statut-ferme-live' : 'badge-statut badge-statut-ouvert-live';
                
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