<?php

namespace Controller;

use App\Session;
use Model\UserManager;
use Model\TopicManager;
use Model\MessageManager;

class HomeController {

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