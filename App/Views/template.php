<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#">
    <link href="<?= URL_CSS ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL_JS ?>sweetalert2/sweetalert2.css" rel="stylesheet">
    <link href="<?= FONTAWESOME ?>" rel="stylesheet">
    <title>CompraVenda</title>
</head>

<body>
    <!-- Menu Principal -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="<?= URL_IMG ?>uff.png" width="40" height="30" alt="logo UFF">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active text-primary" href="<?= URL_BASE ?>/home">Home <span class="sr-only">(current)</span></a>
                <?php
                if (isset($_SESSION['id'])) : ?>
                    <?php if($_SESSION['papelFuncionario'] == 0): ?>
                        <!-- Links para acesso de admin -->
                        
                    <?php elseif($_SESSION['papelFuncionario'] == 1): ?>
                        <!-- Links para acesso de vendedor -->
                        <a class="nav-item nav-link text-success" href="<?= URL_BASE ?>/Clientes">Clientes</a>
                        <a class="nav-item nav-link text-success" href="<?= URL_BASE ?>/Vendas">Vendas</a>

                    <?php elseif($_SESSION['papelFuncionario'] == 2): ?>
                        <!-- Links para acesso de comprador -->
                        <a class="nav-item nav-link text-success" href="<?= URL_BASE ?>/Fornecedores">Fornecedores</a>
                        <a class="nav-item nav-link text-success" href="<?= URL_BASE ?>/Categorias">Categorias</a>
                        <a class="nav-item nav-link text-success" href="<?= URL_BASE ?>/Compras">Compras</a>
                        <a class="nav-item nav-link text-success" href="<?= URL_BASE ?>/Produtos">Produtos</a>

                    <?php endif ?>
                    <a class="nav-item nav-link text-danger" href="<?= URL_BASE ?>/logout">Logout</a>
                <?php else : ?>
                    <a class="nav-item nav-link" href="<?= URL_BASE ?>/login">Área Restrita</a>
                <?php endif ?>
            </div>
        </div>
    </nav>

    <!-- Conteúdo da Página -->
    <div class="container-fluid mt-3">
        <!-- Vai inserir a view no template que será passada por parâmetro -->
        <?php require_once 'App/views/' . $view . '.php' ?>
    </div>

    <script src="<?= URL_JS ?>jquery-3.4.1.min.js"></script>
    <script src="<?= URL_JS ?>popper.min.js"></script>
    <script src="<?= URL_JS ?>bootstrap.min.js"></script>
    <script src="<?= URL_JS ?>sweetalert2/sweetalert2.js"></script>

    <?php  // verifica ser existe inclusão de script js e faz a inserção
         if ($js != null):
            require_once 'App/views/' . $js.'.php' ;
         endif;   
    ?>

</body>

</html>