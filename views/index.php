<?php 
/**
 * Vue : Accueil du site
 * Page d'accueil principale du parc d'attractions Studio Toon avec mise en avant des univers et attractions.
 */

$pageTitle = "Studio Toon - Accueil";
ob_start(); 
?>
<link rel="stylesheet" href="static/css/pages/index.css">
<!-- En-tête visuel principal (Hero Banner) avec illustration du parc -->
<div id="hero-accueil" class="hero">
    <img 
        src="static/images/entree_parc.png" 
        alt="Entrée du parc Studio Toon" 
        class="hero__image"
    >

    <div class="hero__actions">
        <a href="index.php?action=attractions" id="bouton-decouvrir-attractions" class="bouton">
            Découvrir les attractions →
        </a>
    </div>
</div>

<!-- En-tête textuel de la section des univers -->
<div class="page-entete">
    <h1>Choisis ton univers</h1>
    <p class="page-entete__intro">Entre décors de studio, faux murs peints et gags légendaires.</p>
</div>

<!-- Grille d'accès rapide aux différents univers du parc -->
<div class="acces-rapide">
    
    <!-- Univers Studio 2D -->
    <a href="index.php?action=attractions&univers=Studio+2D" class="carte-acces carte-acces--studio-2d">
        <h2>🎨 Studio 2D</h2>
        <p>Découvre la Falaise et traverse les murs grâce au Trou Portable.</p>
    </a>

    <!-- Univers Le Chapiteau -->
    <a href="index.php?action=attractions&univers=Le+Chapiteau" class="carte-acces carte-acces--chapiteau">
        <h2>🎪 Le Chapiteau</h2>
        <p>Viens tester le fulgurant Canon de Cirque et ses sensations extrêmes.</p>
    </a>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>