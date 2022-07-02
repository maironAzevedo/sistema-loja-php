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
        $this->view('home/index');
    }
}
