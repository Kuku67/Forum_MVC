<?php
namespace Model;

use App\AbstractEntity;

class Topic extends AbstractEntity {

    private $id;
    private $titre;
    private $contenu;
    private $creation;
    private $user;
    private $verrouillage;
    private $resolu;
    private $nbMessages;

    public function __construct($data){
        parent::hydrate($data, $this);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
            return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
            $this->id = $id;

            return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreation($format)
    {
            return $this->creation->format($format);
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreation($created_at)
    {
        $this->creation = new \DateTime($created_at);

        return $this;
    }

    public function __toString(){
        return $this->username;
    }

    /**
     * Get the value of titre
     */ 
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @return  self
     */ 
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get the value of contenu
     */ 
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set the value of contenu
     *
     * @return  self
     */ 
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get the value of verrouillage
     */ 
    public function getVerrouillage()
    {
        return $this->verrouillage;
    }

    /**
     * Set the value of verrouillage
     *
     * @return  self
     */ 
    public function setVerrouillage($verrouillage)
    {
        $this->verrouillage = $verrouillage;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of nbMessages
     */ 
    public function getNbMessages()
    {
        return $this->nbMessages;
    }

    /**
     * Set the value of nbMessages
     *
     * @return  self
     */ 
    public function setNbMessages($nbMessages)
    {
        $this->nbMessages = $nbMessages;

        return $this;
    }

    /**
     * Get the value of resolu
     */ 
    public function getResolu()
    {
        return $this->resolu;
    }

    /**
     * Set the value of resolu
     *
     * @return  self
     */ 
    public function setResolu($resolu)
    {
        $this->resolu = $resolu;

        return $this;
    }
}