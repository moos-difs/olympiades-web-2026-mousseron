<?php
/**
 * Contrôleur : StatsController
 * Gère le tableau de bord administrateur des statistiques et des analyses (KPIs, ventes journalières,
 * temps d'attente, stocks de billets et comparatif des accès au parc).
 */

require_once __DIR__ . '/../models/StatsModel.php';

class StatsController {
    private $statsModel;

    public function __construct() {
        $this->statsModel = new StatsModel();
    }

    /**
     * Affiche la page principale du tableau de bord des statistiques (réservé aux administrateurs).
     */
    public function index() {
        // Vérification de la sécurité : l'utilisateur doit être connecté et posséder le rôle 'admin'
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }

        // Récupération des données analytiques via le modèle
        $kpis = $this->statsModel->getGlobalKPIs();
        $ventes_jour = $this->statsModel->getVentesEtCaParJour();
        $attractions_stats = $this->statsModel->getTempsAttenteMoyenParAttraction();
        $tarifs_stats = $this->statsModel->getVentesParTarif();
        
        // Indicateurs complémentaires (jauge et dates)
        $billets_restants = $this->statsModel->getBilletsRestantsParJour();
        $dates_disponibles = $this->statsModel->getDatesVisitesUniques();

        // Gestion du filtre optionnel par date pour le graphique comparatif
        $date_filtre = $_GET['date_visite'] ?? null;
        $comparatif_jours = $this->statsModel->getComparatifVendusVsValidesParDate($date_filtre);

        // Chargement de la vue associée
        require_once 'views/admin_stats_index.php';
    }
}