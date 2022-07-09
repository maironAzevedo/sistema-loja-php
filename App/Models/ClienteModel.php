<?php

use App\Core\BaseModel;

class ClienteModel extends BaseModel
{
    public function create($cliente)
    {
        try {
            $sql = "INSERT INTO clientes(nome,cpf,endereco,bairro,cidade,uf,cep,telefone,email) VALUES (?,?,?,?,?,?,?,?,?)";
            $conn = ClienteModel::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $cliente->getNome());
            $stmt->bindValue(2, $cliente->getCpf());
            $stmt->bindValue(3, $cliente->getEndereco());
            $stmt->bindValue(4, $cliente->getBairro());
            $stmt->bindValue(5, $cliente->getCidade());
            $stmt->bindValue(6, $cliente->getUf());
            $stmt->bindValue(7, $cliente->getCep());
            $stmt->bindValue(8, $cliente->getTelefone());
            $stmt->bindValue(9, $cliente->getEmail());
            $stmt->execute();
            $chaveGerada = $conn->lastInsertId();
            $conn = null;
            return $chaveGerada;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function read()
    {
        try {
            $sql = "SELECT * FROM CLIENTES";
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
            $sql = "UPDATE clientes SET nome = ?, endereco = ?, bairro = ?, cidade = ?, uf = ?, cep = ?, telefone = ?, email = ?, WHERE id = ?";
            $conn = ClienteModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $cliente->getNome());
            $stmt->bindValue(3, $cliente->getEndereco());
            $stmt->bindValue(4, $cliente->getBairro());
            $stmt->bindValue(5, $cliente->getCidade());
            $stmt->bindValue(6, $cliente->getUf());
            $stmt->bindValue(7, $cliente->getCep());
            $stmt->bindValue(8, $cliente->getTelefone());
            $stmt->bindValue(9, $cliente->getEmail());
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
            $stmt->bindValue(1,$id);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getFuncionarioPorCPF($cpf)
    {

        try {
            $sql = "Select * from funcionarios where cpf = ? limit 1";
            // obter a conecção e preparar o comando sql (PDO)
            $conn = ClienteModel::getConexao();
            $stmt = $conn->prepare($sql);
             // passando parâmteros
            $stmt->bindValue(1, $cpf);
            $stmt->execute();
            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $resultset[0];
            else :
                return []; // retornado array vazio... não há registros no BD    
            endif;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
}
