<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class Produto extends BaseController
{

    protected $filters = [
        'nome_produto' => 'trim|sanitize_string',
        'descricao' => 'trim|sanitize_string',
        'liberado_venda' => 'trim|sanitize_string|upper_case',
        'id_categoria' => 'trim',
    ];

    protected $rules = [
        'nome_produto'    => 'required|min_len,1|max_len,40',
        'descricao' => 'required|min_len,1|max_len,400',
        'liberado_venda' => 'required|exact_len,1',
        'id_categoria' => 'required|min_len,1|max_len,10|integer',
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

            $categoriaModel = $this->model("CategoriaModel");

            //Criando categorias_lista e mandando para a view para fazer o select box só com os categorias permitidos na view.
            $categorias_lista = $categoriaModel->read()->fetchAll(\PDO::FETCH_ASSOC);
            $data = ['categorias_lista' => $categorias_lista];

            $this->view('produto/index', $data, 'produto/produtojs');
        else :
            Funcoes::redirect("Home");
        endif;
    }


    public function ajax_lista($data)
    {

        $numPag = $data['numPag'];

        // calcula o offset
        $offset = ($numPag - 1) * REGISTROS_PAG;

        $produtoModel = $this->model("ProdutoModel");

        // obtém a quantidade total de registros na base de dados
        $total_registros = $produtoModel->getTotalProdutos();

        // calcula a quantidade de páginas - ceil — Arredonda frações para cima
        $total_paginas = ceil($total_registros / REGISTROS_PAG);

        // obtém os registros referente a página
        $lista_produtos = $produtoModel->getRegistroPagina($offset, REGISTROS_PAG)->fetchAll(\PDO::FETCH_ASSOC);

        $corpoTabela = "";

        if (!empty($lista_produtos)) :
            foreach ($lista_produtos as $produto) {
                $corpoTabela .= "<tr>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($produto['nome_produto'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($produto['descricao'])) . "</td>";

                $corpoTabela .= "<td>" . htmlentities(utf8_encode($produto['liberado_venda'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($produto['id_categoria'])) . "</td>";
                $corpoTabela .= "<td>" . '<button type="button" id="btAlterar" data-id="' . $produto['id'] . '" class="btn btn-outline-primary">Alterar</button>
                                          <button type="button" id="btExcluir" data-id="' . $produto['id'] . '" data-nome="' . $produto['nome_produto'] . '" class="btn btn-outline-primary">Excluir</button>'
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
            $corpoTabela = "<tr>Não há produtos</tr>";
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
    // chama a view para entrada dos dados do produto
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

                if ($post_validado === true) :  // verificar dados do produto

                    //$hash_senha = password_hash($_POST['senha'], PASSWORD_ARGON2I); // gerar hash senha enviada

                    $produto = new \App\models\Produto(); // criar uma instância de usuário
                    $produto->setNomeProduto($_POST['nome_produto']);   // setar os valores
                    $produto->setDescricao($_POST['descricao']);
                    $produto->setLiberadoVenda($_POST['liberado_venda']);
                    $produto->setIdCategoria($_POST['id_categoria']);
                    $produtoModel = $this->model("ProdutoModel"); 
                    $produtoModel->create($produto); // incluir usuário no BD

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
    public function alterarProduto($data)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            // o controlador receber o parâmetro como um array $data['hashID']
            $id = $data['id'];

            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

            $produtoModel = $this->model("produtoModel");

            $produto = $produtoModel->get($id);

            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            $data['nome_produto'] = $produto['nome_produto'];
            $data['descricao'] = $produto['descricao'];
            $data['liberado_venda'] = $produto['liberado_venda'];
            $data['id_categoria'] = $produto['id_categoria'];
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
                    'nome_produto_alteracao' => 'trim|sanitize_string',
                    'descricao_alteracao' => 'trim|sanitize_string',
                    'liberado_venda_alteracao' => 'trim|sanitize_string|upper_case',
                    'id_categoria_alteracao' => 'trim',
                ];
            
                $rules = [
                    'nome_produto_alteracao'    => 'required|min_len,1|max_len,40',
                    'descricao_alteracao' => 'required|min_len,1|max_len,400',
                    'liberado_venda_alteracao' => 'required|exact_len,1',
                    'id_categoria_alteracao' => 'required|min_len,1|max_len,10|integer',
                ];

                $validacao = new Validador("pt-br");

                $post_filtrado = $validacao->filter($_POST, $filters);
                $post_validado = $validacao->validate($post_filtrado, $rules);

                if ($post_validado === true) :  // verificar dados do produto

                    // criando um objeto produto
                    $produto = new \App\models\Produto();

                    $produto->setNomeProduto($_POST['nome_produto_alteracao']);
                    $produto->setDescricao($_POST['descricao_alteracao']);
                    $produto->setLiberadoVenda($_POST['liberado_venda_alteracao']);
                    $produto->setIdCategoria($_POST['id_categoria_alteracao']);
                    $produto->setId($_POST['id_alteracao']);

                    $produtoModel = $this->model("produtoModel");

                    $produto_aux = $produtoModel->get($produto->getId());
                    $produto->setQuantidadeDisponivel($produto_aux["quantidade_disponível"]);
                    $produto->setPrecoCompra($produto_aux["preco_compra"]);
                    $produto->setPrecoVenda($produto_aux["preco_venda"]);

                    $produtoModel->update($produto);

                    $data['status'] = true;
                    echo json_encode($data);
                    exit();


                else :
                    $erros = $validacao->get_errors_array();
                    $erros = implode("<br>", $erros);
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $produtoModel = $this->model("produtoModel");
                    $produto = $produtoModel->getId($_POST['id_alteracao']);

                    $data['status'] = true;
                    $data['nome_produto'] = $produto['nome_produto'];
                    $data['descricao'] = $produto['descricao'];
                    $data['liberado_venda'] = $produto['liberado_venda'];
                    $data['id_categoria'] = $produto['id_categoria'];
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


    public function excluirProduto($data)
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $id = $data['id'];

            $produtoModel = $this->model("ProdutoModel");

            $produtoModel->delete($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }
}