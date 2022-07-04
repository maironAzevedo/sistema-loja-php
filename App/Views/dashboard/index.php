<h1>Dashboard</h1>
<?php

switch ($_SESSION['papelUsuario']) {
    case 0:
        $papelUsuario = 'Administrador';
        break;
    case 1:
        $papelUsuario = 'Comprador';
        break;
    case 2:
        $papelUsuario = 'Vendedor';
        break;
}

// listando os artigos
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
<div class="card mt-3 border-0">
    <div class="card-body px-2">
        <i class="fas fa-user"></i> <strong><?= htmlentities(utf8_encode($_SESSION['nomeUsuario'])) ?></strong>
        <p class="text-muted"><?=$papelUsuario?></p>
    </div>
</div>
<div class="row">
    <div class="card mt-3 border-0">
        <div class="card-body px-2">
        <a href="#" class="btn btn-outline-success">Artigos</a>
        </div>
    </div>
    <div class="card mt-3 border-0">
        <div class="card-body px-2">
        <a href="<?= url('painelusuario') ?>" class="btn btn-outline-primary">Usuarios</a>
        </div>
    </div>
</div>

<?php endif; ?>