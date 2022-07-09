<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class Fornecedor extends BaseController
{

    protected $filters = [
        'razao_social' => 'trim|sanitize_string',
        'cnpj' => 'trim|sanitize_string',
        'endereco' => 'trim|sanitize_string',
        'bairro' => 'trim|sanitize_string',
        'cidade' => 'trim|sanitize_string',
        'uf' => 'trim|sanitize_string|upper_case',
        'cep' => 'trim',
        'telefone' => 'trim',
        'email' => 'trim|sanitize_email|lower_case'
    ];

    protected $rules = [
        'razao_social'    => 'required|min_len,2|max_len,40',
        'cnpj' => 'required|max_len,20',
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

            $this->view('fornecedor/index', [], 'fornecedor/fornecedorjs');
        else :
            Funcoes::redirect("Home");
        endif;
    }


    public function ajax_lista($data)
    {

        $numPag = $data['numPag'];

        // calcula o offset
        $offset = ($numPag - 1) * REGISTROS_PAG;

        $fornecedorModel = $this->model("FornecedorModel");

        // obtém a quantidade total de registros na base de dados
        $total_registros = $fornecedorModel->getTotalFornecedores();

        // calcula a quantidade de páginas - ceil — Arredonda frações para cima
        $total_paginas = ceil($total_registros / REGISTROS_PAG);

        // obtém os registros referente a página
        $lista_fornecedores = $fornecedorModel->getRegistroPagina($offset, REGISTROS_PAG)->fetchAll(\PDO::FETCH_ASSOC);

        $corpoTabela = "";

        if (!empty($lista_fornecedores)) :
            foreach ($lista_fornecedores as $fornecedor) {
                $corpoTabela .= "<tr>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['razao_social'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['cnpj'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['endereco'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['bairro'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['cidade'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['uf'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['cep'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($fornecedor['telefone'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities($fornecedor['email'], ENT_QUOTES, 'UTF-8') . "</td>";
                $corpoTabela .= "<td>" . '<button type="button" id="btAlterar" data-id="' . $fornecedor['id'] . '" class="btn btn-outline-primary">Alterar</button>
                                          <button type="button" id="btExcluir" data-id="' . $fornecedor['id'] . '" data-cnpj="' . $fornecedor['cnpj'] . '"class="btn btn-outline-primary">Excluir</button>'
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
            $corpoTabela = "<tr>Não há fornecedores</tr>";
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
    // chama a view para entrada dos dados do fornecedor
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

                if ($post_validado === true) :  // verificar dados do fornecedor

                    //$hash_senha = password_hash($_POST['senha'], PASSWORD_ARGON2I); // gerar hash senha enviada

                    $fornecedor = new \App\models\Fornecedor(); // criar uma instância de usuário
                    $fornecedor->setRazaoSocial($_POST['razao_social']);   // setar os valores
                    $fornecedor->setCnpj($_POST['cnpj']);
                    $fornecedor->setEndereco($_POST['endereco']);
                    $fornecedor->setBairro($_POST['bairro']);
                    $fornecedor->setCidade($_POST['cidade']);
                    $fornecedor->setUf($_POST['uf']);
                    $fornecedor->setCep($_POST['cep']);
                    $fornecedor->setTelefone($_POST['telefone']);
                    $fornecedor->setEmail($_POST['email']);
                    $fornecedorModel = $this->model("FornecedorModel"); 
                    $fornecedorModel->create($fornecedor); // incluir usuário no BD
                    //$hashId = hash('sha512', $chaveGerada);  // calcular o hash da id (chave primária) gerada
                    //$fornecedorModel->createHashID($chaveGerada, $hashId);

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
    public function alterarFornecedor($data)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            // o controlador receber o parâmetro como um array $data['hashID']
            $id = $data['id'];

            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

            $fornecedorModel = $this->model("fornecedorModel");

            $fornecedor = $fornecedorModel->get($id);

            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            $data['razao_social'] = $fornecedor['razao_social'];
            $data['cnpj'] = $fornecedor['cnpj'];
            $data['endereco'] = $fornecedor['endereco'];
            $data['bairro'] = $fornecedor['bairro'];
            $data['cidade'] = $fornecedor['cidade'];
            $data['uf'] = $fornecedor['uf'];
            $data['cep'] = $fornecedor['cep'];
            $data['telefone'] = $fornecedor['telefone'];
            $data['email'] = $fornecedor['email'];
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
                    'razao_social_alteracao' => 'trim|sanitize_string',
                    'cnpj_alteracao' => 'trim|sanitize_string',
                    'endereco_alteracao' => 'trim|sanitize_string',
                    'bairro_alteracao' => 'trim|sanitize_string',
                    'cidade_alteracao' => 'trim|sanitize_string',
                    'uf_alteracao' => 'trim|sanitize_string|upper_case',
                    'cep_alteracao' => 'trim',
                    'telefone_alteracao' => 'trim',
                    'email_alteracao' => 'trim|sanitize_email|lower_case'
                ];

                $rules = [
                    'razao_social_alteracao'    => 'required|min_len,2|max_len,40',
                    'cnpj_alteracao' => 'required|max_len,20',
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

                if ($post_validado === true) :  // verificar dados do fornecedor

                    // criando um objeto fornecedor
                    $fornecedor = new \App\models\Fornecedor();
                    $fornecedor->setRazaoSocial($_POST['razao_social_alteracao']);
                    $fornecedor->setCnpj($_POST['cnpj_alteracao']);
                    $fornecedor->setEndereco($_POST['endereco_alteracao']);
                    $fornecedor->setBairro($_POST['bairro_alteracao']);
                    $fornecedor->setCidade($_POST['cidade_alteracao']);
                    $fornecedor->setUf($_POST['uf_alteracao']);
                    $fornecedor->setCep($_POST['cep_alteracao']);
                    $fornecedor->setTelefone($_POST['telefone_alteracao']);
                    $fornecedor->setEmail($_POST['email_alteracao']);
                    $fornecedor->setId($_POST['id_alteracao']);

                    $fornecedorModel = $this->model("fornecedorModel");

                    $fornecedorModel->update($fornecedor);

                    $data['status'] = true;
                    echo json_encode($data);
                    exit();


                else :
                    $erros = $validacao->get_errors_array();
                    $erros = implode("<br>", $erros);
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $fornecedorModel = $this->model("fornecedorModel");
                    $fornecedor = $fornecedorModel->getId($_POST['id_alteracao']);

                    $data['status'] = true;
                    $data['razao_social'] = $fornecedor['razao_social'];
                    $data['cnpj'] = $fornecedor['cnpj'];
                    $data['endereco'] = $fornecedor['endereco'];
                    $data['bairro'] = $fornecedor['bairro'];
                    $data['cidade'] = $fornecedor['cidade'];
                    $data['uf'] = $fornecedor['uf'];
                    $data['cep'] = $fornecedor['cep'];
                    $data['telefone'] = $fornecedor['telefone'];
                    $data['email'] = $fornecedor['email'];
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


    public function excluirFornecedor($data)
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $id = $data['id'];

            $fornecedorModel = $this->model("FornecedorModel");

            $fornecedorModel->delete($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }
}