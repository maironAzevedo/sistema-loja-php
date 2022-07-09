<?php

namespace App\Controllers;

use App\Core\BaseController;

class Home extends BaseController
{

    function __construct()
    {
        session_start();
    }
    public function index()
    {
        // instanciar o model
        // $this->model método herdado de BaseController
        $produtoModel = $this->model("ProdutoModel");

        $produtos = $produtoModel->read()->fetchAll(\PDO::FETCH_ASSOC);

        $data = ['produtos' => $produtos];

        $this->view('home/index', $data);
    }
}
