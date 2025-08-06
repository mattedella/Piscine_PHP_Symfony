<?php

class HotBeverage {
    protected $nom;
    protected $prix;
    protected $resistance;

    function getName() {
        return $this->nom;
    }

    function getPrix() {
        return $this->prix;
    }

    function getResistance() {
        return $this->resistance;
    }
}

?>