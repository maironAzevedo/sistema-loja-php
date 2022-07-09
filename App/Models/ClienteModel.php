<?php

use App\Core\BaseModel;

class ClienteModel extends BaseModel{

    public function create($cliente)
    {
        try { // conexão com a base de dados
            $sql = "INSERT INTO clientes(nome, cpf, endereco, bairro, cidade, uf, cep, telefone, email) VALUES (?,?,?,?,?,?,?,?,?)";
            $conn = ClienteModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$cliente->getNome());
            $stmt->bindValue(2,$cliente->getCpf());
            $stmt->bindValue(3,$cliente->getEndereco());
            $stmt->bindValue(4,$cliente->getBairro());
            $stmt->bindValue(5,$cliente->getCidade());
            $stmt->bindValue(6,$cliente->getUf());
            $stmt->bindValue(7,$cliente->getCep());
            $stmt->bindValue(8,$cliente->getTelefone());
            $stmt->bindValue(9,$cliente->getEmail());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "SELECT * FROM clientes WHERE id = ?";
            $conn = ClienteModel::getConexao();
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
            $sql = "SELECT * FROM clientes";
            $conn = ClienteModel::getConexao();
            $stmt = $conn->query($sql);
            $conn = null;
            return $stmt;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function update($cliente)
    {
        try {
            $sql = "UPDATE clientes SET nome = ?, cpf = ?, endereco = ?, bairro = ?, cidade = ?, uf = ?, cep = ?, telefone = ?,email = ? WHERE id = ?";
            $conn = ClienteModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$cliente->getNome());
            $stmt->bindValue(2,$cliente->getCpf());
            $stmt->bindValue(3,$cliente->getEndereco());
            $stmt->bindValue(4,$cliente->getBairro());
            $stmt->bindValue(5,$cliente->getCidade());
            $stmt->bindValue(6,$cliente->getUf());
            $stmt->bindValue(7,$cliente->getCep());
            $stmt->bindValue(8,$cliente->getTelefone());
            $stmt->bindValue(9,$cliente->getEmail());
            $stmt->bindValue(10,$cliente->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM clientes WHERE id = ?";
            $conn = ClienteModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getTotalClientes()
    {
        try {
            $sql = "SELECT count(*) as total FROM clientes";
            $conn = ClienteModel::getConexao();
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
            $sql = "SELECT * FROM clientes LIMIT ?,?";
            $conn = ClienteModel::getConexao();
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