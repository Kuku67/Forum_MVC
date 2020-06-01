<?php
// Définition de l'espace de travail.
namespace Controller;
// Autoload des classes.
use App\Session;
use App\Router;
use Model\TopicManager;
use Model\MessageManager;
// Filtre rapidement les allées et venues.
if(!Session::getUser()) {
    Session::setMessage("Vous devez être connecté(e) pour accéder au forum. <a href='/security/login'>Connectez-vous</a> ou <a href='/security/register'>inscrivez-vous</a>.","danger");
    Router::redirectTo("home", "index");
}
// Déclaration de la classe.
class TopicController {

    // Création d'un topic
    public function create() {
        // Si un formulaire a été envoyé
        if(!empty($_POST)){
            $user_id = Session::getUser()->getId();
            // Failles XSS
            $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_STRING);
            $contenu = filter_input(INPUT_POST, "contenu", FILTER_UNSAFE_RAW);
            // Si les champs sont valides
            if($titre && $contenu){
                // Choix du model requis
                $model = new TopicManager();
                // Ajout dans la base de données
                $model->addTopic($titre, $contenu, $user_id);
                // Récupération du sujet qui vient d'être créé.
                $currentTopic = $model->findLast($user_id);
                $currentTopicId = $currentTopic->getId();
                // Message de confirmation, et redirection.
                Session::setMessage("Le sujet a bien été créé. <a href='/topic/view/$currentTopicId'>Voir le sujet</a>", "success");
                Router::redirectTo("topic", "create");
            } else {
                Session::setMessage("Les champs ne peuvent pas être vides !", "danger");
                Router::redirectTo("topic", "create");
            }
        }
        // Pas de else, car si l'utilisateur n'est pas redirigé, on lui retournera la vue demandée
        return [
            "view" => "createTopic.php",
            "title" => "Forum - Ouvrir un sujet",
            "data" => null
        ];
    }

    // Visionner un topic
    public function view() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            // Choix du manager
            $model = new TopicManager();
            // Si un topic existe
            if($topic = $model->findOneById($id)) {
                // Choix du manager
                $model = new MessageManager();
                // Récupération des messages
                $messages = $model->findAll($topic->getId());
                // Retourne la vue avec les datas nécessaires
                return [
                    "view" => "viewTopic.php",
                    "title" => "Forum - Sujet",
                    "data" => [
                        "topic" => $topic,
                        "messages" => $messages
                        ]
                ];
            } else {
                Session::setMessage("Le sujet n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    // Supprimer un topic
    public function delete() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            // Choix du manager
            $model = new TopicManager();
            // Si un topic existe
            if($topic = $model->findOneById($id)) {
                // Vérification des droits
                if(Session::getUser()->getId() == $topic->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Suppression du topic
                    $model->deleteTopic($id);
                    // Message de confirmation et redirection
                    Session::setMessage("Le sujet a bien été supprimé.", "success");
                    Router::redirectTo("home", "index");
                } else {
                    Session::setMessage("Vous n'avez pas les droits nécessaires", "danger");
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas effacer un sujet qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    // Verrouiller un topic
    public function lock() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            // Choix du manager
            $id = $_GET['id'];
            $model = new TopicManager();
            // Si un topic existe
            if($topic = $model->findOneById($id)) {
                // Vérification des droits
                if(Session::getUser()->getId() == $topic->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Vérouillage du topic
                    $model->lockTopic($id);
                    // Message de confirmation et redirection
                    Session::setMessage("Le sujet a bien été verrouillé.", "success");
                    Router::redirectTo("topic", "view", $id);
                } else {
                    Session::setMessage("Vous n'avez pas les droits nécessaires", "danger");
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas verrouiller un sujet qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    // Déverrouiller un topic
    public function unlock() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            // Choix du manager
            $id = $_GET['id'];
            $model = new TopicManager();
            // Si un topic existe
            if($topic = $model->findOneById($id)) {
                // Vérification des droits
                if(Session::getUser()->getId() == $topic->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Dévérouillage du topic
                    $model->unlockTopic($id);
                    // Message de confirmation et redirection
                    Session::setMessage("Le sujet a bien été déverrouillé.", "success");
                    Router::redirectTo("topic", "view", $id);
                } else {
                    Session::setMessage("Vous n'avez pas les droits nécessaires", "danger");
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas verrouiller un sujet qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    // Mettre un topic en résolu
    public function resolve() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            // Choix du manager
            $id = $_GET['id'];
            $model = new TopicManager();
            // Si un topic existe
            if($topic = $model->findOneById($id)) {
                // Vérification des droits
                if(Session::getUser()->getId() == $topic->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Passage en statut résolu
                    $model->resolveTopic($id);
                    // Message de confirmation et redirection
                    Session::setMessage("Le statut « résolu » a bien été ajouté.", "success");
                    Router::redirectTo("topic", "view", $id);
                } else {
                    Session::setMessage("Vous n'avez pas les droits nécessaires", "danger");
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas agir sur un sujet qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    // Retirer le statut résolu d'un topic
    public function unresolve() {
        // Si un param ID est présent
        if(isset($_GET['id'])) {
            // Choix du manager
            $id = $_GET['id'];
            $model = new TopicManager();
            // Si un topic existe
            if($topic = $model->findOneById($id)) {
                // Vérification des droits
                if(Session::getUser()->getId() == $topic->getUser()->getId() || Session::getUser()->getRole() === 'admin') {
                    // Retrait du statut résolu
                    $model->unresolveTopic($id);
                    // Message de confirmation et redirection
                    Session::setMessage("Le statut « résolu » a bien été retiré.", "success");
                    Router::redirectTo("topic", "view", $id);
                } else {
                    Session::setMessage("Vous n'avez pas les droits nécessaires", "danger");
                    Router::redirectTo("home", "index");
                }
            } else {
                Session::setMessage("Vous ne pouvez pas agir sur un sujet qui n'existe pas...", "secondary");
                Router::redirectTo("home", "index");
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }

    // Modifier un topic
    public function edit() {

        // Si un param ID est présent
        if(isset($_GET['id'])) {

            $id = $_GET['id'];

            if(!empty($_POST)) {
                $user_id = Session::getUser()->getId();
                // Failles XSS
                $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_STRING);
                $contenu = filter_input(INPUT_POST, "contenu", FILTER_UNSAFE_RAW);
                // Si les champs sont valides
                if($titre && $contenu){
                    // Choix du model requis
                    $model = new TopicManager();
                    // Ajout dans la base de données
                    $model->updateTopic($titre, $contenu, $user_id, $id);
                    // Message de confirmation, et redirection.
                    Session::setMessage("Le sujet a bien été modifié.", "success");
                    Router::redirectTo("topic", "view", $id);
                } else {
                    // Champs non valides
                }
            } else {
                // Choix du manager
                $model = new TopicManager();
                // Si un topic existe
                if($topic = $model->findOneById($id)) {
                    // Retourne la vue avec les datas nécessaires
                    return [
                        "view" => "editTopic.php",
                        "title" => "Forum - Edition",
                        "data" => $topic
                    ];
                } else {
                    Session::setMessage("Vous ne pouvez pas modifier un sujet qui n'existe pas...", "secondary");
                    Router::redirectTo("home", "index");
                }
            }
        } else {
            Router::redirectTo("home", "index");
        }
    }
}