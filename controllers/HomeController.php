<?php
/**
 * Contrôleur : HomeController
 * Gère l'affichage de la page d'accueil principale du parc Studio Toon.
 */

class HomeController {
    /**
     * Affiche la vue de la page d'accueil.
     */
    public function index() {
        require_once __DIR__ . '/../views/index.php';
    }
}