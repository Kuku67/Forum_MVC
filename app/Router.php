<?php
namespace App;

use App\Session;

abstract class Router {
    
    // Fonction de gestion de la requête HTTP
    public static function handleRequest($get, $post){
        // Avant toute chose, si j'ai pas de Token CSRF, j'en met un.
        !Session::getToken() ? Session::setToken() : null;
        // Si sur le moindre POST, le Token ne correspond pas, on annule.
        if(!empty($post) && $post['token'] !== Session::getToken()) {
            Session::setToken();
            self::redirectTo("home", "index");
        }

        // Controller et Method par défaut
        $ctrlname = "Controller\HomeController";
        $method = "index";
        // Si un param CTRL est présent
        if(isset($get['ctrl'])){
            // Création du nom de la classe à appeler
            $ctrlname = "Controller\\".ucfirst($get['ctrl'])."Controller";
        }
        // Instanciation
        $ctrl = new $ctrlname();
        // Si un param METHOD est présent
        if(isset($get['method']) && method_exists($ctrl, $get['method'])){
            // Récupération de la méthode
            $method = $get['method'];
        }
        // On retourne l'action
        return $ctrl->$method();
    }

    public static function redirectTo($ctrl = null, $method = null, $id = null){
        // Si CTRL est autre chose que home, on construit l'url en fonction des param
        if($ctrl != "home") {
            $url = "";
            $url.= $ctrl ? "/".$ctrl : "";
            $url.= $method ? "/".$method : "";
            $url.= $id ? "/".$id : "";
        } else $url = "/";
        header("Location: $url");
        die();
    }
}