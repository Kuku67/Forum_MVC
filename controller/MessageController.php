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
    
    // Fonction pour envoyer un message sur un topic
    public function send() {
        // Si une cible est renseignée
        if(isset($_GET['id'])) {

            $id = $_GET['id'];
            // Si j'ai un formulaire POST, je le traite.
            if(!empty($_POST)) {
                // J'instancie le Manager dont j'ai besoin.
                $model = new TopicManager();
                // Si le topic existe
                if($topic = $model->findOneById($id)) {
                    // Je prend toutes les données dont j'ai besoin sur ma vue
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
    // Fonction de suppression de message
    public function delete() {
        // Si un identifiant de message est renseigné
        if(isset($_GET['id'])) {
            // J'instancie le manager dont j'ai besoin
            $id = $_GET['id'];
            $model = new MessageManager();
            // Si le message à supprimer existe
            if($message = $model->findOneById($id)) {
                // Je vérifie que l'utilisateur qui a demandé la requête a les droits nécessaires
                if(Session::getUser()->getId() == $message->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Je récupère l'id du topic si lequel j'étais
                    // Ergonomiquement, je vais pouvoir renvoyer l'utilisateur là où il était
                    $topicId = $message->getTopic()->getId();
                    // Je supprime le message
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
    // Fonction d'édition d'un message
    public function edit() {
        // Si un identifiant de message est renseigné
        if(isset($_GET['id'])) {
            // J'instancie le Manager nécessaire
            $id = $_GET['id'];
            $model = new MessageManager();
            // Si le message existe
            if($message = $model->findOneById($id)) {
                // Si l'utilisateur a les droits
                if(Session::getUser()->getId() == $message->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Si un post est envoyé
                    if(!empty($_POST)) {
                        $contenu = filter_input(INPUT_POST, "contenu", FILTER_UNSAFE_RAW);
                        // Si les champs sont valides
                        if($contenu){
                            // J'ai besoin de savoir sur quel topic le message est édité
                            $topicId = $message->getTopic()->getId();
                            // Je supprime le message
                            $message = $model->updateMessage($contenu, $id);
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