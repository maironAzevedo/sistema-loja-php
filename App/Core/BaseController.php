<?php

namespace App\Core;

class BaseController {

    // método que carrega o model associado ao controller
    public function model($model){

        if (file_exists('App/Models/'.$model.'.php')) :
          require_once 'App/Models/'.$model.'.php';
          return new $model;
        else:
            echo "Model não existe";
        endif;
    }

     // método que carrega a view associado ao controller
     // e os dados que serão exibidos
     // $view passada parâmetro será inserida em template
     // $js script jquery a ser inserido no template
    public function view($view, $data=[], $js=null){
        require_once 'App/Views/template.php';
    }



}