<?php

namespace App\models;

class Compra
{
    private $id, $quantidade_compra, $data_compra, $valor_compra, $id_fornecedor, $id_produto, $id_funcionario;

    public function __construct()
    {
        $this->id = 0;
        $this->quantidade_compra = 0;
        $this->data_compra = "";
        $this->valor_compra = 0;
        $this->id_fornecedor = "";
        $this->id_produto = "";
        $this->id_funcionario = "";
    }

    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }

    public function getQuantidadeCompra ()
    {
        return $this->quantidade_compra;
    }

    public function setQuantidadeCompra ($quantidade_compra)
    {
        $this->quantidade_compra = $quantidade_compra;
    }

    public function getDataCompra ()
    {
        return $this->data_compra;
    }

    public function setDataCompra ($data_compra)
    {
        $this->data_compra = $data_compra;
    }

    public function getValorCompra ()
    {
        return $this->valor_compra;
    }

    public function setValorCompra ($valor_compra)
    {
        $this->valor_compra = $valor_compra;
    }

    public function getIdFornecedor ()
    {
        return $this->id_fornecedor;
    }

    public function setIdFornecedor ($id_fornecedor)
    {
        $this->id_fornecedor = $id_fornecedor;
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