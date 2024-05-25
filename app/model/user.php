<?php
class Users{
    private $id;
    private $username;
    private $name;
    private $email;
    private $phoneno;

    // Constructor
    public function __construct($id, $username, $name, $email, $phoneno){
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->phoneno = $phoneno;
    }

    public function getID(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPhoneno(){
        return $this->phoneno;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setPhoneno($phoneno){
        $this->phoneno = $phoneno;
    }
}

class BusinessOwner{
    private $company;
    //private $subscription;
    private $user;
    private $password;
    private $suspend_status;

    public function __construct($companyname, $id, $username, $name, $email, $phoneno, $password){
        $user = new Users($id, $username, $name, $email, $phoneno);
        $company = new Company($companyname);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->company = $company;
        //$this->subscription = $subscription;
        $this->user = $user;
        $this->password = $hash;
        $this->suspend_status = false;
    }

    function getCompany(){
        return $this->company;
    }

    function getUser(){
        return $this->user;
    }
    
    function getSuspendStatus(){
        return $this->suspend_status;
    }

    function setCompany($company){
        $this->company = $company;
    }

    function setUser($user){
        $this->user = $user;
    }

    function setPassword($password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hash;
    }

    function setSuspendStatus($status){
        $this->suspend_status = $status;
    }
}

class EndUser{
    private $name;
    private $company;
    private $suspend_status;

    public function __construct($name, $companyname){
        $company = new Company($companyname);

        $this->name = $name;
        $this->company = $company;
        $this->suspend_status = false;
    }

    function getName(){
        return $this->name;
    }

    function getCompany(){
        return $this->company;
    }

    function getSuspendStatus(){
        return $this->suspend_status;
    }

    function setName($name){
        $this->name = $name;
    }

    function setCompany($company){
        $this->company = $company;
    }

    function setSuspendStatus($status){
        $this->suspend_status = $status;
    }
}

class Company{
    private $name;

    public function __construct($name){
        $this->name = $name;
    }

    function getName(){
        return $this->name;
    }

    function setName($name){
        $this->name = $name;
    }
}

class Subscription{
    private $name;
    private $price;
    private $description;

    public function __construct($name, $price, $description){
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    function getName(){
        return $this->name;
    }

    function getPrice(){
        return $this->price;
    }

    function getDescription(){
        return $this->description;
    }

    function setName($name){
        $this->name = $name;
    }

    function setPrice($price){
        $this->price = $price;
    }

    function setDescription($description){
        $this->description = $description;
    }
}
?>