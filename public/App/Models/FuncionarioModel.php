<?php

use App\Core\BaseModel;

class FuncionarioModel extends BaseModel
{

    public function create($funcionario)
    {
        try { // conexão com a base de dados
            $sql = "INSERT INTO funcionarios(nome, cpf, senha, papel) VALUES (?,?,?,?)";
            $conn = FuncionarioModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$funcionario->getNome());
            $stmt->bindValue(2,$funcionario->getCpf());
            $stmt->bindValue(3,$funcionario->getSenha());
            $stmt->bindValue(4,$funcionario->getPapel());
            $stmt->execute();
            $chaveGerada = $conn->lastInsertId();
            $conn = null;
            return $chaveGerada;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "SELECT * FROM funcionarios WHERE id = ?";
            $conn = FuncionarioModel::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getHashID($hashid)
    {
        try {

            $sql = "SELECT * FROM funcionarios WHERE hashid like ?";
            $conn = FuncionarioModel::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $hashid, PDO::PARAM_STR);
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
            $sql = "SELECT * FROM funcionarios";
            $conn = FuncionarioModel::getConexao();
            $stmt = $conn->query($sql);
            $conn = null;
            return $stmt;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function update($funcionario)
    {
        try {
            $sql = "UPDATE funcionarios SET nome = ?, cpf = ?, senha = ?, papel = ? WHERE hashid = ?";
            $conn = FuncionarioModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$funcionario->getNome());
            $stmt->bindValue(2,$funcionario->getCpf());
            $stmt->bindValue(3,$funcionario->getSenha());
            $stmt->bindValue(4,$funcionario->getPapel());
            $stmt->bindValue(5,$funcionario->getHashId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function delete($hashId)
    {
        try {
            $sql = "DELETE FROM funcionarios WHERE hashid = ?";
            $conn = FuncionarioModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$hashId);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }


    public function getTotalFuncionarios()
    {
        try {
            $sql = "SELECT count(*) as total FROM funcionarios";
            $conn = FuncionarioModel::getConexao();
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
            $sql = "SELECT * FROM funcionarios LIMIT ?,?";
            $conn = FuncionarioModel::getConexao();
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

       public function getFuncionarioCpf($cpf)
    {

        try {
            $sql = "Select * from funcionarios where cpf = ? limit 1";
            // obter a conecção e preparar o comando sql (PDO)
            $conn = FuncionarioModel::getConexao();
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

    public function createHashID($id, $hashId)
    {
        try {
            $sql = "UPDATE funcionarios SET hashid = ? WHERE id = ?";
            $conn = FuncionarioModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $hashId);
            $stmt->bindValue(2, $id);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
}
