<?php

require_once "database.php";

class Category extends Database{
    public $id = "";
    public $name = "";

    //load the list of category, no longer static
    public function getCategories() {
        $sql = "SELECT * FROM category ORDER BY name ASC";
        $query = $this->connect()->prepare($sql);
        
        if ($query->execute()) {
            return $query->fetchAll();
        } else {
            return null;
        }
    }

}