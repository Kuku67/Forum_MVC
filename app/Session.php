<?php
namespace App;

session_start();

abstract class Session {
    
    // Retourne les infos de l'utilisateur présent en session
    // ou FALSE s'il n'y en a pas
    public static function getUser() {
        if(isset($_SESSION['user']) && $_SESSION['user'] !== NULL){
            return $_SESSION['user'];
        }
        return false;
    }

    // Insérer les données utilisateur en session
    public static function setUser($user) {
        $_SESSION['user'] = $user;
    }

    // Permet de nullifier la connexion au profil
    public static function removeUser() {
        if(self::getUser()){
            unset($_SESSION['user']);
        }
        return;
    }

    public static function generateKey() {
        if(!isset($_SESSION['key']) || $_SESSION['key'] === NULL) {
            $_SESSION['key'] = bin2hex(random_bytes(32));
        }
        return;
    }

    public static function eraseKey() {
        unset($_SESSION['key']);
    }

    public static function getKey() {
        return isset($_SESSION['key']) ? $_SESSION['key'] : NULL;
    }

    // Dans le cas où j'utilise bootstrap, mais cela me
    // permet aussi de personnaliser les messages d'erreur
    public static function setMessage($message, $type) {
        $_SESSION['message'] = [
            "type" => $type,
            "content" => $message
        ];
    }

    // Si un message d'erreur est stocké, je le récupère
    public static function getMessage() {
        if(isset($_SESSION['message']) && $_SESSION['message'] !== NULL) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
            return $message;
        }
        return false;
    }

    // Permet d'éviter les comportements non voulus
    // comme les messages qui subsistent d'une page à l'autre
    public static function unsetMessage() {
        if(self::getMessage()) {
            unset($_SESSION['message']);
        }
    }
}