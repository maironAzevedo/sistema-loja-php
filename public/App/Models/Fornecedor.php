<?php

namespace App\models;

class Fornecedor
{
    private $id, $razao_social, $cnpj, $endereco, $bairro, $cidade, $uf, $cep, $telefone, $email;

    public function __construct()
    {
        $this->id = 0;
        $this->razao_social = "";
        $this->cnpj = "";
        $this->endereco = "";
        $this->bairro = "";
        $this->cidade = "";
        $this->uf = "";
        $this->cep = "";
        $this->telefone = "";
        $this->email = "";
    }

    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }

    public function getRazaoSocial ()
    {
        return $this->razao_social;
    }

    public function setRazaoSocial ($razao_social)
    {
        $this->razao_social = $razao_social;
    }

    public function getCnpj ()
    {
        return $this->cnpj;
    }

    public function setCnpj ($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    public function getEndereco ()
    {
        return $this->endereco;
    }

    public function setEndereco ($endereco)
    {
        $this->endereco = $endereco;
    }

    public function getBairro ()
    {
        return $this->bairro;
    }

    public function setBairro ($bairro)
    {
        $this->bairro = $bairro;
    }

    public function getCidade ()
    {
        return $this->cidade;
    }

    public function setCidade ($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getUf ()
    {
        return $this->uf;
    }

    public function setUf ($uf)
    {
        $this->uf = $uf;
    }

    public function getCep ()
    {
        return $this->cep;
    }

    public function setCep ($cep)
    {
        $this->cep = $cep;
    }

    public function getTelefone ()
    {
        return $this->telefone;
    }

    public function setTelefone ($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEmail ()
    {
        return $this->email;
    }

    public function setEmail ($email)
    {
        $this->email = $email;
    }
}