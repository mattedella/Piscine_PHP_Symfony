<?php

require_once "HotBeverage.php";

class Tea extends HotBeverage {

    private $description;
    private $commentaire;
    
    public function __construct() {
        $this->nom = "Tea";
        $this->prix = 1.50;
        $this->resistance = 5;
        $this->description = "A soothing beverage made from the leaves of the Camellia sinensis plant. It is typically served hot and can be enjoyed plain or with various additives such as milk, sugar, or lemon. Tea is known for its calming properties and is often consumed for relaxation or as a social drink.";
        $this->commentaire = "Tea is a popular beverage enjoyed by many people around the world.";
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }
}


?>