<h1>Dashboard Vendedor</h1>
<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeFuncionario'])) : ?>

    <div class="alert alert-success" role="alert">
        <h5>Vendedor logado com sucesso</h5>
    </div>
    <div class="row">
        <div class="card mt-3 border-0">
            <div class="card-body px-2">
                <i class="fas fa-user"></i> <strong>Nome</strong>
                <p class="text-muted"><?= htmlentities(utf8_encode($_SESSION['nomeFuncionario'])) ?></p>
                <i class="fas fa-at"></i><strong> CPF</strong>
                <p class="text-muted"><?= htmlentities(utf8_encode($_SESSION['cpfFuncionario'])) ?></p>

            </div>
        </div>
    </div>
<?php endif; ?>