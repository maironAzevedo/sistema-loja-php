<!-- Button trigger modal -->
<button type="button" id="btIncluir" class="btn btn-outline-primary mb-1">
    Novo
</button>

<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Quantidade Compra</th>
            <th>Data Compra</th>
            <th>Valor Compra</th>
            <th>Fornecedor</th>
            <th>Produto</th>
            <th>Funcionario</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody name="contudoTabela" id="contudoTabela">
    </tbody>
</table>

<div id="pagination_link"></div>


<!-- Modal Inclusão da compra-->
<div class="modal fade" id="modalNovaCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nova Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= url('salvarinclusaocompra') ?>" id="formInclusao" method="POST">
                    <div id="mensagem_erro" name="mensagem_erro"></div>
                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <div class="form-group">
                        <label for="quantidade_compra">Quantidade Compra*</label>
                        <input type="text" class="form-control" id="quantidade_compra" name="quantidade_compra">
                    </div>
                    <div class="form-group">
                        <label for="data_compra">Data Compra*</label>
                        <input type="data_compra" class="form-control" id="data_compra" name="data_compra">
                    </div>
                    <div class="form-group">
                        <label for="valor_compra">Valor Compra*</label>
                        <input type="valor_compra" class="form-control" id="valor_compra" name="valor_compra">
                    </div>
                    <div class="form-group">
                        <label for="id_fornecedor">Fornecedor*</label>
                        <select type="id_fornecedor" class="form-control" id="id_fornecedor" name="id_fornecedor">
                        <?php
                        $fornecedores = $data['fornecedores_lista'];
                        if (!empty($fornecedores)) :
                            foreach ($fornecedores as $fornecedor) { ?>
                                    <option value="<?= $fornecedor['id']; ?>"><?= $fornecedor['razao_social']; ?></option>
                        <?php } endif;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_produto">Produto*</label>
                        <select type="id_produto" class="form-control" id="id_produto" name="id_produto">
                        <?php
                        $produtos = $data['produtos_lista'];
                        if (!empty($produtos)) :
                            foreach ($produtos as $produto) { ?>
                                <option value="<?= $produto['id']; ?>"><?= $produto['nome_produto']; ?></option>
                        <?php } endif;?>
                        </select>
                    </div>
                    <input type="hidden" class="form-control" id="id_funcionario" name="id_funcionario" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btSalvarInclusao" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal alteracao da compra-->
<div class="modal fade" id="modalAlterarCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= url('gravaralteracaocompra') ?>" id="formAltercao" method="POST">

                    <div id="mensagem_erro_alteracao" name="mensagem_erro_alteracao"></div>

                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <input type="hidden" id="id_alteracao" name="id_alteracao" value="" />

                    <div class="form-group">
                        <label for="quantidade_compra">Quantidade Compra*</label>
                        <input type="text" class="form-control" id="quantidade_compra_alteracao" name="quantidade_compra_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="data_compra">Data Compra*</label>
                        <input type="data_compra" class="form-control" id="data_compra_alteracao" name="data_compra_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="valor_compra">Valor Compra*</label>
                        <input type="valor_compra" class="form-control" id="valor_compra_alteracao" name="valor_compra_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="id_fornecedor">Fornecedor*</label>
                        <select type="id_fornecedor" class="form-control" id="id_fornecedor_alteracao" name="id_fornecedor_alteracao">
                        <?php
                        $fornecedores = $data['fornecedores_lista'];
                        if (!empty($fornecedores)) :
                            foreach ($fornecedores as $fornecedor) { ?>
                                    <option value="<?= $fornecedor['id']; ?>"><?= $fornecedor['razao_social']; ?></option>
                        <?php } endif;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_produto">Produto*</label>
                        <select type="id_produto" class="form-control" id="id_produto_alteracao" name="id_produto_alteracao">
                        <?php
                        $produtos = $data['produtos_lista'];
                        if (!empty($produtos)) :
                            foreach ($produtos as $produto) { ?>
                                    <option value="<?= $produto['id']; ?>"><?= $produto['nome_produto']; ?></option>
                        <?php } endif;?>
                        </select>
                    </div>
                    <input type="hidden" class="form-control" id="id_funcionario_alteracao" name="id_funcionario_alteracao" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btSalvarAlteracao" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>