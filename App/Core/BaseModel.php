<?php

namespace App\Core;

use \PDO;
use \PDOException;

// classe responsável pela conecção com o BD
class BaseModel
{
    public static function getConexao()
    {
        $banco = "mysql:host=" . HOST . ";dbname=" . DB;

        $opcoes = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", //Colocado para corrigir bug de retornar o quantidade_disponível de produtos
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8"]; //Colocado para corrigir bug de retornar o quantidade_disponível de produtos

        try { // conexão com a base de dados
            return new PDO($banco, USUARIO, SENHA, $opcoes);
        } catch (PDOException $e) {
            echo 'Conexao falhou: ' . $e->getMessage();
        }
    }
}
