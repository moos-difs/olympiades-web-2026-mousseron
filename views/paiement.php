<?php 
/**
 * Vue : Paiement Sécurisé
 * Permet à l'utilisateur de saisir ses coordonnées bancaires pour régler sa commande.
 */

$pageTitle = "Paiement Sécurisé - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/paiement.css">
<!-- En-tête de la page de paiement -->
<div id="entete-paiement" class="page-entete">
    <h1>🔒 Paiement Sécurisé</h1>
    <p class="page-entete__intro">Règlement de votre commande pour Studio Toon.</p>
</div>

<!-- Conteneur principal du formulaire de paiement -->
<div id="conteneur-paiement">

    <!-- Récapitulatif du montant total -->
    <div id="recap-montant-total">
        <span>Montant total :</span>
        <span class="prix-total-valeur">
            <?= number_format($panier['prix_total'] ?? 0, 2, ',', ' ') ?> €
        </span>
    </div>

    <!-- Formulaire de saisie des coordonnées bancaires -->
    <form id="formulaire-paiement" action="index.php?action=valider_paiement" method="POST">
        
        <div class="champ-formulaire">
            <label for="champ-nom-carte">Nom sur la carte :</label>
            <input 
                type="text" 
                id="champ-nom-carte" 
                name="nom_carte"
                placeholder="EX: JULES PEPIN" 
                required 
                autocomplete="cc-name"
            >
        </div>

        <div class="champ-formulaire">
            <label for="champ-numero-carte">Numéro de carte bancaire :</label>
            <input 
                type="text" 
                id="champ-numero-carte" 
                name="numero_carte"
                placeholder="XXXX XXXX XXXX XXXX" 
                maxlength="19" 
                required 
                autocomplete="cc-number"
            >
        </div>

        <div class="grille-champs-carte">
            <div class="champ-formulaire">
                <label for="champ-expiration-carte">Expiration :</label>
                <input 
                    type="text" 
                    id="champ-expiration-carte" 
                    name="expiration_carte"
                    placeholder="MM/AA" 
                    maxlength="5" 
                    required 
                    autocomplete="cc-exp"
                >
            </div>
            <div class="champ-formulaire">
                <label for="champ-cvv-carte">CVV :</label>
                <input 
                    type="password" 
                    id="champ-cvv-carte" 
                    name="cvv_carte"
                    placeholder="123" 
                    maxlength="4" 
                    required 
                    autocomplete="cc-csc"
                >
            </div>
        </div>

        <button type="submit" id="bouton-valider-paiement" class="bouton">
            Payer et valider la commande →
        </button>

    </form>
</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>