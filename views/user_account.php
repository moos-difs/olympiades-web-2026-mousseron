<?php 
/**
 * Vue : Espace Personnel / Mon Compte
 * Affiche les informations de l'utilisateur ainsi que son historique de billets/réservations.
 */

$pageTitle = "Mon Compte - Studio Toon";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/user_account.css">
<!-- En-tête de la page compte -->
<div id="entete-compte" class="page-entete">
    <div class="entete-compte__contenu">
        <div>
            <h1 class="titre-compte">👤 Mon Espace Personnel</h1>
            <p class="page-entete__intro intro-compte">
                Consultez vos informations de compte et retrouvez vos billets.
            </p>
        </div>
        <div>
            <a href="index.php?action=planificateur" class="bouton-planificateur">
                🎯 Mon Planificateur →
            </a>
        </div>
    </div>
</div>

<div id="conteneur-compte">

    <!-- 1. INFORMATIONS DU COMPTE -->
    <section class="carte-section">
        <h2 class="titre-section">
            📋 Mes Informations
        </h2>

        <div class="grille-infos">
            <div class="carte-info">
                <span class="badge-label">Email</span>
                <div class="valeur-info">
                    <?= htmlspecialchars($utilisateur['email'] ?? $_SESSION['user_email'] ?? 'Non renseigné') ?>
                </div>
            </div>

            <div class="carte-info">
                <span class="badge-label">Nom</span>
                <div class="valeur-info valeur-nom">
                    <?= htmlspecialchars($utilisateur['nom'] ?? $_SESSION['user_nom'] ?? 'Membre') ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. HISTORIQUE DES RÉSERVATIONS DE BILLETS -->
    <section class="carte-section">
        <h2 class="titre-section">
            🎟️ Billets & Réservations
        </h2>

        <div class="liste-reservations">
            <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $res): ?>
                    <article class="carte-reservation">
                        <div class="reservation-details">
                            <span class="badge-reservation">
                                RÉSERVATION #ST-<?= htmlspecialchars($res['id']) ?>
                            </span>
                            <h3 class="reservation-date">
                                Visite le : <?= htmlspecialchars($res['date_visite'] ?? 'Date flexible') ?>
                            </h3>
                            <p class="reservation-meta">
                                <?= htmlspecialchars($res['total_billets'] ?? 1) ?> Billet(s) • Total : <?= number_format($res['prix_total'] ?? 0, 2, ',', ' ') ?> €
                            </p>
                        </div>
                        <div class="reservation-actions">
                            <a href="index.php?action=voir_mail&id=<?= htmlspecialchars($res['id']) ?>" class="bouton-ebillet">
                                Voir mes e-billets 🎫
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carte-vide">
                    <p class="texte-vide">Vous n'avez pas encore effectué de réservation.</p>
                    <a href="index.php?action=billetterie" class="bouton-acheter">
                        Acheter des billets →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>