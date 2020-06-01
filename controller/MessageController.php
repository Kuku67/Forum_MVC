<?php

namespace Controller;

use App\Session;
use App\Router;
use Model\TopicManager;
use Model\MessageManager;

if(!Session::getUser()) {
    Session::setMessage("Vous devez être connecté pour accéder au forum. <a href='/security/login'>Connectez-vous</a> ou <a href='/security/register'>inscrivez-vous</a>","danger");
    Router::redirectTo("home", "index");
}

class MessageController {
    
    public function send() {

        if(isset($_GET['id'])) {

            $id = $_GET['id'];

            if(!empty($_POST)) {

                $model = new TopicManager();
    
                if($topic = $model->findOneById($id)) {

                    $user_id = Session::getUser()->getId();
                    $topic_id = $topic->getId();
                    $contenu = filter_input(INPUT_POST, "contenu", FILTER_UNSAFE_RAW);

                    $model = new MessageManager();
                    $model->addMessage(
                        $contenu,
                        $user_id,
                        $topic_id
                    );

                    Router::redirectTo("topic", "view", $topic_id);
    
                } else {
                    Session::setMessage("Une erreur s'est produite.", "danger");
                    Router::redirectTo("topic", "view", $id);
                }
            } else {
                Router::redirectTo("topic", "view", $id);
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    public function delete() {

        if(isset($_GET['id'])) {

            $id = $_GET['id'];
            $model = new MessageManager();

            if($message = $model->findOneById($id)) {

                if(Session::getUser()->getId() == $message->getUser()->getId() || Session::getUser()->getRole() === 'admin') {

                    $topicId = $message->getTopic()->getId();

                    $model->deleteMessage($id);
                    // Message de confirmation et redirection
                    Session::setMessage("Le message a bien été supprimé.", "success");
                    Router::redirectTo("topic", "view", $topicId);
                } else {
                    Session::setMessage("Vous n'avez pas les droits nécessaires.", "danger");
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas supprimer un message qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    public function edit() {

        if(isset($_GET['id'])) {

            $id = $_GET['id'];
            $model = new MessageManager();

            if($message = $model->findOneById($id)) {

                if(Session::getUser()->getId() == $message->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    
                    if(!empty($_POST)) {
                        // Failles XSS
                        $user_id = Session::getUser()->getId();
                        $contenu = filter_input(INPUT_POST, "contenu", FILTER_UNSAFE_RAW);
                        // Si les champs sont valides
                        if($contenu){
                            // Choix du model requis
                            $topicId = $message->getTopic()->getId();
                            $message = $model->updateMessage($contenu, $user_id, $id);
                            // Message de confirmation, et redirection.
                            Session::setMessage("Le message a bien été modifié.", "success");
                            Router::redirectTo("topic", "view", $topicId);
                        } else {
                            Session::setMessage("Les champs ne sont pas conformes.", "danger");
                            Router::redirectTo("message", "edit", $message->getUser()->getId());
                        }
                    } else {
                        // Retourne la vue avec les datas nécessaires
                        return [
                            "view" => "editMessage.php",
                            "title" => "Forum - Edition",
                            "data" => $message
                        ];
                    }
                } else {
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas modifier un message qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }
}