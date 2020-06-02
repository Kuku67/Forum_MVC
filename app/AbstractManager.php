<?php
namespace App;

abstract class AbstractManager {

    private static $connection;

    protected static function connect(){

        self::$connection = DAO::getConnection();

    }

    // Me permet d'être certain d'obtenir un objet seul
    protected static function getOneOrNullResult($row, $class){
        
        if($row != null){
            return new $class($row);
        }
        return null;
    }

    // Me permet d'être certain que toutes les données retournées
    // Seront instanciées
    protected static function getResults($rows, $class){
        
        $results = [];
        
        if($rows != null){
            foreach($rows as $row){
                $results[] = new $class($row);
            }
        }
        return $results;
    }

    // Fonction parente, permettant une selection générale, avec la possibilité
    // de choisir si l'on veut une retour de table unique, ou multiple
    protected static function select($sql, $params = null, $multiple = true){
        $stmt = self::$connection->prepare($sql);
        $stmt->execute($params);

        if($multiple){
            return $stmt->fetchAll();
        }
        return $stmt->fetch();
    }

    // Fonction de création d'enregistrement classique
    protected static function create($sql, $params) {

        $stmt = self::$connection->prepare($sql);
        return $stmt->execute($params);
    }

    // Fonction de mise à jour
    protected static function update($sql, $params) {

        $stmt = self::$connection->prepare($sql);
        return $stmt->execute($params);
    }

    // Fonction de suppression d'un enregistrement
    protected static function delete($sql, $params) {
        
        $stmt = self::$connection->prepare($sql);
        return $stmt->execute($params);
    }
}