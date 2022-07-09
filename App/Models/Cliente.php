<?php

namespace App\models;

class Cliente
{
    private 
        $id,
        $nome, 
        $cpf, 
        $endereco, 
        $bairro,
        $cidade,
        $uf,
        $cep,
        $telefone,
        $email
    ;

    public function __construct()
    {
        $this->id = 0;
        $this->nome = "";
        $this->cpf = "";
        $this->endereco = "";
        $this->bairro = "";
        $this->cidade = "";
        $this->uf = "";
        $this->cep = "";
        $this->telefone = "";
        $this->email = "";
    }

    /**
     * getters & setters
     */
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
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
    public function getCpf()
    {
        return $this->cpf;
    }
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /******************* */
    public function getEndereco()
    {
        return $this->endereco;
    }
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    /******************* */
    public function getBairro()
    {
        return $this->bairro;
    }
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /******************* */
    public function getCidade()
    {
        return $this->cidade;
    }
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /******************* */
    public function getUf()
    {
        return $this->uf;
    }
    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /******************* */
    public function getCep()
    {
        return $this->cep;
    }
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /******************* */
    public function getTelefone()
    {
        return $this->telefone;
    }
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
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
}
