<?php

require_once "database.php";

class Category{
    public $id = "";
    public $name = "";

    protected $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function fetchAllCategories(){
        $sql = "SELECT * FROM category ORDER BY name ASC";
        $query = $this->db->connect()->prepare($sql);

        if($query->execute()){
            return $query->fetchAll();
        }else{
            return NULL;
        }
    }
}

// $obj = new Category();
// var_dump($obj->fetchAllCategories());