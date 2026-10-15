<?php 
$pageTitle = "Billetterie - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 750px; margin: 0 auto 2rem auto;">
    <h1>🎟️ Billetterie Officielle</h1>
    <p class="page-entete__intro">Réserve tes billets pour Studio Toon. Choisis ta date et tes tarifs !</p>
</div>

<?php if (isset($erreur)): ?>
    <div style="max-width: 750px; margin: 0 auto 1.5rem auto; background: var(--rose); color: #fff; font-weight: bold; padding: 1rem; border: 3px solid var(--noir); box-shadow: 5px 5px 0 var(--noir);">
        ⚠️ <?= htmlspecialchars($erreur) ?>
    </div>
<?php endif; ?>

<div style="max-width: 750px; margin: 0 auto; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
    <form action="index.php?action=billetterie_post" method="POST" style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Choix de la date -->
        <div>
            <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 1rem;">1. Choisissez la date de votre visite :</label>
            <input type="date" name="date_visite" id="date_visite" min="<?= $today ?>" value="<?= $today ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 700; font-size: 1.1rem; background: var(--cyan);">
        </div>

        <!-- Choix des billets -->
        <div>
            <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 1rem; font-size: 1rem;">2. Choisissez le nombre de billets :</label>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($tarifs as $t): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; border: 3px solid var(--noir); padding: 1rem; box-shadow: 4px 4px 0 var(--noir); flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <div style="font-weight: 800; font-size: 1.1rem;"><?= htmlspecialchars($t['libelle']) ?></div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--violet);"><?= htmlspecialchars($t['description']) ?></div>
                        <div style="font-weight: 800; color: var(--rose); margin-top: 0.2rem;"><?= number_format($t['prix'], 2, ',', ' ') ?> €</div>
                    </div>
                    
                    <!-- Sélecteur personnalisé avec boutons et affichage direct du chiffre -->
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <!-- Bouton - -->
                        <button type="button" onclick="modifierQuantite(<?= $t['id'] ?>, -1)" style="background: var(--rose); color: #fff; width: 40px; height: 40px; border: 3px solid var(--noir); font-weight: 900; font-size: 1.3rem; cursor: pointer; box-shadow: 2px 2px 0 var(--noir); display: flex; align-items: center; justify-content: center;">-</button>
                        
                        <!-- Affichage visuel du chiffre -->
                        <div id="affichage_qte_<?= $t['id'] ?>" style="width: 50px; height: 40px; display: flex; align-items: center; justify-content: center; background: #fff; border: 3px solid var(--noir); font-weight: 900; font-size: 1.2rem;">
                            0
                        </div>

                        <!-- Champ caché pour envoyer la valeur au backend via POST -->
                        <input type="hidden" 
                               name="quantites[<?= $t['id'] ?>]" 
                               id="qte_<?= $t['id'] ?>"
                               class="input-quantite" 
                               data-prix="<?= $t['prix'] ?>" 
                               value="0">
                               
                        <!-- Bouton + -->
                        <button type="button" onclick="modifierQuantite(<?= $t['id'] ?>, 1)" style="background: var(--vert); color: var(--noir); width: 40px; height: 40px; border: 3px solid var(--noir); font-weight: 900; font-size: 1.3rem; cursor: pointer; box-shadow: 2px 2px 0 var(--noir); display: flex; align-items: center; justify-content: center;">+</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Encadré total -->
        <div style="background: var(--noir); color: #fff; padding: 1.2rem; border: 3px solid var(--noir); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="font-weight: 700; text-transform: uppercase;">Total à payer :</span>
                <span id="affichage-total" style="font-family: var(--police-titre); font-size: 1.8rem; color: var(--vert); display: block;">0,00 €</span>
            </div>
            <button type="submit" class="bouton" style="background: var(--rose); color: #fff; margin-top: 0; padding: 0.8rem 1.5rem; font-size: 1rem; cursor: pointer;">
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
</script>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>