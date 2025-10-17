<?php

require_once 'staff.php';

class Account extends Staff{

    public $id;
    public $email;
    public $password;

    function login(){
        $sql = "SELECT * FROM staff WHERE email = :email and is_active = 1 LIMIT 1;";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':email', $this->email);
    
        if ($query->execute()) {
            $accountData = $query->fetch();
    
            if ($accountData && password_verify($this->password, $accountData['password'])) {
                $this->id = $accountData['id'];
                return true;
            }
        }
    
        return false;
    }

}

?>