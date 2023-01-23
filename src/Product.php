<?php

namespace App;

class Product
{
    public $name;
    public $qte;
    public $price;

    public function __construct($name,$qte,$price)
    {
        $this->name = $name;
        $this->qte  = $qte;
        $this->price = $price;
    }

}