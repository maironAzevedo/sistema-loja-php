<?php

use App\Core\BaseModel;

class FuncionarioModel extends BaseModel
{
    public function create($funcionario)
    {
        try {
            $sql = "INSERT INTO funcionarios(nome,cpf,senha,papel) VALUES (?,?,?,?)";
            $conn = FuncionarioModel::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $funcionario->getNome());
            $stmt->bindValue(2, $funcionario->getEmail());
            $stmt->bindValue(3, $funcionario->getSenha());
            $stmt->bindValue(4, $funcionario->getPapel());
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
            $sql = "SELECT * FROM FUNCIONARIOS";
            $conn = FuncionarioModel::getConexao();
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
            $sql = "UPDATE funcionarios SET nome = ?, papel = ? WHERE id = ?";
            $conn = FuncionarioModel::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $usuario->getNome());
            $stmt->bindValue(2, $usuario->getPapel());
            $stmt->bindValue(3, $usuario->getId());
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $conn = FuncionarioModel::getConexao();

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
}

?>