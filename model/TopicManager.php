<?php
namespace Model;

use App\AbstractManager;

class TopicManager extends AbstractManager {

    private static $classname = "Model\Topic";

    public function __construct(){
        self::connect(self::$classname);
    }

    public function findOneById($id){
        
        $sql = "SELECT * FROM sujet WHERE id = :id";

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

    public function addTopic($titre, $contenu, $user_id){
        $sql = "INSERT INTO sujet (titre, contenu, user_id) VALUES (:titre, :contenu, :user_id)";

        return self::create($sql, [
            'titre' => $titre,
            'contenu' => $contenu,
            'user_id' => $user_id
        ]);
    }

    public function updateTopic($titre, $contenu, $user_id, $id) {
        $sql = "UPDATE sujet SET titre = :titre, contenu = :contenu WHERE id = :id";
        return self::update($sql, [
            'id' => $id,
            'titre' => $titre,
            'contenu' => $contenu
        ]);
    }

    public function deleteTopic($id) {

        $sql = "DELETE FROM sujet WHERE id = :id";
        return self::delete($sql, ['id' => $id]);
    }

    public function lockTopic($id) {

        $sql = "UPDATE sujet SET verouillage = 1 WHERE id = :id";
        return self::update($sql, ['id' => $id]);
    }

    public function unlockTopic($id) {

        $sql = "UPDATE sujet SET verouillage = 0 WHERE id = :id";
        return self::update($sql, ['id' => $id]);
    }

    public function resolveTopic($id) {

        $sql = "UPDATE sujet SET resolu = 1 WHERE id = :id";
        return self::update($sql, ['id' => $id]);
    }

    public function unresolveTopic($id) {

        $sql = "UPDATE sujet SET resolu = 0 WHERE id = :id";
        return self::update($sql, ['id' => $id]);
    }

    public function findAll(){
        $sql = "SELECT s.id, s.titre, s.contenu, s.creation, s.user_id, s.verouillage, s.resolu, COUNT(*) AS nbMessages
                    FROM message m, sujet s
                    WHERE m.topic_id = s.id
                    GROUP BY s.id
                
                UNION
                
                SELECT s.id, s.titre, s.contenu, s.creation, s.user_id, s.verouillage, s.resolu, 0 AS nbMessages
                    FROM sujet s
                    WHERE s.id NOT IN (SELECT topic_id FROM message)
                    GROUP BY s.id

                ORDER BY creation DESC";

        return self::getResults(
            self::select($sql, null, true),
            self::$classname
        );
    }

    public function findLast($user_id) {
        $sql = "SELECT * FROM sujet WHERE user_id = :id ORDER BY creation DESC LIMIT 1";
        return self::getOneOrNullResult(
            self::select($sql, ['id' => $user_id], false),
            self::$classname
        );
    }
}