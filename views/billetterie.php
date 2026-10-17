<?php 
/**
 * Vue : Billetterie Officielle
 * Permet de choisir une date de visite et de sélectionner le nombre de billets souhaités
 * avec calcul dynamique du prix total en JavaScript.
 */

$pageTitle = "Billetterie - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/billetterie.css">
<!-- En-tête de la page de billetterie -->
<div id="entete-billetterie" class="page-entete">
    <h1>🎟️ Billetterie Officielle</h1>
    <p class="page-entete__intro">Réserve tes billets pour Studio Toon. Choisis ta date et tes tarifs !</p>
</div>

<!-- Message d'erreur éventuel -->
<?php if (isset($erreur)): ?>
    <div id="alerte-erreur-billetterie">
        ⚠️ <?= htmlspecialchars($erreur) ?>
    </div>
<?php endif; ?>

<!-- Conteneur principal du formulaire de réservation -->
<div id="conteneur-billetterie">
    <form id="formulaire-billetterie" action="index.php?action=billetterie_post" method="POST">
        
        <!-- 1. Choix de la date -->
        <div class="groupe-champ-billetterie">
            <label for="date_visite" class="label-etape">1. Choisissez la date de votre visite :</label>
            <input 
                type="date" 
                name="date_visite" 
                id="date_visite" 
                min="<?= htmlspecialchars($today ?? '') ?>" 
                value="<?= htmlspecialchars($date_visite ?? ($today ?? '')) ?>" 
                onchange="changerDateVisite(this.value)" 
                required
            >
        </div>

        <!-- 2. Choix des billets -->
        <div class="groupe-champ-billetterie">
            <label class="label-etape">2. Choisissez le nombre de billets :</label>
            
            <div class="liste-tarifs">
                <?php if (!empty($tarifs)): ?>
                    <?php foreach ($tarifs as $t): ?>
                    <div class="carte-tarif">
                        <div class="tarif-infos">
                            <div class="tarif-libelle"><?= htmlspecialchars($t['libelle']) ?></div>
                            <div class="tarif-description"><?= htmlspecialchars($t['description']) ?></div>
                            <div class="tarif-prix"><?= number_format($t['prix'], 2, ',', ' ') ?> €</div>
                        </div>
                        
                        <!-- Sélecteur personnalisé avec boutons et affichage dynamique -->
                        <div class="selecteur-quantite-container">
                            <!-- Bouton - -->
                            <button type="button" onclick="modifierQuantite(<?= $t['id'] ?>, -1)" class="bouton-quantite bouton-moins">-</button>
                            
                            <!-- Affichage visuel du chiffre -->
                            <div id="affichage_qte_<?= $t['id'] ?>" class="affichage-chiffre-quantite">
                                0
                            </div>

                            <!-- Champ caché pour envoyer la valeur au backend via POST -->
                            <input 
                                type="hidden" 
                                name="quantites[<?= $t['id'] ?>]" 
                                id="qte_<?= $t['id'] ?>"
                                class="input-quantite" 
                                data-prix="<?= $t['prix'] ?>" 
                                value="0"
                            >
                                   
                            <!-- Bouton + -->
                            <button type="button" onclick="modifierQuantite(<?= $t['id'] ?>, 1)" class="bouton-quantite bouton-plus">+</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Encadré total -->
        <div id="encadre-total-panier">
            <div class="total-texte-container">
                <span class="total-libelle">Total à payer :</span>
                <span id="affichage-total" class="total-montant">0,00 €</span>
            </div>
            <button type="submit" id="bouton-valider-reservation" class="bouton">
                Valider la réservation →
            </button>
        </div>

    </form>
</div>

<!-- Script JS pour la gestion des boutons et du calcul -->
<script>
function modifierQuantite(id, delta) {
    const inputCache = document.getElementById('qte_' + id);
    const affichageChiffre = document.getElementById('affichage_qte_' + id);
    
    let val = parseInt(inputCache.value) || 0;
    val += delta;
    if (val < 0) val = 0;
    if (val > 10) val = 10; // Limite max de billets par type
    
    inputCache.value = val;
    affichageChiffre.textContent = val;
    
    calculerTotalFront();
}

function calculerTotalFront() {
    const inputs = document.querySelectorAll('.input-quantite');
    const affichageTotal = document.getElementById('affichage-total');
    let total = 0;
    
    inputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        const prix = parseFloat(input.getAttribute('data-prix')) || 0;
        total += qty * prix;
    });
    
    affichageTotal.textContent = total.toFixed(2).replace('.', ',') + ' €';
}

function changerDateVisite(dateStr) {
    // Recharge la page de la billetterie en passant la nouvelle date dans l'URL
    window.location.href = 'index.php?action=billetterie&date_visite=' + dateStr;
}
</script>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>