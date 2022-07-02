<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class User extends BaseController
{

    protected $filters = [
        'nome' => 'trim|sanitize_string|upper_case',
        'email' => 'trim|sanitize_email|lower_case',
        'senha' => 'trim|sanitize_string|lower_case'
    ];

    protected $rules = [
        'nome'    => 'required|min_len,2|max_len,40',
        'email'  => 'required|valid_email',
        'senha'    => 'required|min_len,3',
    ];

    function __construct()
    {
        session_start();
        if (!Funcoes::usuarioLogado()) :
            Funcoes::redirect("Home");
        endif;
    }

    public function index($numPag = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $this->view('user/index', [], 'user/userjs');
        else :
            Funcoes::redirect("Home");
        endif;
    }


    public function ajax_lista($data)
    {

        $numPag = $data['numPag'];

        // calcula o offset
        $offset = ($numPag - 1) * REGISTROS_PAG;

        $userModel = $this->model("UserModel");

        // obtém a quantidade total de registros na base de dados
        $total_registros = $userModel->getTotalUsuarios();

        // calcula a quantidade de páginas - ceil — Arredonda frações para cima
        $total_paginas = ceil($total_registros / REGISTROS_PAG);

        // obtém os registros referente a página
        $lista_usuarios = $userModel->getRegistroPagina($offset, REGISTROS_PAG)->fetchAll(\PDO::FETCH_ASSOC);

        $corpoTabela = "";

        if (!empty($lista_usuarios)) :
            foreach ($lista_usuarios as $user) {
                $corpoTabela .= "<tr>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($user['nome'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities($user['email'], ENT_QUOTES, 'UTF-8') . "</td>";
                $corpoTabela .= "<td>" . '<button type="button" id="btAlterar" data-hashid="' . $user['hashid'] . '" class="btn btn-outline-primary">Alterar</button>
                                          <button type="button" id="btExcluir" data-hashid="' . $user['hashid'] . '" data-nome="' . $user['nome'] . '"class="btn btn-outline-primary">Excluir</button>'
                    . "</td>";
                $corpoTabela .= "</tr>";
            }

            $links = '<nav aria-label="Page navigation example">';
            $links .= '<ul class="pagination">';

            for ($page = 1; $page <= $total_paginas; $page++) {
                $links .= '<li class="page-item"><a class="page-link link-navegacao" href="javascript:load_data(' . $page . ')">' . $page . '</a></li>';
            }
            $links .= '  </ul></nav>';

        else :
            $corpoTabela = "<tr>Não há usuarios</tr>";
        endif;

        $data = [];
        $data["TotalRegistros"] = $total_registros;
        $data["TotalPaginas"] = $total_paginas;
        $data["corpoTabela"] = $corpoTabela;
        $data["links"] = $links;
        $data['status'] = true;
        echo json_encode($data);
        exit();
    }

    // ***********************************************************************
    // chama a view para entrada dos dados do usuario
    public function incluir()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
            // devolve os dados 
            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            echo json_encode($data);
            exit();
        else :
            Funcoes::redirect("Home");
        endif;
    }

    public function gravarInclusao()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :

            if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :

                $validacao = new Validador("pt-br");
                $post_filtrado = $validacao->filter($_POST, $this->filters);
                $post_validado = $validacao->validate($post_filtrado, $this->rules);

                if ($post_validado === true) :  // verificar dados do usuario

                    $hash_senha = password_hash($_POST['senha'], PASSWORD_ARGON2I); // gerar hash senha enviada

                    $usuario = new \App\models\Usuario(); // criar uma instância de usuário
                    $usuario->setNome($_POST['nome']);   // setar os valores
                    $usuario->setEmail($_POST['email']);
                    $usuario->setSenha($hash_senha);
                    $userModel = $this->model("UserModel"); 
                    $chaveGerada = $userModel->create($usuario); // incluir usuário no BD
                    
                    $hashId = hash('sha512', $chaveGerada);  // calcular o hash da id (chave primária) gerada
                    $userModel->createHashID($chaveGerada, $hashId);

                    $data['status'] = true;          // retornar inclusão realizada
                    echo json_encode($data);
                    exit();
                else :  // validação dos dados falhou
                    $erros = $validacao->get_errors_array();  // obter erros de validação
                    $erros = implode("<br>", $erros);         // gerar uma string com os erros
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $data['token'] = $_SESSION['CSRF_token'];  // gerar CSRF
                    $data['status'] = false;        // retornar erros
                    $data['erros'] = $erros;
                    echo json_encode($data);
                    exit();
                endif;
            else :
                die("Erro 404");
            endif;

        else :
            Funcoes::redirect("Home");
        endif;
    }

    // ***********************************************************************
    public function alterarUsuario($data)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            // o controlador receber o parâmetro como um array $data['hashID']
            $hashID = $data['hashID'];

            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

            $userModel = $this->model("UserModel");

            $usuario = $userModel->getHashID($hashID);

            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            $data['nome'] = $usuario['nome'];
            $data['email'] = $usuario['email'];
            $data['hashid'] =  $hashID;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }

    public function gravarAlterar()
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :

            if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :

                $filters = [
                    'nome_alteracao' => 'trim|sanitize_string|upper_case',
                    'email_alteracao' => 'trim|sanitize_email|lower_case'
                ];

                $rules = [
                    'nome_alteracao'    => 'required|min_len,2|max_len,40',
                    'email_alteracao'  => 'required|valid_email'
                ];

                $validacao = new Validador("pt-br");

                $post_filtrado = $validacao->filter($_POST, $filters);
                $post_validado = $validacao->validate($post_filtrado, $rules);

                if ($post_validado === true) :  // verificar dados do usuario

                    // criando um objeto usuário
                    $usuario = new \App\models\Usuario();
                    $usuario->setNome($_POST['nome_alteracao']);
                    $usuario->setEmail($_POST['email_alteracao']);
                    $usuario->setHashId($_POST['hashid_alteracao']);

                    $userModel = $this->model("UserModel");

                    $userModel->update($usuario);

                    $data['status'] = true;
                    echo json_encode($data);
                    exit();


                else :
                    $erros = $validacao->get_errors_array();
                    $erros = implode("<br>", $erros);
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $userModel = $this->model("UserModel");
                    $usuario = $userModel->getHashID($_POST['hashid_alteracao']);

                    $data['status'] = true;
                    $data['nome'] = $usuario['nome'];
                    $data['email'] = $usuario['email'];
                    $data['hashid'] =  $_POST['hashid_alteracao'];
                    $data['token'] = $_SESSION['CSRF_token'];
                    $data['status'] = false;
                    $data['erros'] = $erros;
                    echo json_encode($data);
                    exit();
                endif;
            else :
                die("Erro 404");
            endif;

        else :
            Funcoes::redirect("Home");
        endif;
    }

    // ***********************************************************************


    public function excluirUsuario($data)
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $hashID = $data['hashID'];

            $userModel = $this->model("UserModel");

            $userModel->delete($hashID);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }
}
