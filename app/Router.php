<?php
namespace App;

abstract class Router {
    
    public static function handleRequest($get){
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
            $url.= $ctrl ? "/".$ctrl : "";
            $url.= $method ? "/".$method : "";
            $url.= $id ? "/".$id : "";
        } else $url = "/";
        header("Location: $url");
        die();   
    }
}