<?php
namespace Model;

use App\AbstractManager;

class MessageManager extends AbstractManager {

    private static $classname = "Model\Message";

    public function __construct(){
        self::connect(self::$classname);
    }

    /**
     * Trouver un message en fonction de son ID
     */
    public function findOneById($id){
        
        $sql = "SELECT * FROM message WHERE id = :id";

        return self::getOneOrNullResult(
            self::select($sql, ['id' => $id], false),
            self::$classname
        );
    }

    /**
     * Trouver les messages d'un utilisateur dans l'ordre antéchronologique
     */
    public function findByUserId($id){
        
        $sql = "SELECT * FROM message WHERE user_id = :id ORDER BY creation DESC";

        return self::getResults(
            self::select($sql, ['id' => $id], true),
            self::$classname
        );
    }

    /**
     * Ajout un message
     */
    public function addMessage($contenu, $user_id, $topic_id){
        $sql = "INSERT INTO message (contenu, user_id, topic_id) VALUES (:contenu, :user_id, :topic_id)";

        return self::create($sql, [
            'contenu' => $contenu,
            'user_id' => $user_id,
            'topic_id' => $topic_id
        ]);
    }

    /**
     * Edition d'un message
     */
    public function updateMessage($contenu, $id) {
        $sql = "UPDATE message SET contenu = :contenu WHERE id = :id";
        return self::update($sql, [
            'id' => $id,
            'contenu' => $contenu
        ]);
    }

    /**
     * Mettre un message en statut fantôme
     * (utilisateur supprimé)
     */
    public function nullifyMessage($id) {
        $sql = "UPDATE message SET user_id = 11 WHERE id = :id";
        return self::update($sql, [
            'id' => $id
        ]);
    }

    /**
     * Trouver tous les messages en fonction de l'ID utilisateur
     */
    public function findAllByUserId($id) {
        $sql = "SELECT * FROM message WHERE user_id = :id";

        return self::getResults(
            self::select($sql, ['id' => $id], true),
            self::$classname
        );
    }

    /**
     * Trouver tous les messages d'un topic dans l'ordre chronologique
     */
    public function findAllByTopicId($topic_id){
        $sql = "SELECT * FROM message WHERE topic_id = :id ORDER BY creation ASC";

        return self::getResults(
            self::select($sql, ['id' => $topic_id], true),
            self::$classname
        );
    }

    /**
     * Supprimer un message
     */
    public function deleteMessage($id) {
        $sql = "DELETE FROM message WHERE id = :id";
        return self::delete($sql, ['id' => $id]);
    }
}