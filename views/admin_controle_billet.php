<?php 
$pageTitle = "Contrôle des Billets - Admin Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 650px; margin: 0 auto 2rem auto; background: var(--orange);">
    <h1>🔍 Contrôle des Billets (Entrée)</h1>
    <p class="page-entete__intro">Scanne ou saisis le code unique du visiteur pour valider son accès au parc.</p>
</div>

<div style="max-width: 650px; margin: 0 auto; background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2.5rem;">
    
    <!-- Formulaire de scan / saisie -->
    <form action="index.php?action=admin_verifier_billet" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div>
            <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem;">Code unique du billet :</label>
            <input type="text" name="code_unique" placeholder="EX: 7F8A3B21" autofocus required style="width: 100%; padding: 1rem; border: 3px solid var(--noir); font-family: monospace; font-weight: 900; font-size: 1.5rem; text-align: center; background: #fff; text-transform: uppercase;">
        </div>

        <button type="submit" class="bouton" style="background: var(--vert); color: var(--noir); padding: 1rem; font-size: 1.1rem; cursor: pointer; text-align: center;">
            Vérifier le billet →
        </button>
    </form>

    <!-- Affichage du résultat du contrôle -->
    <?php if (isset($message_resultat)): ?>
        <?php 
            $bgCouleur = 'var(--cyan)';
            if ($statut_classe === 'erreur') $bgCouleur = 'var(--rose)';
            if ($statut_classe === 'avertissement') $bgCouleur = 'var(--orange)';
            if ($statut_classe === 'succes') $bgCouleur = 'var(--vert)';
        ?>
        <div style="margin-top: 2rem; background: <?= $bgCouleur ?>; border: 3px solid var(--noir); padding: 1.5rem; box-shadow: 5px 5px 0 var(--noir); font-weight: 800; font-size: 1.1rem; text-align: center; color: var(--noir);">
            <?= $message_resultat ?>
        </div>
    <?php endif; ?>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>