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
        $comparatif_jours = $this->statsModel->getComparatifVendusVsValidesParJour();
        $attractions_stats = $this->statsModel->getTempsAttenteMoyenParAttraction();
        $tarifs_stats = $this->statsModel->getVentesParTarif();

        require_once 'views/admin_stats_index.php';
    }
}