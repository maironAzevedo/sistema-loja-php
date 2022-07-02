<?php

namespace App\core;

class Paginacao
{

    private $limit = REGISTROS_PAG;
    private $page;
    private $query;
    private $total;
    private $model;
    private $campo;
    private $orientacao;
    private $pesquisa;

    // vai passar o model e a query
    public function __construct($model, $sql, $campo, $orientacao, $pesquisa)
    {
        $this->model = $model;
        $this->query = $sql;

        $this->campo = $campo;
        $this->orientacao = $orientacao;
        $this->pesquisa = $pesquisa;

        $conn = $model::getConexao();
        $dados = $conn->query($sql);
        $this->total = $dados->rowCount();
    }


    public function getData($page = 1)
    {

        $conn = $this->model::getConexao();
        $this->page = $page;

        $query = $this->query;

        if (!empty($this->pesquisa)) :
            $query .= " WHERE titulo LIKE '%" . $this->pesquisa . "%'";

            $dados = $conn->query($query);
            $this->total = $dados->rowCount();

        endif;

        if (!empty($this->campo)) :
            $query .= ' ORDER BY ' . $this->campo . ' ' . $this->orientacao;
        endif;

        $query .= " LIMIT " . (($this->page - 1) * $this->limit) . ", $this->limit";

        $dados = $conn->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $result         = new \stdClass();
        $result->page   = $this->page;
        $result->limit  = $this->limit;
        $result->total  = $this->total;
        $result->data   = $dados;

        return $result;
    }


    public function createLinks($url_link)
    {

        $link = "";
        if ($this->total > 0) :
            $TotalPaginas = ceil($this->total / $this->limit);
            $link .= '<nav aria-label="Page navigation example">
                <ul class="pagination">';
            // links da paginação
            for ($page = 1; $page <= $TotalPaginas; $page++) {
                $url = URL_BASE . '/' . $url_link . '/' . $page;
                $link .= '<li class="page-item"><a class="page-link" href="' . $url . '">' . $page . '</a></li>';
            }

            $link .= '</ul>
            </nav>';
        endif;

        return $link;
    }
}
