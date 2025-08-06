<?php
require_once "HotBeverage.php";

class Coffee extends HotBeverage {
    private $description;
    private $commentaire;

    function __construct() {
        $this->nom = "Coffee";
        $this->prix = 1.30;
        $this->resistance = 1;
        $this->description = "beverage brewed from roasted, ground coffee beans. Darkly colored, bitter, and slightly acidic, coffee has a stimulating effect on humans, primarily due to its caffeine content, but decaffeinated coffee is also commercially available. There are also various coffee substitutes. Typically served hot, coffee has the highest sales in the world market for hot drinks.";
        $this->commentaire = "Coffee is a popular beverage enjoyed by many people around the world. It is known for its rich flavor and aroma, and is often consumed in various forms such as espresso, cappuccino, or macchiatone.";
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }
}

?>