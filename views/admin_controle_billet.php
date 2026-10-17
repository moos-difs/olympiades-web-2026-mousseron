<?php 
/**
 * Vue : Contrôle des Billets (Entrée)
 * Interface administrateur/agent d'accueil permettant de scanner ou saisir le code unique d'un billet
 * afin de valider l'accès au parc.
 */

$pageTitle = "Contrôle Billets - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/admin_controle_billet.css">
<!-- En-tête de la page de contrôle -->
<div id="entete-controle-billets" class="page-entete">
    <h1>🔍 Contrôle des Billets (Entrée)</h1>
    <p class="page-entete__intro">Scanne ou saisis le code unique du visiteur pour valider son accès au parc.</p>
</div>

<!-- Conteneur principal du formulaire de scan -->
<div id="conteneur-controle-billets">
    
    <!-- Formulaire de scan / saisie -->
    <form id="formulaire-scan-billet" action="index.php?action=admin_verifier_billet" method="POST">
        <div class="champ-formulaire-code">
            <label for="champ-code-unique">Code unique du billet :</label>
            <input 
                type="text" 
                id="champ-code-unique"
                name="code_unique" 
                placeholder="EX: 7F8A3B21" 
                autofocus 
                required 
                autocomplete="off"
            >
        </div>

        <button type="submit" id="bouton-verifier-billet" class="bouton">
            Vérifier le billet →
        </button>
    </form>

    <!-- Affichage du résultat du contrôle -->
    <?php if (isset($message_resultat)): ?>
        <?php 
            $classeStatut = 'resultat-neutre';
            if (($statut_classe ?? '') === 'erreur') $classeStatut = 'resultat-erreur';
            elseif (($statut_classe ?? '') === 'avertissement') $classeStatut = 'resultat-avertissement';
            elseif (($statut_classe ?? '') === 'succes') $classeStatut = 'resultat-succes';
        ?>
        <div id="bloc-resultat-controle" class="<?= $classeStatut ?>">
            <?= $message_resultat ?>
        </div>
    <?php endif; ?>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>