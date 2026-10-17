<?php
/**
 * Layout Principal (Base)
 * Fichier squelette englobant toutes les vues du site (En-tête, Navigation dynamique, Contenu principal et Pied de page).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : "Studio Toon - Le Parc à Thème" ?></title>
    
    <!-- Feuilles de style CSS -->
    <link rel="stylesheet" href="static/css/style-zinzin.css">
    <link rel="stylesheet" href="static/css/animations.css">
    <link rel="stylesheet" href="static/css/responsive.css">
    <link rel="stylesheet" href="static/css/pages/base.css">
    
    <!-- Polices Google Fonts pour le style cartoon / brutaliste -->
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body id="corps-layout">

<!-- En-tête / navigation principale du parc -->
<header class="entete">
    <a href="index.php?action=accueil" class="entete__logo cartoon-logo">
        <span class="logo-nuage">☁️</span>
        <span class="entete__logo-mark icone-vert">🎬</span> 
        <span class="logo-texte">
            <span class="logo-studio">STUDIO</span><span class="logo-toon">TOON</span>
        </span>
    </a>

    <nav class="entete__nav">
        <!-- 01. Accueil -->
        <a href="index.php?action=accueil">
            <span class="entete__nav-num">01</span> Accueil
        </a>

        <!-- 02. Attractions (Redirige vers l'admin si l'utilisateur est admin) -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === "admin"): ?>
            <a href="index.php?action=admin_attractions">
        <?php else : ?>
            <a href="index.php?action=attractions">
        <?php endif; ?>
            <span class="entete__nav-num">02</span> Attractions
        </a>

        <!-- 03. En direct -->
        <a href="index.php?action=live">
            <span class="entete__nav-num">03</span> En direct
        </a>

        <!-- 04. Billetterie -->
        <a href="index.php?action=billetterie">
            <span class="entete__nav-num">04</span> Billetterie
        </a>

        <!-- 05. Réservations (Admin ou Utilisateur) -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === "admin"): ?>
            <a href="index.php?action=admin_reservations">
        <?php else : ?>
            <a href="index.php?action=mes_reservations">
        <?php endif; ?>
            <span class="entete__nav-num">05</span>Réservations
        </a>

        <!-- 06 & 07 : Menus conditionnels (Admin vs Visiteur) -->
        <?php if (isset($_SESSION["user_id"]) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="index.php?action=admin_controle">
                <span class="entete__nav-num">06</span>Controle tickets
            </a>
            <a href="index.php?action=admin_stats">
                <span class="entete__nav-num">07</span>Statistiques
            </a>
            <a href="index.php?action=admin_logs">
                <span class="entete__nav-num">08</span>Logs
            </a>
        <?php else : ?>
            <a href="index.php?action=carte">
                <span class="entete__nav-num">06</span>Carte intéractive
            </a>
            <a href="index.php?action=planificateur">
                <span class="entete__nav-num">07</span>Planificateur
            </a>
        <?php endif; ?>

        <!-- Gestion de la session utilisateur (Connecté / Non connecté) -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Affiché si l'utilisateur est connecté -->
            <a href="index.php?action=account" class="lien-nav-profil">
                <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= $_SESSION['user_role'] === 'admin' ? '⭐ Admin' : '👤 User' ?>)
            </a>
            <a href="index.php?action=logout" class="lien-nav-deconnexion">Déconnexion</a>
        <?php else: ?>
            <!-- Affiché si visiteur non connecté -->
            <a href="index.php?action=login" class="lien-nav-connexion">Connexion</a>
        <?php endif; ?>
    </nav>
</header>

<!-- Contenu Principal (Injecté dynamiquement depuis les vues) -->
<main class="contenu">
    <?php if (isset($content)) { echo $content; } ?>
</main>

<!-- Pied de page -->
<footer class="pied">
    <p>&copy; 2026 - Studio Toon. « Ici, c'est toi le dessin animé. » Tous droits réservés.</p>
</footer>

</body>
</html>