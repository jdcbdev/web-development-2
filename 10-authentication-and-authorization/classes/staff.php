<?php

require_once 'database.php';

Class Staff extends Database{
    //attributes

    public $id;
    public $firstname;
    public $lastname;
    // for this example, roles are only staff and admin
    public $role;
    public $email;
    public $password;
    public $is_active;

    //Methods

    function addStaff(){
        $sql = "INSERT INTO staff (firstname, lastname, role, email, password, is_active) VALUES 
        (:firstname, :lastname, :role, :email, :password, :is_active);";

        $query=$this->connect()->prepare($sql);
        $query->bindParam(':firstname', $this->firstname);
        $query->bindParam(':lastname', $this->lastname);
        $query->bindParam(':role', $this->role);
        $query->bindParam(':email', $this->email);
        // Hash the password securely using password_hash
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':is_active', $this->is_active);
        
        if($query->execute()){
            return true;
        }
        else{
            return false;
        }	
    }

    function getStaffByEmail(){
        $sql = "SELECT * FROM staff WHERE email = :email;";
        $query=$this->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
        if($query->execute()){
            $data = $query->fetch();
        }
        return $data;
    }
}

?>