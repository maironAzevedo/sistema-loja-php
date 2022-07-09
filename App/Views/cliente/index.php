<!-- Button trigger modal -->
<button type="button" id="btIncluir" class="btn btn-outline-primary mb-1">
    Novo
</button>

<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Endereço</th>
            <th>Bairro</th>
            <th>Cidade</th>
            <th>UF</th>
            <th>CEP</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody name="contudoTabela" id="contudoTabela">
    </tbody>
</table>

<div id="pagination_link"></div>


<!-- Modal Inclusão do cliente-->
<div class="modal fade" id="modalNovoCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= url('salvarinclusaocliente') ?>" id="formInclusao" method="POST">
                    <div id="mensagem_erro" name="mensagem_erro"></div>
                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <div class="form-group">
                        <label for="nome">Nome*</label>
                        <input type="text" class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF*</label>
                        <input type="cpf" class="form-control" id="cpf" name="cpf">
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço*</label>
                        <input type="endereco" class="form-control" id="endereco" name="endereco">
                    </div>
                    <div class="form-group">
                        <label for="bairro">Bairro*</label>
                        <input type="bairro" class="form-control" id="bairro" name="bairro">
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade*</label>
                        <input type="cidade" class="form-control" id="cidade" name="cidade">
                    </div>
                    <div class="form-group">
                        <label for="uf">UF*</label>
                        <input type="uf" class="form-control" id="uf" name="uf">
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP*</label>
                        <input type="cep" class="form-control" id="cep" name="cep">
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone*</label>
                        <input type="telefone" class="form-control" id="telefone" name="telefone">
                    </div>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" class="form-control" id="email" name="email">
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

<!-- Modal alteracao do cliente-->
<div class="modal fade" id="modalAlterarCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= url('gravaralteracaocliente') ?>" id="formAltercao" method="POST">

                    <div id="mensagem_erro_alteracao" name="mensagem_erro_alteracao"></div>

                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <input type="hidden" id="id_alteracao" name="id_alteracao" value="" />

                    <div class="form-group">
                        <label for="nome">Nome*</label>
                        <input type="text" class="form-control" id="nome_alteracao" name="nome_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF*</label>
                        <input type="cpf" class="form-control" id="cpf_alteracao" name="cpf_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço*</label>
                        <input type="endereco" class="form-control" id="endereco_alteracao" name="endereco_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="bairro">Bairro*</label>
                        <input type="bairro" class="form-control" id="bairro_alteracao" name="bairro_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade*</label>
                        <input type="cidade" class="form-control" id="cidade_alteracao" name="cidade_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="uf">UF*</label>
                        <input type="uf" class="form-control" id="uf_alteracao" name="uf_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP*</label>
                        <input type="cep" class="form-control" id="cep_alteracao" name="cep_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone*</label>
                        <input type="telefone" class="form-control" id="telefone_alteracao" name="telefone_alteracao">
                    </div>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" class="form-control" id="email_alteracao" name="email_alteracao">
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