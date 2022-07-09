<?php

use App\Core\BaseModel;

class VendaModel extends BaseModel{

    public function create($venda)
    {
        try { // conexão com a base de dados
            $sql = "INSERT INTO vendas(quantidade_venda, data_venda, valor_venda, id_cliente, id_produto, id_funcionario) VALUES (?,?,?,?,?,?)";
            $conn = VendaModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$venda->getQuantidadeVenda());
            $stmt->bindValue(2,$venda->getDataVenda());
            $stmt->bindValue(3,$venda->getValorVenda());
            $stmt->bindValue(4,$venda->getIdCliente());
            $stmt->bindValue(5,$venda->getIdProduto());
            $stmt->bindValue(6,$venda->getIdFuncionario());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "SELECT * FROM vendas WHERE id = ?";
            $conn = VendaModel::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function read()
    {
        try {
            $sql = "SELECT * FROM vendas";
            $conn = VendaModel::getConexao();
            $stmt = $conn->query($sql);
            $conn = null;
            return $stmt;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function update($venda)
    {
        try {
            $sql = "UPDATE vendas SET quantidade_venda = ?, data_venda = ?, valor_venda = ?, id_cliente = ?, id_produto = ?, id_funcionario = ? WHERE id = ?";
            $conn = VendaModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$venda->getQuantidadeVenda());
            $stmt->bindValue(2,$venda->getDataVenda());
            $stmt->bindValue(3,$venda->getValorVenda());
            $stmt->bindValue(4,$venda->getIdCliente());
            $stmt->bindValue(5,$venda->getIdProduto());
            $stmt->bindValue(6,$venda->getIdFuncionario());
            $stmt->bindValue(7,$venda->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM vendas WHERE id = ?";
            $conn = VendaModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getTotalVendas()
    {
        try {
            $sql = "SELECT count(*) as total FROM vendas";
            $conn = VendaModel::getConexao();
            $stmt = $conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
            $conn = null;
            return $stmt['total'];
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getRegistroPagina($offset, $numRegistrosPag)
    {
        try {
            $sql = "SELECT * FROM vendas LIMIT ?,?";
            $conn = VendaModel::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $offset, PDO::PARAM_INT);
            $stmt->bindParam(2, $numRegistrosPag, PDO::PARAM_INT);
            $stmt->execute();
            //$stmt->debugDumpParams();  <- usando para depuração
            $conn = null;
            return $stmt;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
}