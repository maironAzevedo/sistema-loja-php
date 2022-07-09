<?php

namespace App\models;

class Categoria
{
    private $id, $nome_categoria;

    public function __construct()
    {
        $this->id = 0;
        $this->nome_categoria = "";
    }

    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }

    public function getNomeCategoria ()
    {
        return $this->nome_categoria;
    }

    public function setNomeCategoria ($nome_categoria)
    {
        $this->nome_categoria = $nome_categoria;
    }
}