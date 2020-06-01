<?php
namespace App;

session_start();

abstract class Session {
    
    public static function getUser(){
        if(isset($_SESSION['user']) && $_SESSION['user'] !== null){
            return $_SESSION['user'];
        }
        return false;
    }

    public static function setUser($user){
        $_SESSION['user'] = $user;
    }

    public static function removeUser(){
        if(self::getUser()){
            unset($_SESSION['user']);
        }
        return;
    }

    public static function authenticationRequired($roleToHave){
        if(!self::getUser()){
            Router::redirectTo("security", "login");
        }
    }

    public static function setMessage($message, $type) {
        $_SESSION['message'] = [
            "type" => $type,
            "content" => $message
        ];
    }

    public static function getMessage() {
        if(isset($_SESSION['message']) && $_SESSION['message'] !== null) {
            return $_SESSION['message'];
        }
        return false;
    }

    public static function unsetMessage() {
        if(self::getMessage()) {
            unset($_SESSION['message']);
        }
    }
}