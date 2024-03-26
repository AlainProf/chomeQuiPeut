<?php
namespace App\Classe;

class ConnexionChomeur
{
    private $nom;

    public function __construct()
    {
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setnom($n)
    {
       $this->nom = $n;
    }
}