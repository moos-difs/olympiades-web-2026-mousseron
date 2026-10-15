<?php
$pageTitle = "Oups - Zinzin Land";
ob_start();
?>
    <div class="fiche-veille" style="background: var(--rose); text-align: center; padding: 4rem 2rem;">
        <h2 style="font-size: 3rem; margin-bottom: 1rem;">OUPSSSSS !</h2>
        <p style="font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 2rem;">
            <?= isset($message_erreur) ? htmlspecialchars($message_erreur) : "Une erreur est survenue." ?>
        </p>
        <a href="index.php?action=attractions" class="bouton" style="background: var(--noir); color: #fff;">Voir les attractions disponibles →</a>
    </div>
<?php
$content = ob_get_clean();
require 'views/base.php';
?>