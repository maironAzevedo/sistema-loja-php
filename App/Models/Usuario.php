<?php

namespace App\models;

class Usuario
{
    private $id, $nome, $email, $senha;

    public function __construct()
    {
        $this->id = 0;
        $this->nome = "";
        $this->email= "";
        $this->senha = "";
        $this->hashid = "";
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    /******************* */
    public function getHashId()
    {
        return $this->hashid;
    }
    public function setHashId($hashid)
    {
        $this->hashid = $hashid;
    }
    /******************* */
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /******************* */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /******************* */
    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
}
