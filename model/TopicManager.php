<?php
namespace Model;

use App\AbstractManager;

class TopicManager extends AbstractManager {

    private static $classname = "Model\Topic";

    public function __construct(){
        self::connect(self::$classname);
    }

    /**
     * Trouver un topic en fonction de son ID
     */
    public function findOneById($id){
        
        $sql = "SELECT * FROM sujet WHERE id = :id";

        return self::getOneOrNullResult(
            self::select($sql, ['id' => $id], false),
            self::$classname
        );
    }

    /**
     * Trouver tous les topic en fonction d'un ID utilisateur
     */
    public function findByUserId($id){
        
        $sql = "SELECT * FROM sujet WHERE user_id = :id ORDER BY creation DESC";

        return self::getResults(
            self::select($sql, ['id' => $id], true),
            self::$classname
        );
    }

    /**
     * Ajouter un topic
     */
    public function addTopic($titre, $contenu, $user_id){
        $sql = "INSERT INTO sujet (titre, contenu, user_id) VALUES (:titre, :contenu, :user_id)";

        return self::create($sql, [
            'titre' => $titre,
            'contenu' => $contenu,
            'user_id' => $user_id
        ]);
    }

    /**
     * Editer un topic
     */
    public function updateTopic($titre, $contenu, $id) {
        $sql = "UPDATE sujet SET titre = :titre, contenu = :contenu WHERE id = :id";
        return self::update($sql, [
            'id' => $id,
            'titre' => $titre,
            'contenu' => $contenu
        ]);
    }

    /**
     * Supprimer un topic
     */
    public function deleteTopic($id) {

        $sql = "DELETE FROM sujet WHERE id = :id";
        return self::delete($sql, ['id' => $id]);
    }

    /**
     * Switch verrouillage/déverrouillage d'un topic
     */
    public function lockTopic($id, $bool) {

        $sql = "UPDATE sujet SET verrouillage = :verrouillage WHERE id = :id";
        return self::update($sql, ['id' => $id, 'verrouillage' => $bool ? 1 : 0]);
    }

    /**
     * Switch résolu/non résolu d'un topic
     */
    public function resolveTopic($id, $bool) {

        $sql = "UPDATE sujet SET resolu = :resolu WHERE id = :id";
        return self::update($sql, ['id' => $id, 'resolu' => $bool ? 1 : 0]);
    }

    /**
     * Trouver tous les topics ainsi que leur nbMessages respectifs dans l'ordre antéchronologique
     */
    public function findAll(){
        $sql = "SELECT s.id, s.titre, s.contenu, s.creation, s.user_id, s.verrouillage, s.resolu, COUNT(*) AS nbMessages
                    FROM message m, sujet s
                    WHERE m.topic_id = s.id
                    GROUP BY s.id
                
                UNION
                
                SELECT s.id, s.titre, s.contenu, s.creation, s.user_id, s.verrouillage, s.resolu, 0 AS nbMessages
                    FROM sujet s
                    WHERE s.id NOT IN (SELECT topic_id FROM message)
                    GROUP BY s.id

                ORDER BY creation DESC";

        return self::getResults(
            self::select($sql, null, true),
            self::$classname
        );
    }

    /**
     * Trouver le dernier topic créé par un utilisateur
     */
    public function findLast($user_id) {
        $sql = "SELECT * FROM sujet WHERE user_id = :id ORDER BY creation DESC LIMIT 1";
        return self::getOneOrNullResult(
            self::select($sql, ['id' => $user_id], false),
            self::$classname
        );
    }

    /**
     * Mettre un topic en statut fantôme
     * (utilisateur supprimé)
     */
    public function nullifyTopic($id) {
        $sql = "UPDATE sujet SET user_id = 11 WHERE id = :id";
        return self::update($sql, [
            'id' => $id
        ]);
    }
}