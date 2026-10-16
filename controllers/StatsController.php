<?php
require_once 'models/StatsModel.php';

class StatsController {
    private $statsModel;

    public function __construct() {
        $this->statsModel = new StatsModel();
    }

    public function index() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }

        $kpis = $this->statsModel->getGlobalKPIs();
        $ventes_jour = $this->statsModel->getVentesEtCaParJour();
        $attractions_stats = $this->statsModel->getTempsAttenteMoyenParAttraction();
        $tarifs_stats = $this->statsModel->getVentesParTarif();
        
        // Nouveaux indicateurs
        $billets_restants = $this->statsModel->getBilletsRestantsParJour();
        $dates_disponibles = $this->statsModel->getDatesVisitesUniques();

        // Gestion du filtre par date pour le comparatif
        $date_filtre = $_GET['date_visite'] ?? null;
        $comparatif_jours = $this->statsModel->getComparatifVendusVsValidesParDate($date_filtre);

        require_once 'views/admin_stats_index.php';
    }
}