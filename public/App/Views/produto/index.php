<!-- Button trigger modal -->
<button type="button" id="btIncluir" class="btn btn-outline-primary mb-1">
    Novo
</button>

<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Liberado p/ Venda</th>
            <th>Categoria</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody name="contudoTabela" id="contudoTabela">
    </tbody>
</table>

<div id="pagination_link"></div>


<!-- Modal Inclusão do produto-->
<div class="modal fade" id="modalNovoProduto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= url('salvarinclusaoproduto') ?>" id="formInclusao" method="POST">
                    <div id="mensagem_erro" name="mensagem_erro"></div>
                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <div class="form-group">
                        <label for="nome_produto">Nome*</label>
                        <input type="text" class="form-control" id="nome_produto" name="nome_produto">
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição*</label>
                        <input type="text_area" class="form-control" id="descricao" name="descricao">
                    </div>
                    <div class="form-group">
                        <label for="liberado_venda">Liberado p/ Venda*</label>
                        <select type="liberado_venda" class="form-control" id="liberado_venda" name="liberado_venda">
                            <option value="S"><?= 'Sim'; ?></option>
                            <option value="N"><?= 'Não' ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_categoria">Categoria*</label>
                        <select type="id_categoria" class="form-control" id="id_categoria" name="id_categoria">
                            <?php
                            $categorias = $data['categorias_lista'];
                            if (!empty($categorias)) :
                                foreach ($categorias as $categoria) { ?>
                                    <option value="<?= $categoria['id']; ?>"><?= $categoria['nome_categoria']; ?></option>
                            <?php }
                            endif; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btSalvarInclusao" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal alteracao do produto-->
<div class="modal fade" id="modalAlterarProduto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= url('gravaralteracaoproduto') ?>" id="formAltercao" method="POST">

                    <div id="mensagem_erro_alteracao" name="mensagem_erro_alteracao"></div>

                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <input type="hidden" id="id_alteracao" name="id_alteracao" value="" />

                    <div class="form-group">
                        <label for="nome_produto_alteracao">Nome*</label>
                        <input type="text" class="form-control" id="nome_produto_alteracao" name="nome_produto_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="descricao_alteracao">Descrição*</label>
                        <input type="text_area" class="form-control" id="descricao_alteracao" name="descricao_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="liberado_venda_alteracao">Liberado p/ Venda*</label>
                        <select type="liberado_venda_alteracao" class="form-control" id="liberado_venda_alteracao" name="liberado_venda_alteracao">
                            <option value="S"><?= 'Sim'; ?></option>
                            <option value="N"><?= 'Não' ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_categoria_alteracao">Categoria*</label>
                        <select type="id_categoria_alteracao" class="form-control" id="id_categoria_alteracao" name="id_categoria_alteracao">
                            <?php
                            $categorias = $data['categorias_lista'];
                            if (!empty($categorias)) :
                                foreach ($categorias as $categoria) { ?>
                                    <option value="<?= $categoria['id']; ?>"><?= $categoria['nome_categoria']; ?></option>
                            <?php }
                            endif; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btSalvarAlteracao" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>