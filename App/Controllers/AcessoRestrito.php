<?php

namespace App\Controllers;

use App\core\BaseController;
use App\core\Funcoes;
use GUMP as Validador;

class AcessoRestrito extends BaseController
{
    protected $filters = [
        //'email' => 'trim|sanitize_email',
        'cpf' => 'trim',
        'senha' => 'trim|sanitize_string',
        'captcha' => 'trim|sanitize_string'
    ];

    protected $rules = [
        //'email'    => 'required|min_len,8|max_len,255',
        'cpf' => 'required',
        'senha'  => 'required',
        'captcha'  => 'required|validar_CAPTCHA_CODE'
    ];


    function __construct() {
        session_start();
    }

    public function login()
    {
        if(!isset($_SESSION['CAPTCHA_CODE'])) { 
            $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
        }
        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
        $data = ['imagem' => Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE'])];
        $this->view('acessorestrito/login', $data);
    }

    public function logar()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") :
            
            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');

            echo "captcha code gerado: " . $_SESSION['CAPTCHA_CODE'] . "\n";
            echo "captcha code digitado: " . $_POST['captcha'];
            
            $validacao = new Validador("pt-br");

            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :  // verificar login

                if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :

                    $senha_enviada = $_POST['senha'];

                    // busca o funcionario
                    $funcionarioModel = $this->model('FuncionarioModel');
                    $funcionario = $funcionarioModel->getFuncionarioCpf($_POST['cpf']);

                    if (!empty($funcionario)) :
                        $senha_bd = $funcionario['senha']; // achou o usuário usa hash do banco
                    /*else :
                        $senha_hash = $hash_senha_fake;  // não achou o usuário usa hash fake*/
                    endif;

                    if ($senha_enviada == $senha_bd) :

                        // apagar CAPTCHA_CODE
                        unset($_SESSION['CAPTCHA_CODE']);
                        
                        // regenerar a sessão
                        session_regenerate_id(true);

                        $_SESSION['id'] = $funcionario['id'];
                        $_SESSION['nomeFuncionario'] = $funcionario['nome'];
                        $_SESSION['cpfFuncionario'] = $funcionario['cpf'];
                        $_SESSION['papelFuncionario'] = $funcionario['papel'];

                        Funcoes::redirect("Dashboard");
                    else :
                        $mensagem = ["CPF e/ou Senha incorreta"];
                        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                        $data = [
                            'imagem' => $imagem,
                            'mensagens' => $mensagem
                        ];
                        
                        $this->view('acessorestrito/login', $data);
                    endif;

                else :  // falha CSRF_token"
                    die("Erro 404");
                endif;
            else : // erro de validação
                $mensagem = $validacao->get_errors_array();
                $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                $data = [
                    'imagem' => $imagem,
                    'mensagens' => $mensagem
                ];

                // $this->view('acessorestrito/login', $data);
            endif;
        else : // não POST
            Funcoes::redirect();
        endif;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        Funcoes::redirect();

    }

}
