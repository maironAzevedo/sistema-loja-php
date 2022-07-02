<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;

class Dashboard extends BaseController
{
    function __construct()
    {
        session_start();
        if (!Funcoes::usuarioLogado()) :
            Funcoes::redirect("Home");
        endif;
    }

    public function index()
    {
        $this->view('dashboard/index');
    }
}
