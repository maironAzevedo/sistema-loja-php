<!-- Button trigger modal -->
<button type="button" id="btIncluir" class="btn btn-outline-primary mb-1">
    Novo
</button>

<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Quantidade Venda</th>
            <th>Data Venda</th>
            <th>Valor Venda</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Funcionario</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody name="contudoTabela" id="contudoTabela">
    </tbody>
</table>

<div id="pagination_link"></div>


<!-- Modal Inclusão da venda-->
<div class="modal fade" id="modalNovaVenda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nova Venda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= url('salvarinclusaovenda') ?>" id="formInclusao" method="POST">
                    <div id="mensagem_erro" name="mensagem_erro"></div>
                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <div class="form-group">
                        <label for="quantidade_venda">Quantidade Venda*</label>
                        <input type="text" class="form-control" id="quantidade_venda" name="quantidade_venda">
                    </div>
                    <div class="form-group">
                        <label for="data_venda">Data Venda*</label>
                        <input type="data_venda" class="form-control" id="data_venda" name="data_venda">
                    </div>
                    <div class="form-group">
                        <label for="valor_venda">Valor Venda*</label>
                        <input type="valor_venda" class="form-control" id="valor_venda" name="valor_venda">
                    </div>
                    <div class="form-group">
                        <label for="id_cliente">Cliente*</label>
                        <select type="id_cliente" class="form-control" id="id_cliente" name="id_cliente">
                        <?php
                        $clientes = $data['clientes_lista'];
                        if (!empty($clientes)) :
                            foreach ($clientes as $cliente) { ?>
                                    <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
                        <?php } endif;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_produto">Produto*</label>
                        <!--<input type="id_produto" class="form-control" id="id_produto" name="id_produto">-->
                        <select type="id_produto" class="form-control" id="id_produto" name="id_produto">
                        <?php
                        $produtos = $data['produtos_lista'];
                        if (!empty($produtos)) :
                            foreach ($produtos as $produto) { ?>
                                <?php if ($produto['quantidade_disponível'] > 0 && $produto['liberado_venda'] == "S") : ?>
                                    <option value="<?= $produto['id']; ?>"><?= $produto['nome_produto']; ?></option>
                                <?php endif; ?>
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

<!-- Modal alteracao da venda-->
<div class="modal fade" id="modalAlterarVenda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Venda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= url('gravaralteracaovenda') ?>" id="formAltercao" method="POST">

                    <div id="mensagem_erro_alteracao" name="mensagem_erro_alteracao"></div>

                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <input type="hidden" id="id_alteracao" name="id_alteracao" value="" />

                    <div class="form-group">
                        <label for="quantidade_venda">Quantidade Venda*</label>
                        <input type="text" class="form-control" id="quantidade_venda_alteracao" name="quantidade_venda_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="data_venda">Data Venda*</label>
                        <input type="data_venda" class="form-control" id="data_venda_alteracao" name="data_venda_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="valor_venda">Valor Venda*</label>
                        <input type="valor_venda" class="form-control" id="valor_venda_alteracao" name="valor_venda_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="id_cliente">Cliente*</label>
                        <select type="id_cliente" class="form-control" id="id_cliente_alteracao" name="id_cliente_alteracao">
                        <?php
                        $clientes = $data['clientes_lista'];
                        if (!empty($clientes)) :
                            foreach ($clientes as $cliente) { ?>
                                    <option value="<?= $cliente['id']; ?>"><?= $cliente['nome']; ?></option>
                        <?php } endif;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_produto">Produto*</label>
                        <!--<input type="id_produto" class="form-control" id="id_produto_alteracao" name="id_produto_alteracao">-->
                        <select type="id_produto" class="form-control" id="id_produto_alteracao" name="id_produto_alteracao">
                        <?php
                        $produtos = $data['produtos_lista'];
                        if (!empty($produtos)) :
                            foreach ($produtos as $produto) { ?>
                                <?php if ($produto['quantidade_disponível'] > 0 && $produto['liberado_venda'] == "S") : ?>
                                    <option value="<?= $produto['id']; ?>"><?= $produto['nome_produto']; ?></option>
                                <?php endif; ?>
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