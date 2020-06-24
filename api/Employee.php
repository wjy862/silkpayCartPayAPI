<?php
//namespace fr\fingertips\entity;


/**
 * Description of Employee
 *
 * @author leowj
 */
class Employee {
    private $idEmp;
    private $nom; 
    private $prenom;
    private $email;
    private $mdp;
    private $idType;
 
 
    public function __construct($idEmp, $nom,$prenom,$email, $mdp,$idType) {
     $this ->idEmp = $idEmp;
     $this ->nom = $nom;
     $this ->prenom = $prenom;
     $this ->email = $email;
     $this ->mdp = $mdp;
     $this ->idType = $idType;
    }

    public function setIdEmp($idEmp){
     $this ->idEmp = $idEmp;
     }
     public function getIdEmp (){
     return $this->idEmp  ;
    } 
  
     public function setNom($nom){
     $this ->nom = $nom;
     }
     public function getNom (){
     return $this->nom ;
    } 
    
     public function setPrenom($prenom){
     $this ->prenom = $prenom;
     }
     public function getPrenom (){
     return $this->prenom  ;
    } 
    
     public function setEmail($email){
     $this ->email = $email;
     }
     public function getEmail (){
     return $this->email  ;
    } 
    
     public function setMdp($mdp){
     $this ->mdp = $mdp;
  }
    public function getMdp (){
     return $this->mdp  ;
    } 
    public function setIdType($idType){
     $this ->idType = $idType;
  }
    public function getIdType (){
     return $this->idType;
    } 
}
