<?php
if (isset($data['mensagens'])) { ?>
  <div class="col-6">
    <div class="alert alert-danger" role="alert">
      <?php

      foreach ($data['mensagens'] as $mensagem) {
        echo $mensagem . "<br>";
      }

      ?>
    </div>
  </div>
<?php
}
?>
<form action="<?= URL_BASE . '/logar' ?>" method="post">
  <input id="CSRF_token" type="hidden" name="CSRF_token" value="<?= $_SESSION['CSRF_token'] ?>">
  <div class="col-6">
    <div class="form-group">
      <label for="email">Email</label>
      <input id="email" class="form-control" type="email" name="email" value="andre@teste.com" placeholder="ana@teste.com">
    </div>
    <div class="form-group">
      <label for="senha">Senha</label>
      <input id="senha" class="form-control" type="password" name="senha" value="123" placeholder="123">
    </div>

    <div class="form-group">
      <?php echo $data['imagem'] ?>
    </div>

    <div class="form-group">
      <input id="captcha" class="form-control" type="text" name="captcha" placeholder="Digite o código acima">
    </div>

    <div class="form-group">
    <button type="submit" class="btn btn-primary">Logar</button>
    </div>

  </div>
</form>