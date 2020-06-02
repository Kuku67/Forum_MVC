<?php
// Classe dont le métier est tout simplement de fournir une connexion à la BDD.
namespace App;

abstract class DAO {

    const DB_HOST = "localhost:3306";
    const DB_NAME = "blogmvc";
    const DB_USER = "root";
    const DB_PASS = "";

    public static function getConnection() {

        try {
            $pdo = new \PDO(
                'mysql:host='.self::DB_HOST.';dbname='.self::DB_NAME,
                self::DB_USER,
                self::DB_PASS,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
            return $pdo;
        } catch(\Exception $e){
            echo $e->getMessage();
            die();
        }
    }
}