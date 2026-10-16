<?php 
$pageTitle = "Mon Compte - Studio Toon";
ob_start(); 
?>

<!-- En-tête -->
<div class="page-entete" style="max-width: 900px; margin: 0 auto 2rem auto; background: var(--violet); color: #fff; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="color: #fff; font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 2rem;">👤 Mon Espace Personnel</h1>
            <p class="page-entete__intro" style="color: #fff; margin: 0.5rem 0 0; font-weight: 700;">Consultez vos informations de compte et retrouvez vos billets.</p>
        </div>
        <div>
            <a href="index.php?action=planificateur" style="background: var(--cyan); color: var(--noir); padding: 0.8rem 1.2rem; border: 3px solid var(--noir); font-weight: 900; text-decoration: none; box-shadow: 4px 4px 0 var(--noir); display: inline-block; text-transform: uppercase;">
                🎯 Mon Planificateur →
            </a>
        </div>
    </div>
</div>

<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">

    <!-- 1. INFORMATIONS DU COMPTE -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            📋 Mes Informations
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
            <div style="background: #fff; border: 3px solid var(--noir); padding: 1.2rem; box-shadow: 4px 4px 0 var(--noir);">
                <span style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; background: var(--noir); color: #fff; padding: 2px 6px;">Email</span>
                <div style="font-weight: 800; font-size: 1.1rem; margin-top: 0.5rem;"><?= htmlspecialchars($utilisateur['email'] ?? $_SESSION['user_email'] ?? 'Non renseigné') ?></div>
            </div>

            <div style="background: #fff; border: 3px solid var(--noir); padding: 1.2rem; box-shadow: 4px 4px 0 var(--noir);">
                <span style="font-size: 0.8rem; font-weight: 900; text-transform: uppercase; background: var(--noir); color: #fff; padding: 2px 6px;">Nom</span>
                <div style="font-weight: 800; font-size: 1.1rem; margin-top: 0.5rem; text-transform: uppercase; color: var(--violet);"><?= htmlspecialchars($utilisateur['nom'] ?? $_SESSION['user_nom'] ?? 'Membre') ?></div>
            </div>
        </div>
    </div>

    <!-- 2. HISTORIQUE DES RÉSERVATIONS DE BILLETS -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            🎟️ Billets & Réservations
        </h2>

        <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
            <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $res): ?>
                    <div style="background: #fff; border: 3px solid var(--noir); padding: 1.2rem; box-shadow: 4px 4px 0 var(--noir); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <span style="background: var(--cyan); border: 2px solid var(--noir); padding: 2px 6px; font-size: 0.75rem; font-weight: 900;">RÉSERVATION #ST-<?= htmlspecialchars($res['id']) ?></span>
                            <h3 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0.4rem 0 0 0; font-size: 1.1rem;">Visite le : <?= htmlspecialchars($res['date_visite'] ?? 'Date flexible') ?></h3>
                            <p style="margin: 0.2rem 0 0 0; font-weight: 700; font-size: 0.85rem; color: #555;"><?= htmlspecialchars($res['total_billets'] ?? 1) ?> Billet(s) • Total : <?= number_format($res['prix_total'] ?? 0, 2, ',', ' ') ?> €</p>
                        </div>
                        <div>
                            <a href="index.php?action=voir_mail&id=<?= htmlspecialchars($res['id']) ?>" style="background: var(--vert); color: var(--noir); padding: 0.6rem 1rem; border: 2px solid var(--noir); font-weight: 900; text-decoration: none; box-shadow: 3px 3px 0 var(--noir); display: inline-block; font-size: 0.85rem; text-transform: uppercase;">
                                Voir mes e-billets 🎫
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="background: #fff; border: 3px solid var(--noir); padding: 1.5rem; text-align: center; box-shadow: 4px 4px 0 var(--noir);">
                    <p style="font-weight: 800; margin: 0; color: #555;">Vous n'avez pas encore effectué de réservation.</p>
                    <a href="index.php?action=billetterie" style="display: inline-block; margin-top: 1rem; background: var(--cyan); color: var(--noir); padding: 0.6rem 1.2rem; border: 2px solid var(--noir); font-weight: 900; text-decoration: none; box-shadow: 3px 3px 0 var(--noir); text-transform: uppercase;">
                        Acheter des billets →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>