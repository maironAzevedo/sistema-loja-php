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
 * parte restrita - usuários
 */
$route->get("/incluirusuario", "User:incluir");
$route->post("/salvarinclusao", "User:gravarInclusao");
// o controlador receber o parâmetro como um array $data['numPag']
$route->get("/navega/{numPag}", "User:ajax_lista");
// o controlador receber o parâmetro como um array $data['hashID']
$route->get("/alteracaousuario/{hashID}", "User:alterarUsuario");
$route->post("/gravaralteracao", "User:gravarAlterar");
// o controlador receber o parâmetro como um array $data['hashID']
$route->get("/excluirusuario/{hashID}", "User:excluirUsuario");
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