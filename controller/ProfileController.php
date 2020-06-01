<?php

namespace Controller;

use App\Session;
use App\Router;
use Model\UserManager;
use Model\TopicManager;
use Model\MessageManager;

if(!Session::getUser()) {
    Session::setMessage("Vous devez être connecté(e) pour accéder au forum. <a href='/security/login'>Connectez-vous</a> ou <a href='/security/register'>inscrivez-vous</a>.","danger");
    Router::redirectTo("home", "index");
}

class ProfileController {

    // Visionner un profile
    public function view() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            // Choix du manager
            $model = new UserManager();
            // Si un topic existe
            if($user = $model->findOneById($id)) {
                // Retourne la vue avec les datas nécessaires
                $topicModel = new TopicManager();
                $topics = $topicModel->findByUserId($id);
                $messageModel = new MessageManager();
                $messages = $messageModel->findByUserId($id);

                return [
                    "view" => "viewUser.php",
                    "title" => "Forum - Voir le profil",
                    "data" => [
                        "user" => $user,
                        "topics" => $topics,
                        "messages" => $messages
                    ]
                ];
            } else {
                Session::setMessage("L'utilisateur n'existe pas.'", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }
}