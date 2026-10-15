<?php
require_once __DIR__ . '/../models/AttractionModel.php';

class AttractionController {
    private $model;

    public function __construct() {
        $this->model = new AttractionModel();
    }

    public function lister() {
        $current_univers = isset($_GET['univers']) ? $_GET['univers'] : null;
        $attractions = $this->model->getAttractions($current_univers);
        require_once 'views/attractions.php';
    }

    public function detail($id) {
        $attraction = $this->model->getAttractionById($id);
        if (!$attraction) {
            $this->erreur("L'attraction demandée n'existe pas ou a été fermée définitivement.");
            return;
        }
        require_once 'views/attraction_detail.php';
    }

    public function erreur($message) {
        $message_erreur = $message;
        header("HTTP/1.0 404 Not Found");
        require_once 'views/error.php';
    }

    public function ajouter() {
        $this->model->ajouter();
        require_once 'views/attractions.php';
    }
}