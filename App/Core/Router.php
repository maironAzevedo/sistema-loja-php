<?php

$route = new \CoffeeCode\Router\Router(URL_BASE);
/**
 * APP
 */
$route->namespace("App\Controllers");
/**
 * parte publica
 */
$route->get("/", "Home:index");
$route->get("/home", "Home:index");
$route->get("/login", "AcessoRestrito:login");
$route->post("/logar", "AcessoRestrito:logar");  // <= rota para metodo POST do from login
/**
 * parte restrita
 */
$route->get("/Dashboard", "Dashboard:index");
$route->get("/logout", "AcessoRestrito:logout");
$route->get("/painelusuario", "User:index");

/**
 * parte restrita - CRUD Clientes
 */
$route->get("/clientes", "Cliente:index");
$route->get("/incluircliente", "Cliente:incluir");
$route->post("/salvarinclusaocliente", "Cliente:gravarInclusao");
$route->get("/navegacliente/{numPag}", "Cliente:ajax_lista");
$route->get("/alteracaocliente/{id}", "Cliente:alterarCliente");
$route->post("/gravaralteracaocliente", "Cliente:gravarAlterar");
$route->get("/excluircliente/{id}", "Cliente:excluirCliente");

/**
 * parte restrita - CRUD Vendas
 */
$route->get("/Vendas", "Venda:index");
$route->get("/incluirvenda", "Venda:incluir");
$route->post("/salvarinclusaovenda", "Venda:gravarInclusao");
$route->get("/navegavenda/{numPag}", "Venda:ajax_lista");
$route->get("/alteracaovenda/{id}", "Venda:alterarVenda");
$route->post("/gravaralteracaovenda", "Venda:gravarAlterar");
$route->get("/excluirvenda/{id}", "Venda:excluirVenda");

/**
 * parte restrita - CRUD fornecedores
 */
$route->get("/Fornecedores", "Fornecedor:index");
$route->get("/incluirfornecedor", "Fornecedor:incluir");
$route->post("/salvarinclusaofornecedor", "Fornecedor:gravarInclusao");
$route->get("/navegafornecedor/{numPag}", "Fornecedor:ajax_lista");
$route->get("/alteracaofornecedor/{id}", "Fornecedor:alterarFornecedor");
$route->post("/gravaralteracaofornecedor", "Fornecedor:gravarAlterar");
$route->get("/excluirfornecedor/{id}", "Fornecedor:excluirFornecedor");

/**
 * parte restrita - CRUD categorias
 */
$route->get("/Categorias", "Categoria:index");
$route->get("/incluircategoria", "Categoria:incluir");
$route->post("/salvarinclusaocategoria", "Categoria:gravarInclusao");
$route->get("/navegacategoria/{numPag}", "Categoria:ajax_lista");
$route->get("/alteracaocategoria/{id}", "Categoria:alterarCategoria");
$route->post("/gravaralteracaocategoria", "Categoria:gravarAlterar");
$route->get("/excluircategoria/{id}", "Categoria:excluirCategoria");

/**
 * parte restrita - CRUD compras
 */
$route->get("/Compras", "Compra:index");
$route->get("/incluircompra", "Compra:incluir");
$route->post("/salvarinclusaocompra", "Compra:gravarInclusao");
$route->get("/navegacompra/{numPag}", "Compra:ajax_lista");
$route->get("/alteracaocompra/{id}", "Compra:alterarCompra");
$route->post("/gravaralteracaocompra", "Compra:gravarAlterar");
$route->get("/excluircompra/{id}", "Compra:excluirCompra");

/**
 * parte restrita - CRUD produtos
 */
$route->get("/Produtos", "Produto:index");
$route->get("/incluirproduto", "Produto:incluir");
$route->post("/salvarinclusaoproduto", "Produto:gravarInclusao");
$route->get("/navegaproduto/{numPag}", "Produto:ajax_lista");
$route->get("/alteracaoproduto/{id}", "Produto:alterarProduto");
$route->post("/gravaralteracaoproduto", "Produto:gravarAlterar");
$route->get("/excluirproduto/{id}", "Produto:excluirProduto");

/**
 * ERROR
 */
$route->group("ops");
$route->get("/{errcode}", "Web:error");
/**
 * PROCESS
 */
$route->dispatch();

if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}
