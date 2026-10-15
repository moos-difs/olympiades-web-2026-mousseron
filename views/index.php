<?php
$pageTitle = "Studio Toon - Accueil";
ob_start();
?>

    <!-- En-tête visuel principal avec l'illustration du parc -->
    <div class="hero" style="background: var(--cyan); padding: 1.5rem; text-align: center; border: 5px solid var(--noir); box-shadow: 10px 10px 0 var(--noir); margin-bottom: 3rem; transform: rotate(0.4deg);">
        <img src="static/images/entree_parc.png" alt="Entrée du parc Studio Toon" style="width: 100%; height: auto; display: block; border: 3px solid var(--noir);">

        <div style="margin-top: 1.5rem;">
            <a href="index.php?action=attractions" class="bouton" style="background: var(--rose); font-size: 1.1rem; padding: 0.9rem 1.8rem;">Découvrir les attractions →</a>
        </div>
    </div>

    <div class="page-entete">
        <h1>Choisis ton univers</h1>
        <p class="page-entete__intro">Entre décors de studio, faux murs peints et gags légendaires.</p>
    </div>

    <div class="acces-rapide">
        <a href="index.php?action=attractions&univers=Studio+2D" class="carte-acces" style="background: var(--vert);">
            <h2>🎨 Studio 2D</h2>
            <p>Découvre la Falaise et traverse les murs grâce au Trou Portable.</p>
        </a>
        <a href="index.php?action=attractions&univers=Le+Chapiteau" class="carte-acces" style="background: var(--orange);">
            <h2>🎪 Le Chapiteau</h2>
            <p>Viens tester le fulgurant Canon de Cirque et ses sensations extrêmes.</p>
        </a>
    </div>

<?php
$content = ob_get_clean();
require 'views/base.php';
?>