<?php
namespace Model;

use App\AbstractManager;

class MessageManager extends AbstractManager {

    private static $classname = "Model\Message";

    public function __construct(){
        self::connect(self::$classname);
    }

    public function findOneById($id){
        
        $sql = "SELECT * FROM message WHERE id = :id";

        return self::getOneOrNullResult(
            self::select($sql, ['id' => $id], false),
            self::$classname
        );
    }

    public function findByUserId($id){
        
        $sql = "SELECT * FROM sujet WHERE user_id = :id ORDER BY creation DESC";

        return self::getResults(
            self::select($sql, ['id' => $id], true),
            self::$classname
        );
    }

    public function addMessage($contenu, $user_id, $topic_id){
        $sql = "INSERT INTO message (contenu, user_id, topic_id) VALUES (:contenu, :user_id, :topic_id)";

        return self::create($sql, [
            'contenu' => $contenu,
            'user_id' => $user_id,
            'topic_id' => $topic_id
        ]);
    }

    public function updateMessage($contenu, $user_id, $id) {
        $sql = "UPDATE message SET contenu = :contenu WHERE id = :id";
        return self::update($sql, [
            'id' => $id,
            'contenu' => $contenu
        ]);
    }

    public function findAll($topic_id){
        $sql = "SELECT * FROM message WHERE topic_id = :id ORDER BY creation ASC";

        return self::getResults(
            self::select($sql, ['id' => $topic_id], true),
            self::$classname
        );
    }

    public function deleteMessage($id) {
        $sql = "DELETE FROM message WHERE id = :id";
        return self::delete($sql, ['id' => $id]);
    }
}