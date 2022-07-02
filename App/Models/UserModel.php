<?php

use App\Core\BaseModel;

class UserModel extends BaseModel
{

    public function create($usuario)
    {
        try { // conexão com a base de dados
            $sql = "INSERT INTO usuarios(nome,email,senha) VALUES (?,?,?)";
            $conn = UserModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $usuario->getNome());
            $stmt->bindValue(2, $usuario->getEmail());
            $stmt->bindValue(3, $usuario->getSenha());
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
            $sql = "SELECT * FROM USUARIOS WHERE id = ?";
            $conn = UserModel::getConexao();
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

            $sql = "SELECT * FROM USUARIOS WHERE hashid like ?";
            $conn = UserModel::getConexao();
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
            $sql = "SELECT * FROM USUARIOS";
            $conn = UserModel::getConexao();
            $stmt = $conn->query($sql);
            $conn = null;
            return $stmt;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function update($usuario)
    {
        try {
            $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE hashid = ?";
            $conn = UserModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $usuario->getNome());
            $stmt->bindValue(2, $usuario->getEmail());
            $stmt->bindValue(3, $usuario->getHashId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function delete($hashId)
    {
        try {
            $sql = "DELETE FROM usuarios WHERE hashid = ?";
            $conn = UserModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1,$hashId);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }


    public function getTotalUsuarios()
    {
        try {
            $sql = "SELECT count(*) as total FROM USUARIOS";
            $conn = UserModel::getConexao();
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
            $sql = "SELECT * FROM USUARIOS LIMIT ?,?";
            $conn = UserModel::getConexao();
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

       public function getUsuarioEmail($email)
    {

        try {
            $sql = "Select * from usuarios where email = ? limit 1";
            // obter a conecção e preparar o comando sql (PDO)
            $conn = UserModel::getConexao();
            $stmt = $conn->prepare($sql);
             // passando parâmteros
            $stmt->bindValue(1, $email);
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
            $sql = "UPDATE usuarios SET hashid = ? WHERE id = ?";
            $conn = UserModel::getConexao();

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
