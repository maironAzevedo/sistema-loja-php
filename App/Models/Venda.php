<?php

namespace App\models;

class Venda
{
    private $id, $quantidade_venda, $data_venda, $valor_venda, $id_cliente, $id_produto, $id_funcionario;

    public function __construct()
    {
        $this->id = 0;
        $this->quantidade_venda = 0;
        $this->data_venda = "";
        $this->valor_venda = 0.0;
        $this->id_cliente = 0;
        $this->id_produto = 0;
        $this->id_funcionario = 0;
    }

    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }

    public function getQuantidadeVenda ()
    {
        return $this->quantidade_venda;
    }

    public function setQuantidadeVenda ($quantidade_venda)
    {
        $this->quantidade_venda = $quantidade_venda;
    }

    public function getDataVenda ()
    {
        return $this->data_venda;
    }

    public function setDataVenda ($data_venda)
    {
        $this->data_venda = $data_venda;
    }

    public function getValorVenda ()
    {
        return $this->valor_venda;
    }

    public function setValorVenda ($valor_venda)
    {
        $this->valor_venda = $valor_venda;
    }

    public function getIdCliente ()
    {
        return $this->id_cliente;
    }

    public function setIdCliente ($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }

    public function getIdProduto ()
    {
        return $this->id_produto;
    }

    public function setIdProduto ($id_produto)
    {
        $this->id_produto = $id_produto;
    }

    public function getIdFuncionario ()
    {
        return $this->id_funcionario;
    }

    public function setIdFuncionario ($id_funcionario)
    {
        $this->id_funcionario = $id_funcionario;
    }
}