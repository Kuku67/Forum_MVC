<?php
namespace Model;

use App\AbstractManager;

class UserManager extends AbstractManager {

    private static $classname = "Model\User";

    public function __construct(){
        self::connect(self::$classname);
    }

    // Trouver un utilisateur en fonction de son mail ou pseudo
    public function findUser($login){
        
        $sql = "SELECT * FROM utilisateur WHERE mail = :login OR pseudo = :login ";

        return self::getOneOrNullResult(
            self::select($sql, ['login' => $login], false),
            self::$classname
        );
    }

    // Trouver un utilisateur en fonction de ton ID
    public function findOneById($id){
        
        $sql = "SELECT * FROM utilisateur WHERE id = :id";

        return self::getOneOrNullResult(
            self::select($sql, ['id' => $id], false),
            self::$classname
        );
    }

    // Ajouter un utilisateur (inscription)
    public function addUser($pseudo, $mail, $hash, $secret){
        $sql = "INSERT INTO utilisateur (pseudo, mail, password, secret) VALUES (:pseudo, :mail, :password, :secret)";

        return self::create($sql, [
                'pseudo' => $pseudo,
                'mail' => $mail,
                'password' => $hash,
                'secret' => $secret
        ]);
    }

    // Trouver tous les utilisateurs
    public function findAll(){
        $sql = "SELECT * FROM utilisateur";

        return self::getResults(
            self::select($sql, null, true),
            self::$classname
        );
    }

    // Trouver tous les utilisateur ayant un certain rôle
    public function findByRole($role){
        $sql = "SELECT id, pseudo, inscription FROM utilisateur WHERE role = :role";

        return self::getResults(
            self::select($sql, ['role' => $role], true),
            self::$classname
        );
    }

    // Effacer un utilisateur
    public function deleteUser($id) {

        $sql = "DELETE FROM utilisateur WHERE id = :id";
        return self::delete($sql, ['id' => $id]);
    }

    // Trouver un utilisateur selon son cookie d'authentification
    public function findUserByCookie($cookie) {

        $sql = "SELECT * FROM utilisateur WHERE secret = :secret";

        return self::getOneOrNullResult(
            self::select($sql, ['secret' => $cookie], false),
            self::$classname
        );
    }
}