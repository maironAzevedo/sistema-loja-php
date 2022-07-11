<?php

if (isset($data['mensagens'])) : ?>
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
endif;
?>
<form action="<?= URL_BASE . '/logar' ?>" method="post">
  <input id="CSRF_token" type="hidden" name="CSRF_token" value="<?= $_SESSION['CSRF_token'] ?>">
  <div class="col-6">
    
    <div class="form-group">
      <label for="cpf">CPF</label>
      <input id="cpf" class="form-control" type="cpf" name="cpf" value="" placeholder="Digite aqui seu CPF" required>
    </div>
    <div class="form-group">
      <label for="senha">Senha</label>
      <input id="senha" class="form-control" type="password" name="senha" value="" placeholder="Digite aqui sua senha" required>
    </div>

    <div class="form-group">
      <?php echo $data['imagem'] ?>
    </div>

    <div class="form-group">
      <input id="captcha" class="form-control" type="text" name="captcha" placeholder="Digite o código acima" required>
    </div>

    <div class="form-group">
    <button type="submit" class="btn btn-primary">Logar</button>
    </div>

  </div>
</form>