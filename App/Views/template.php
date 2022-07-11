<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#">
    <link href="<?= URL_CSS ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL_JS ?>sweetalert2/sweetalert2.css" rel="stylesheet">
    <link href="<?= FONTAWESOME ?>" rel="stylesheet">
    <title>Sistema de vendas UFF</title>
</head>

<body>
    <!-- Menu Principal -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/home">
            <img src="<?= URL_IMG ?>uff.png" width="40" height="30" alt="logo UFF">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav w-100 container">
                <a class="nav-item nav-link active text-primary text-right" href="<?= URL_BASE ?>/home">Home <span class="sr-only">(current)</span></a>
                <?php
                if (isset($_SESSION['id'])) : ?>
                    <a class="nav-item nav-link active text-primary text-right" href="<?= URL_BASE ?>/Dashboard">Dashboard <span class="sr-only"></span></a>

                    <?php switch ($_SESSION['papelFuncionario']):
                        case 0: // Administrador 
                    ?>
                            <?php break; ?>
                        <?php
                        case 1: // Vendedor 
                        ?>
                            <a class="nav-item nav-link text-primary text-right" href="<?= URL_BASE ?>/Clientes">Clientes<span class="sr-only"></a>
                            <a class="nav-item nav-link text-primary text-right" href="<?= URL_BASE ?>/Vendas">Vendas<span class="sr-only"></a>
                            <?php break; ?>
                        <?php
                        case 2: // Comprador 
                        ?>
                            <a class="nav-item nav-link text-primary text-right" href="<?= URL_BASE ?>/Fornecedores">Fornecedores<span class="sr-only"></a>
                            <a class="nav-item nav-link text-primary text-right" href="<?= URL_BASE ?>/Categorias">Categorias<span class="sr-only"></a>
                            <a class="nav-item nav-link text-primary text-right" href="<?= URL_BASE ?>/Compras">Compras<span class="sr-only"></a>
                            <a class="nav-item nav-link text-primary text-right" href="<?= URL_BASE ?>/Produtos">Produtos<span class="sr-only"></a>
                    <?php endswitch; ?>
                    <a class="nav-item nav-link text-danger container text-right" href="<?= URL_BASE ?>/logout">Logout</a>
                <?php else : ?>
                    <a class="nav-item nav-link text-primary container text-right" href="<?= URL_BASE ?>/login">Login</a>
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
    if ($js != null) :
        require_once 'App/views/' . $js . '.php';
    endif;
    ?>

</body>

</html>