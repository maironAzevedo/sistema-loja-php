<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class Compra extends BaseController
{

    protected $filters = [
        'quantidade_compra' => 'trim',
        'data_compra'    => 'trim|sanitize_string',
        'valor_compra'    => 'trim',
        'id_fornecedor'    => 'trim',
        'id_produto'    => 'trim',
        'id_funcionario'    => 'trim'
    ];

    protected $rules = [
        'quantidade_compra'    => 'required|min_len,1|max_len,10|integer',
        'data_compra'    => 'required',
        'valor_compra'    => 'required|min_len,1|max_len,10|float',
        'id_fornecedor'    => 'required|min_len,1|max_len,10|integer',
        'id_produto'    => 'required|min_len,1|max_len,10|integer',
        'id_funcionario'    => 'required|min_len,1|max_len,10|integer'
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

            $produtoModel = $this->model("ProdutoModel");

            //Criando produtos_lista e mandando para a view para fazer o select box só com os produtos permitidos na view.
            $produtos_lista = $produtoModel->read()->fetchAll(\PDO::FETCH_ASSOC);
            $data = ['produtos_lista' => $produtos_lista];

            $fornecedorModel = $this->model("FornecedorModel");

            //Criando fornecedores_lista e mandando para a view para fazer o select box só com os fornecedores permitidos na view.
            $fornecedores_lista = $fornecedorModel->read()->fetchAll(\PDO::FETCH_ASSOC);
            $data += ['fornecedores_lista' => $fornecedores_lista];

            $this->view('compra/index', $data, 'compra/comprajs');
        else :
            Funcoes::redirect("Home");
        endif;
    }


    public function ajax_lista($data)
    {

        $numPag = $data['numPag'];

        // calcula o offset
        $offset = ($numPag - 1) * REGISTROS_PAG;

        $compraModel = $this->model("CompraModel");

        // obtém a quantidade total de registros na base de dados
        $total_registros = $compraModel->getTotalCompras();

        // calcula a quantidade de páginas - ceil — Arredonda frações para cima
        $total_paginas = ceil($total_registros / REGISTROS_PAG);

        // obtém os registros referente a página
        $lista_compras = $compraModel->getRegistroPagina($offset, REGISTROS_PAG)->fetchAll(\PDO::FETCH_ASSOC);

        $corpoTabela = "";

        if (!empty($lista_compras)) :
            foreach ($lista_compras as $compra) {
                $corpoTabela .= "<tr>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($compra['quantidade_compra'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($compra['data_compra'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($compra['valor_compra'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($compra['id_fornecedor'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($compra['id_produto'])) . "</td>";
                $corpoTabela .= "<td>" . htmlentities(utf8_encode($compra['id_funcionario'])) . "</td>";
                $corpoTabela .= "<td>" . '<button type="button" id="btAlterar" data-id="' . $compra['id'] . '" class="btn btn-outline-primary">Alterar</button>
                                          <button type="button" id="btExcluir" data-id="' . $compra['id'] . '" data-produto="' . $compra['id_produto'] . '"class="btn btn-outline-primary">Excluir</button>'
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
            $corpoTabela = "<tr>Não há compras</tr>";
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
    // chama a view para entrada dos dados da compra
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

                if ($post_validado === true) :  // verificar dados da compra

                    //$hash_senha = password_hash($_POST['senha'], PASSWORD_ARGON2I); // gerar hash senha enviada

                    $compra = new \App\models\Compra(); // criar uma instância da compra
                    $compra->setQuantidadeCompra($_POST['quantidade_compra']);   // setar os valores
                    $compra->setDataCompra($_POST['data_compra']);
                    $compra->setValorCompra($_POST['valor_compra']);
                    $compra->setIdFornecedor($_POST['id_fornecedor']);
                    $compra->setIdProduto($_POST['id_produto']);
                    $compra->setIdFuncionario($_POST['id_funcionario']);
                    $compraModel = $this->model("CompraModel");
                    $compraModel->create($compra); // incluir compra no BD
                    //$hashId = hash('sha512', $chaveGerada);  // calcular o hash da id (chave primária) gerada
                    //$clienteModel->createHashID($chaveGerada, $hashId);                   

                    //Alterar preço de compra e quantidade disponível do produto
                    $produtoModel = $this->model("ProdutoModel");
                    $produto_aux = $produtoModel->get($compra->getIdProduto());
                    $produto = new \App\models\Produto();
                    $produto->setQuantidadeDisponivel($produto_aux["quantidade_disponível"] + $compra->getQuantidadeCompra());
                    $produto->setNomeProduto($produto_aux["nome_produto"]);
                    $produto->setDescricao($produto_aux["descricao"]);
                    $produto->setPrecoCompra($compra->getValorCompra());
                    $produto->setPrecoVenda($produto_aux["preco_venda"]);
                    $produto->setLiberadoVenda($produto_aux["liberado_venda"]);
                    $produto->setIdCategoria($produto_aux["id_categoria"]);
                    $produto->setId($compra->getIdProduto());
                    $produtoModel->update($produto);

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
    public function alterarCompra($data)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            // o controlador receber o parâmetro como um array $data['hashID']
            $id = $data['id'];

            // gera o CSRF_token e guarda na sessão
            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

            $compraModel = $this->model("compraModel");

            $compra = $compraModel->get($id);

            $data = array();
            $data['token'] = $_SESSION['CSRF_token'];
            $data['status'] = true;
            $data['quantidade_compra'] = $compra['quantidade_compra'];
            $data['data_compra'] = $compra['data_compra'];
            $data['valor_compra'] = $compra['valor_compra'];
            $data['id_fornecedor'] = $compra['id_fornecedor'];
            $data['id_produto'] = $compra['id_produto'];
            $data['id_funcionario'] = $compra['id_funcionario'];
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
                    'quantidade_compra_alteracao' => 'trim',
                    'data_compra_alteracao'    => 'trim|sanitize_string',
                    'valor_compra_alteracao'    => 'trim',
                    'id_fornecedor_alteracao'    => 'trim',
                    'id_produto_alteracao'    => 'trim',
                    'id_funcionario_alteracao'    => 'trim'
                ];

                $rules = [
                    'quantidade_compra_alteracao'    => 'required|min_len,1|max_len,10|integer',
                    'data_compra_alteracao'    => 'required',
                    'valor_compra_alteracao'    => 'required|min_len,1|max_len,10|float',
                    'id_fornecedor_alteracao'    => 'required|min_len,1|max_len,10|integer',
                    'id_produto_alteracao'    => 'required|min_len,1|max_len,10|integer',
                    'id_funcionario_alteracao'    => 'required|min_len,1|max_len,10|integer'
                ];

                $validacao = new Validador("pt-br");

                $post_filtrado = $validacao->filter($_POST, $filters);
                $post_validado = $validacao->validate($post_filtrado, $rules);

                if ($post_validado === true) :  // verificar dados da compra

                    $compraModel = $this->model("CompraModel");

                    //Criando já o objeto compra_nova para pegar o id do campo e usar para pegar as informações da compra_antiga
                    $compra_nova = new \App\models\Compra();
                    $compra_nova->setId($_POST['id_alteracao']);

                    //Criando um objeto compra para pegar os valores da compra antes de ser atualizada e usar para atualizar o produto
                    $compra_aux = $compraModel->get($compra_nova->getId());
                    $compra_antiga = new \App\models\Compra(); // criar uma instância da compra
                    $compra_antiga->setQuantidadeCompra($compra_aux['quantidade_compra']);   // setar os valores que importam
                    $compra_antiga->setValorCompra($compra_aux['valor_compra']);
                    $compra_antiga->setIdProduto($compra_aux['id_produto']);

                    //Criando objeto produto para atualizar quantidade disponível do produto antes de atualizar a venda
                    $produtoModel = $this->model("ProdutoModel");
                    $produto_aux = $produtoModel->get($compra_antiga->getIdProduto());
                    $produto = new \App\models\Produto();
                    $produto->setQuantidadeDisponivel($produto_aux["quantidade_disponível"] - $compra_antiga->getQuantidadeCompra());
                    $produto->setNomeProduto($produto_aux["nome_produto"]);
                    $produto->setDescricao($produto_aux["descricao"]);
                    $produto->setPrecoCompra($compra_antiga->getValorCompra());
                    $produto->setPrecoVenda($produto_aux["preco_venda"]);
                    $produto->setLiberadoVenda($produto_aux["liberado_venda"]);
                    $produto->setIdCategoria($produto_aux["id_categoria"]);
                    $produto->setId($compra_antiga->getIdProduto());
                    $produtoModel->update($produto);

                    // criando um objeto compra_nova
                    $compra_nova->setQuantidadeCompra($_POST['quantidade_compra_alteracao']);
                    $compra_nova->setDataCompra($_POST['data_compra_alteracao']);
                    $compra_nova->setValorCompra($_POST['valor_compra_alteracao']);
                    $compra_nova->setIdFornecedor($_POST['id_fornecedor_alteracao']);
                    $compra_nova->setIdProduto($_POST['id_produto_alteracao']);
                    $compra_nova->setIdFuncionario($_POST['id_funcionario_alteracao']);
                    $compraModel->update($compra_nova);

                    //Alterar preço de compra e quantidade disponível do produto
                    $produtoModel = $this->model("ProdutoModel");
                    $produto_aux = $produtoModel->get($compra_nova->getIdProduto());
                    $produto = new \App\models\Produto();
                    $produto->setQuantidadeDisponivel($produto_aux["quantidade_disponível"] + $compra_nova->getQuantidadeCompra());
                    $produto->setNomeProduto($produto_aux["nome_produto"]);
                    $produto->setDescricao($produto_aux["descricao"]);
                    $produto->setPrecoCompra($compra_nova->getValorCompra());
                    $produto->setPrecoVenda($produto_aux["preco_venda"]);
                    $produto->setLiberadoVenda($produto_aux["liberado_venda"]);
                    $produto->setIdCategoria($produto_aux["id_categoria"]);
                    $produto->setId($compra_nova->getIdProduto());
                    $produtoModel->update($produto);

                    $data['status'] = true;
                    echo json_encode($data);
                    exit();


                else :
                    $erros = $validacao->get_errors_array();
                    $erros = implode("<br>", $erros);
                    $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();

                    $compraModel = $this->model("compraModel");
                    $compra = $compraModel->getId($_POST['id_alteracao']);

                    $data['status'] = true;
                    $data['quantidade_compra'] = $compra['quantidade_compra'];
                    $data['data_compra'] = $compra['data_compra'];
                    $data['valor_compra'] = $compra['valor_compra'];
                    $data['id_fornecedor'] = $compra['id_fornecedor'];
                    $data['id_produto'] = $compra['id_produto'];
                    $data['id_funcionario'] = $compra['id_funcionario'];
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


    public function excluirCompra($data)
    {
        // trata a as solicitações POST
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :

            $id = $data['id'];

            $compraModel = $this->model("CompraModel");

            //Criando um objeto compra para pegar os valores da compra que vai ser deletada para atualizar o produto
            $compra_aux = $compraModel->get($id);
            $compra = new \App\models\Compra(); // criar uma instância da compra
            $compra->setQuantidadeCompra($compra_aux['quantidade_compra']);   // setar os valores
            $compra->setValorCompra($compra_aux['valor_compra']);
            $compra->setIdProduto($compra_aux['id_produto']);

            //Criando objeto produto para atualizar quantidade disponível do produto antes de deletar a compra
            $produtoModel = $this->model("ProdutoModel");
            $produto_aux = $produtoModel->get($compra->getIdProduto());
            $produto = new \App\models\Produto();
            $produto->setQuantidadeDisponivel($produto_aux["quantidade_disponível"] - $compra->getQuantidadeCompra());
            $produto->setNomeProduto($produto_aux["nome_produto"]);
            $produto->setDescricao($produto_aux["descricao"]);
            $produto->setPrecoCompra($compra->getValorCompra());
            $produto->setPrecoVenda($produto_aux["preco_venda"]);
            $produto->setLiberadoVenda($produto_aux["liberado_venda"]);
            $produto->setIdCategoria($produto_aux["id_categoria"]);
            $produto->setId($compra->getIdProduto());
            $produtoModel->update($produto);

            $compraModel->delete($id);

            $data = array();
            $data['status'] = true;
            echo json_encode($data);
            exit();

        else :
            Funcoes::redirect("Home");
        endif;
    }
}
