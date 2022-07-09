<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class Cliente extends BaseController
{

    protected $filters = [
        'nome' => 'trim|sanitize_string',
        'cpf' => 'trim|sanitize_string',
        'endereco' => 'trim|sanitize_string',
        'bairro' => 'trim|sanitize_string',
        'cidade' => 'trim|sanitize_string',
        'uf' => 'trim|sanitize_string|upper_case',
        'cep' => 'trim',
        'telefone' => 'trim',
        'email' => 'trim|sanitize_email|lower_case'
    ];

    protected $rules = [
        'nome'    => 'required|min_len,2|max_len,40',
        'cpf' => 'required|exact_len,14',
        'endereco' => 'required|max_len,40',
        'bairro' => 'required|max_len,40',
        'cidade' => 'required|max_len,40',
        'uf' => 'required|exact_len,2',
        'cep' => 'required|exact_len,8',
        'telefone' => 'required|between_len,8;13',
        'email'  => 'required|valid_email'
    ];

    function __construct()
    {
        session_start();
        if (!Funcoes::funcionarioLogado()) :
            Funcoes::redirect("Home");
        endif;
    }

    public function index($numPag = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $this->view('cliente/index', [], 'cliente/clientejs');
        else :
            Funcoes::redirect("Home");
        endif;
    }


    public function ajax_lista($data)
    {

        $numPag = $data['numPag'];

        // calcula o offset
        $offset = ($numPag - 1) * REGISTROS_PAG;

        $clienteModel = $this->model("ClienteModel");

        // obtém a quantidade total de registros na base de dados
        $total_registros = $clienteModel->getTotalClientes();

        // calcula a quantidade de páginas - ceil — Arredonda frações para cima
        $total_paginas = ceil($total_registros / REGISTROS_PAG);

        // obtém os registros referente a página
        $lista_clientes = $clienteModel->getRegistroPagina($offset, REGISTROS_PAG)->fetchAll(\PDO::FETCH_ASSOC);

        $corpoTabela = "";

        if (!empty($lista_clientes)) :
            foreach ($lista_clientes as $cliente) {
                $corpoTabela .= "<tr>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['nome'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['cpf'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['endereco'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['bairro'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['cidade'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['uf'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['cep'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($cliente['telefone'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities($cliente['email'], ENT_QUOTES, 'UTF-8') . "</td>";
                $corpoTabela .= "<td>" . '<button type="button" id="btAlterar" data-id="' . $cliente['id'] . '" class="btn btn-outline-primary">Alterar</button>
                                          <button type="button" id="btExcluir" data-id="' . $cliente['id'] . '" data-nome="' . $cliente['nome'] . '"class="btn btn-outline-primary">Excluir</button>'
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
            $corpoTabela = "<tr>Não há clientes</tr>";
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
    // chama a view para entrada dos dados do cliente
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

                if ($post_validado === true) :  // verificar dados do cliente

                    //$hash_senha = password_hash($_POST['senha'], PASSWORD_ARGON2I); // gerar hash senha enviada

                    $cliente = new \App\models\Cliente(); // criar uma instância de usuário
                    $cliente->setNome($_POST['nome']);   // setar os valores
                    $cliente->setCpf($_POST['cpf']);
                    $cliente->setEndereco($_POST['endereco']);
                    $cliente->setBairro($_POST['bairro']);
                    $cliente->setCidade($_POST['cidade']);
                    $cliente->setUf($_POST['uf']);
                    $cliente->setCep($_POST['cep']);
                    $cliente->setTelefone($_POST['telefone']);
                    $cliente->setEmail($_POST['email']);
                    $clienteModel = $this->model("ClienteModel"); 
                    $clienteModel->create($cliente); // incluir usuário no BD
                    //$hashId = hash('sha512', $chaveGerada);  // calcular o hash da id (chave primária) gerada
                    //$clienteModel->createHashID($chaveGerada, $hashId);

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
    public function alterarCliente($data)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            // o controlador receber o parâmetro como um array $data['hashID']
            $id = $data['id'];

            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

            $clienteModel = $this->model("clienteModel");

            $cliente = $clienteModel->get($id);

            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            $data['nome'] = $cliente['nome'];
            $data['cpf'] = $cliente['cpf'];
            $data['endereco'] = $cliente['endereco'];
            $data['bairro'] = $cliente['bairro'];
            $data['cidade'] = $cliente['cidade'];
            $data['uf'] = $cliente['uf'];
            $data['cep'] = $cliente['cep'];
            $data['telefone'] = $cliente['telefone'];
            $data['email'] = $cliente['email'];
            $data['id'] =  $id;
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
                    'nome_alteracao' => 'trim|sanitize_string',
                    'cpf_alteracao' => 'trim|sanitize_string',
                    'endereco_alteracao' => 'trim|sanitize_string',
                    'bairro_alteracao' => 'trim|sanitize_string',
                    'cidade_alteracao' => 'trim|sanitize_string',
                    'uf_alteracao' => 'trim|sanitize_string|upper_case',
                    'cep_alteracao' => 'trim',
                    'telefone_alteracao' => 'trim',
                    'email_alteracao' => 'trim|sanitize_email|lower_case'
                ];
            
                $rules = [
                    'nome_alteracao'    => 'required|min_len,2|max_len,40',
                    'cpf_alteracao' => 'required|exact_len,14',
                    'endereco_alteracao' => 'required|max_len,40',
                    'bairro_alteracao' => 'required|max_len,40',
                    'cidade_alteracao' => 'required|max_len,40',
                    'uf_alteracao' => 'required|exact_len,2',
                    'cep_alteracao' => 'required|exact_len,8',
                    'telefone_alteracao' => 'required|between_len,8;13',
                    'email_alteracao'  => 'required|valid_email'
                ];

                $validacao = new Validador("pt-br");

                $post_filtrado = $validacao->filter($_POST, $filters);
                $post_validado = $validacao->validate($post_filtrado, $rules);

                if ($post_validado === true) :  // verificar dados do cliente

                    // criando um objeto cliente
                    $cliente = new \App\models\Cliente();
                    $cliente->setNome($_POST['nome_alteracao']);
                    $cliente->setCpf($_POST['cpf_alteracao']);
                    $cliente->setEndereco($_POST['endereco_alteracao']);
                    $cliente->setBairro($_POST['bairro_alteracao']);
                    $cliente->setCidade($_POST['cidade_alteracao']);
                    $cliente->setUf($_POST['uf_alteracao']);
                    $cliente->setCep($_POST['cep_alteracao']);
                    $cliente->setTelefone($_POST['telefone_alteracao']);
                    $cliente->setEmail($_POST['email_alteracao']);
                    $cliente->setId($_POST['id_alteracao']);

                    $clienteModel = $this->model("clienteModel");

                    $clienteModel->update($cliente);

                    $data['status'] = true;
                    echo json_encode($data);
                    exit();


                else :
                    $erros = $validacao->get_errors_array();
                    $erros = implode("<br>", $erros);
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $clienteModel = $this->model("clienteModel");
                    $cliente = $clienteModel->getId($_POST['id_alteracao']);

                    $data['status'] = true;
                    $data['nome'] = $cliente['nome'];
                    $data['cpf'] = $cliente['cpf'];
                    $data['endereco'] = $cliente['endereco'];
                    $data['bairro'] = $cliente['bairro'];
                    $data['cidade'] = $cliente['cidade'];
                    $data['uf'] = $cliente['uf'];
                    $data['cep'] = $cliente['cep'];
                    $data['telefone'] = $cliente['telefone'];
                    $data['email'] = $cliente['email'];
                    $data['id'] =  $_POST['id_alteracao'];
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


    public function excluirCliente($data)
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $id = $data['id'];

            $clienteModel = $this->model("ClienteModel");

            $clienteModel->delete($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }
}