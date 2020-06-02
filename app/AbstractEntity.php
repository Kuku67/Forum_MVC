<?php

namespace App;

abstract class AbstractEntity {

    protected static function hydrate($data, $object) {
        // Pour tous les champs et leur valeur
        foreach($data as $field => $value) {
            // Je casse la chaine des champs
            $fieldArray = explode("_", $field);
            // Si le mot ID subvient en 2nd emplacement
            // J'ai donc une clé étrangère
            if(isset($fieldArray[1]) && $fieldArray[1] === 'id') {
                // Je transforme ce champ[0] en nom de classe
                $classname = "Model\\".ucfirst($fieldArray[0])."Manager";
                $manager = new $classname;
                // Le champs[0] devient une propriété
                $field = $fieldArray[0];
                // Et je boucle autant de fois que nécessaire l'hydratateur
                $value = $manager->findOneById($value);

            }

            // On défini le préfixe commun à tous les setters 
            $method = "set".ucfirst($field);
            // Si la méthode existe pour chaque nouvel objet, je "set" leurs attributs
            if(method_exists($object, $method)) {

                $object->$method($value);
                
            }
        }
    }
}