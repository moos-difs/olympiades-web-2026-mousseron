<?php
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
    <!-- Lien vers ton CSS néo-brutaliste -->
    <link rel="stylesheet" href="static/css/style-zinzin.css">
    <!-- Lien vers CSS d'animations -->
    <link rel="stylesheet" href="static/css/animations.css">
    <!-- Lien vers CSS responsive -->
    <link rel="styleshett" href="static/css/responsive.css">
    <!-- Polices Google Fonts pour le style cartoon / brutaliste -->
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Ajustement Flexbox pour fixer le footer en bas de l'écran */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .contenu {
            flex: 1; /* Pousse le footer vers le bas si la page est courte */
        }
    </style>
</head>
<body>

<!-- En-tête / navigation du parc -->
<header class="entete">
    <a href="index.php?action=accueil" class="entete__logo cartoon-logo">
        <span class="logo-nuage">☁️</span>
        <span class="entete__logo-mark" style="color: var(--vert);">🎬</span> 
        <span class="logo-texte">
            <span style="color:#fdc80f;">STUDIO</span><span style="color:#fd312d;">TOON</span>
        </span>
    </a>
    <nav class="entete__nav">
        <a href="index.php?action=accueil">
            <span class="entete__nav-num">01</span> Accueil
        </a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === "admin"): ?>
            <a href="index.php?action=admin_attractions">
        <?php else : ?>
            <a href="index.php?action=attractions">
        <?php endif; ?>
            <span class="entete__nav-num">02</span> Attractions
        </a>

        <a href="index.php?action=live">
            <span class="entete__nav-num">03</span> En direct
        </a>

        <a href="index.php?action=billetterie">
            <span class="entete__nav-num">04</span> Billetterie
        </a>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === "admin"): ?>
            <a href="index.php?action=admin_reservations">
        <?php else : ?>
            <a href="index.php?action=mes_reservations">
        <?php endif; ?>
            <span class="entete__nav-num">05</span>Réservations
        </a>

        <?php if (isset($_SESSION["user_id"]) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="index.php?action=admin_controle">
                <span class="entete__nav-num">06</span>Controle tickets
            </a>

            <a href="index.php?action=admin_stats">
                <span class="entete__nav-num">07</span>Statistiques
            </a>

        <?php else : ?>
            <a href="index.php?action=carte">
                <span class="entete__nav-num">06</span>Carte intéractive
            </a>
            <a href="index.php?action=planificateur">
                <span class="entete__nav-num">07</span>Planificateur
            </a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Affiché si connecté -->
            <a href="index.php?action=account" style="background: var(--violet); color: #fff; padding: 0.4rem 0.8rem; border: 3px solid var(--noir); font-size: 0.85rem; display: flex; align-items: center;">
                    <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= $_SESSION['user_role'] === 'admin' ? '⭐ Admin' : '👤 User' ?>)
            </a>
            <a href="index.php?action=logout" style="background: var(--rose); color: #fff;">Déconnexion</a>
        <?php else: ?>
            <!-- Affiché si visiteur non connecté -->
            <a href="index.php?action=login" style="background: var(--cyan);">Connexion</a>
        <?php endif; ?>
    </nav>
</header>

<!-- Contenu Principal -->
<main class="contenu">
    <?php if (isset($content)) { echo $content; } ?>
</main>

<!-- Pied de page -->
<footer class="pied">
    <p>&copy; 2026 - Studio Toon. « Ici, c'est toi le dessin animé. » Tous droits réservés.</p>
</footer>

</body>
</html>