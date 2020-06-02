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

    // Fonction de suppression d'un utilisateur
    public function delete() {
        // Si un identifiant est renseigné
        if(isset($_GET['id'])) {

            $id = $_GET['id'];
            // J'instancie le Manager dont j'ai besoin
            $model = new UserManager();
            // Si l'utilisateur existe
            if($user = $model->findOneById($id)) {
                // J'ai besoin de récupérer tous les messages associés à l'utilisateur
                $messageModel = new MessageManager();
                $messages = $messageModel->findByUserId($id);
                // J'ai besoin de récupérer tous les topics associés à l'utilisateur
                $topicModel = new TopicManager();
                $topics = $topicModel->findByUserId($id);
                // Pour chaque message, on leur attribut un nouvel auteur fantôme
                foreach($messages as $message) {
                    $messageModel->nullifyMessage($message->getId());
                }
                // Pour chaque topic, on leur attribut un nouvel auteur fantôme
                foreach($topics as $topic) {
                    $topicModel->nullifyTopic($topic->getId());
                }
                // Ensuite je supprime l'utilisateur
                $model->deleteUser($user->getId());
                // Et enfin je le redirige
                Router::redirectTo("security", "logout");
            }
        }
    }
}