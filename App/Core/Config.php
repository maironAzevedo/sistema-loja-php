<?php
// Configuração do BD
define('HOST', 'localhost');// onde está o banco de dados
define('DB','estoque');      // nome da base de dados
define('USUARIO','root');   // usuário da base de dados
define('SENHA','');         // senha usuário da base de dados

//Constante que indica a URL básica da aplicação

define("URL_BASE", "http://localhost/sistema-loja-php");

//Constante que indica a URL básica da images
define("URL_IMG", URL_BASE."/public/images/");

//Constante que indica a URL básica da css
define("URL_CSS", URL_BASE."/public/css/");

//Constante que indica a URL básica da css
define("URL_JS", URL_BASE."/public/js/");

//Constante que indica a URL básica da css
define("FONTAWESOME", URL_BASE."/public/fontawesome/css/all.css");

//Constante usada para gerar CSRF Token
define('CSRF_TOKEN_SECRET', 'iyHS4##SiPcV9tIZ');

// Caminho para a imagem Captcha
define('DIR_IMG_CAPTCHA', "C:/wamp64/www/sistema-loja-php/App/writable/");

// Caminho para a imagem upload
define('DIR_IMG_UPLOAD', "C:/wamp64/www/sistema-loja-php/public/upload/");

// URL imagem upload
define('URL_IMG_UPLOAD', "http://localhost/sistema-loja-php/upload/");

// Quantidade de registros exibidos na página
define("REGISTROS_PAG", 4);

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri) {
        return URL_BASE . "/{$uri}";
    }

    return URL_BASE;
}