<?php
namespace App;

abstract class AbstractManager {

    private static $connection;

    protected static function connect(){

        self::$connection = DAO::getConnection();

    }

    protected static function getOneOrNullResult($row, $class){
        
        if($row != null){
            return new $class($row);
        }
        return null;
    }

    protected static function getResults($rows, $class){
        
        $results = [];
        
        if($rows != null){
            foreach($rows as $row){
                $results[] = new $class($row);
            }
        }
        return $results;
    }

    protected static function select($sql, $params = null, $multiple = true){

        $stmt = self::$connection->prepare($sql);
        $stmt->execute($params);

        if($multiple){
            return $stmt->fetchAll();
        }
        return $stmt->fetch();
    }

    protected static function create($sql, $params){

        $stmt = self::$connection->prepare($sql);
        return $stmt->execute($params);
    }

    protected static function update($sql, $params){
        
        $stmt = self::$connection->prepare($sql);
        return $stmt->execute($params);
    }

    protected static function delete($sql, $params){
        try {
            $stmt = self::$connection->prepare($sql);
            return $stmt->execute($params);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}