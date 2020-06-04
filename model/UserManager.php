<?php
namespace Model;

use App\AbstractManager;

class UserManager extends AbstractManager {

    private static $classname = "Model\User";

    public function __construct(){
        self::connect(self::$classname);
    }

    /**
     * Trouver un utilisateur en fonction du mail (unique) ou pseudo (unique)
     */
    public function findUser($login){
        
        $sql = "SELECT * FROM utilisateur WHERE mail = :login OR pseudo = :login ";

        return self::getOneOrNullResult(
            self::select($sql, ['login' => $login], false),
            self::$classname
        );
    }

    /**
     * Trouver un utilisateur en fonction de son ID
     */
    public function findOneById($id){
        
        $sql = "SELECT * FROM utilisateur WHERE id = :id";

        return self::getOneOrNullResult(
            self::select($sql, ['id' => $id], false),
            self::$classname
        );
    }

    /**
     * Ajouter un utilisateur (inscription)
     */
    public function addUser($pseudo, $mail, $hash, $secret, $mailToken){
        $sql = "INSERT INTO utilisateur (pseudo, mail, password, secret, mailToken) VALUES (:pseudo, :mail, :password, :secret, :mailToken)";

        return self::create($sql, [
                'pseudo' => $pseudo,
                'mail' => $mail,
                'password' => $hash,
                'secret' => $secret,
                'mailToken' => $mailToken
        ]);
    }

    /**
     * Trouver tous les utilisateurs existants et toutes leurs informations
     */
    public function findAll(){
        $sql = "SELECT * FROM utilisateur";

        return self::getResults(
            self::select($sql, null, true),
            self::$classname
        );
    }

    /**
     * Trouver tous les utilisateur en fonction d'un rôle précis
     */
    public function findByRole($role){
        $sql = "SELECT id, pseudo, inscription FROM utilisateur WHERE role = :role";

        return self::getResults(
            self::select($sql, ['role' => $role], true),
            self::$classname
        );
    }

    /**
     * Effacer un utilisateur
     */
    public function deleteUser($id) {

        $sql = "DELETE FROM utilisateur WHERE id = :id";
        return self::delete($sql, ['id' => $id]);
    }

    /**
     * Mettre à jour le token de récupération de mot de passe
     */
    public function updateToken($login, $recoverToken) {

        $sql = "UPDATE utilisateur SET token = :token WHERE mail = :login OR pseudo = :login";

        return self::update($sql, [
            'token' => $recoverToken,
            'login' => $login
            ]);
    }

    /**
     * Trouver un utilisateur en fonction de son token de récupération
     */
    public function findUserByToken($token) {

        $sql = "SELECT * FROM utilisateur WHERE token = :token";

        return self::getOneOrNullResult(
            self::select($sql, ['token' => $token], false),
            self::$classname
        );
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur et passer son token de récupération en NULL
     */
    public function updatePassByToken($token, $hash) {
        $sql = "UPDATE utilisateur SET password = :password WHERE token = :token";

        return self::update($sql, [
            'password' => $hash,
            'token' => $token
        ]);
    }

    /**
     * Trouver un utilisateur en fonction de son cookie d'authentification
     */
    public function findUserByCookie($cookie) {

        $sql = "SELECT * FROM utilisateur WHERE secret = :secret";

        return self::getOneOrNullResult(
            self::select($sql, ['secret' => $cookie], false),
            self::$classname
        );
    }

    /**
     * Trouver un utilisateur en fonction de son token de validation de mail
     */
    public function findUserByMailToken($token) {
        $sql = "SELECT * FROM utilisateur WHERE mailToken = :token";

        return self::getOneOrNullResult(
            self::select($sql, ['token' => $token], false),
            self::$classname
        );
    }

    /**
     * Valider un compte utilisateur et passer son token de validation de mail à NULL
     */
    public function validateAccount($user_id) {
        
        $sql = "UPDATE utilisateur SET mailToken = '' WHERE id = :id";
        return self::update($sql, ['id' => $user_id]);
    }
}