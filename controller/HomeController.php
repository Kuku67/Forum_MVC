<?php

namespace Controller;

use App\Session;
use Model\TopicManager;

class HomeController {

    // Fonction pour retourner ma Homepage
    public function index() {

        $model = new TopicManager();

        $lastTopics = $model->findAll();

            return [
    
                "view" => "home.php",
                "title" => "Forum - Accueil",
                "data" => $lastTopics
    
            ];
    }
}