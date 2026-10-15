<?php 
$pageTitle = "Paiement Sécurisé - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 600px; margin: 0 auto 2rem auto; background: var(--cyan);">
    <h1>🔒 Paiement Sécurisé</h1>
    <p class="page-entete__intro">Règlement de votre commande pour Studio Toon.</p>
</div>

<div style="max-width: 600px; margin: 0 auto; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2.5rem;">
    <div style="margin-bottom: 1.5rem; font-weight: 800; font-size: 1.2rem; background: var(--vert); padding: 1rem; border: 3px solid var(--noir); display: flex; justify-content: space-between;">
        <span>Montant total :</span>
        <span style="color: var(--rose);"><?= number_format($panier['prix_total'], 2, ',', ' ') ?> €</span>
    </div>

    <form action="index.php?action=valider_paiement" method="POST" style="display: flex; flex-direction: column; gap: 1.2rem;">
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.3rem; text-transform: uppercase; font-size: 0.9rem;">Nom sur la carte :</label>
            <input type="text" placeholder="EX: JULES PEPIN" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
        </div>
        <div>
            <label style="display: block; font-weight: 700; margin-bottom: 0.3rem; text-transform: uppercase; font-size: 0.9rem;">Numéro de carte bancaire :</label>
            <input type="text" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label style="display: block; font-weight: 700; margin-bottom: 0.3rem; text-transform: uppercase; font-size: 0.9rem;">Expiration :</label>
                <input type="text" placeholder="MM/AA" maxlength="5" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; margin-bottom: 0.3rem; text-transform: uppercase; font-size: 0.9rem;">CVV :</label>
                <input type="password" placeholder="123" maxlength="4" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 600; background: #fff;">
            </div>
        </div>

        <button type="submit" class="bouton" style="background: var(--rose); color: #fff; margin-top: 1rem; padding: 1rem; font-size: 1.1rem; cursor: pointer; text-align: center;">
            Payer et valider la commande →
        </button>
    </form>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>