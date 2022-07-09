<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class Categoria extends BaseController
{

    protected $filters = [
        'nome_categoria' => 'trim|sanitize_string',
    ];

    protected $rules = [
        'nome_categoria'    => 'required|min_len,1|max_len,40',
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

            $this->view('categoria/index', [], 'categoria/categoriajs');
        else :
            Funcoes::redirect("Home");
        endif;
    }


    public function ajax_lista($data)
    {

        $numPag = $data['numPag'];

        // calcula o offset
        $offset = ($numPag - 1) * REGISTROS_PAG;

        $categoriaModel = $this->model("CategoriaModel");

        // obtém a quantidade total de registros na base de dados
        $total_registros = $categoriaModel->getTotalCategorias();

        // calcula a quantidade de páginas - ceil — Arredonda frações para cima
        $total_paginas = ceil($total_registros / REGISTROS_PAG);

        // obtém os registros referente a página
        $lista_categorias = $categoriaModel->getRegistroPagina($offset, REGISTROS_PAG)->fetchAll(\PDO::FETCH_ASSOC);

        $corpoTabela = "";

        if (!empty($lista_categorias)) :
            foreach ($lista_categorias as $categoria) {
                $corpoTabela .= "<tr>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($categoria['nome_categoria'])) . "</td>";
                $corpoTabela .= "<td>" . '<button type="button" id="btAlterar" data-id="' . $categoria['id'] . '" class="btn btn-outline-primary">Alterar</button>
                                          <button type="button" id="btExcluir" data-id="' . $categoria['id'] . '" data-nome="' . $categoria['nome_categoria'] . '"class="btn btn-outline-primary">Excluir</button>'
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
            $corpoTabela = "<tr>Não há categorias</tr>";
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
    // chama a view para entrada dos dados do categoria
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

                if ($post_validado === true) :  // verificar dados da categoria

                    //$hash_senha = password_hash($_POST['senha'], PASSWORD_ARGON2I); // gerar hash senha enviada

                    $categoria = new \App\models\Categoria(); // criar uma instância de usuário
                    $categoria->setNomeCategoria($_POST['nome_categoria']);   // setar os valores
                    $categoriaModel = $this->model("CategoriaModel"); 
                    $categoriaModel->create($categoria); // incluir usuário no BD
                    //$hashId = hash('sha512', $chaveGerada);  // calcular o hash da id (chave primária) gerada
                    //$categoriaModel->createHashID($chaveGerada, $hashId);

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
    public function alterarCategoria($data)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            // o controlador receber o parâmetro como um array $data['hashID']
            $id = $data['id'];

            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

            $categoriaModel = $this->model("categoriaModel");

            $categoria = $categoriaModel->get($id);

            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            $data['nome_categoria'] = $categoria['nome_categoria'];
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
                    'nome_categoria_alteracao' => 'trim|sanitize_string',
                ];

                $rules = [
                    'nome_categoria_alteracao'    => 'required|min_len,1|max_len,40',
                ];

                $validacao = new Validador("pt-br");

                $post_filtrado = $validacao->filter($_POST, $filters);
                $post_validado = $validacao->validate($post_filtrado, $rules);

                if ($post_validado === true) :  // verificar dados do categoria

                    // criando um objeto categoria
                    $categoria = new \App\models\Categoria();
                    $categoria->setNomeCategoria($_POST['nome_categoria_alteracao']);
                    $categoria->setId($_POST['id_alteracao']);

                    $categoriaModel = $this->model("categoriaModel");

                    $categoriaModel->update($categoria);

                    $data['status'] = true;
                    echo json_encode($data);
                    exit();


                else :
                    $erros = $validacao->get_errors_array();
                    $erros = implode("<br>", $erros);
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $categoriaModel = $this->model("categoriaModel");
                    $categoria = $categoriaModel->getId($_POST['id_alteracao']);

                    $data['status'] = true;
                    $data['nome'] = $categoria['nome_categoria'];
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


    public function excluirCategoria($data)
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $id = $data['id'];

            $categoriaModel = $this->model("CategoriaModel");

            $categoriaModel->delete($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }
}