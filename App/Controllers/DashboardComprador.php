<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\core\Funcoes;

class DashboardComprador extends BaseController
{
    function __construct()
    {
        session_start();
        if (!Funcoes::funcionarioLogado()) :
            Funcoes::redirect("Home");
        endif;
    }

    public function index()
    {
        $this->view('dashboardComprador/index');
    }
}