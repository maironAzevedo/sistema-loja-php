<script>
    function load_data(page) {

        var ajax_request = new XMLHttpRequest();
        ajax_request.open('GET', '<?= url('navegafornecedor') . '/' ?>' + page);
        ajax_request.send();
        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {

                var response = JSON.parse(ajax_request.responseText);

                document.getElementById('contudoTabela').innerHTML = response.corpoTabela;
                document.getElementById('pagination_link').innerHTML = response.links;

            }

        }
    }

    $(document).ready(function() {

        // carregar dos dados dos fornecedores
        load_data(1);

        // ************************************************************************
        // INCLUIR NOVO FORNECEDOR

        // clicar no botão de novo fornecedor
        $('#btIncluir').on('click', function() {

            $("#razao_social").val("");
            $("#cnpj").val("");
            $("#endereco").val("");
            $("#bairro").val("");
            $("#cidade").val("");
            $("#uf").val("");
            $("#cep").val("");
            $("#telefone").val("");
            $("#email").val(""); // limpar os inputs
            //$("#senha").val(""); // limpar as mensagens de erros de validação
            $("#mensagem_erro").html("");
            $("#mensagem_erro").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('incluirfornecedor') ?>", // chamar o método para obter o CSRF token
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // receber o CSRF token o colocá-lo no input hidden do form modal
                    $('[name="CSRF_token"]').val(data.token);
                    // apresentar o modal
                    $("#modalNovoFornecedor").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalNovoFornecedor").modal('hide');
                }
            });
        })


        // salvar os dados da inclusão
        $('#btSalvarInclusao').on('click', function() {
            $.ajax({
                url: "<?= url('salvarinclusaofornecedor') ?>", // chama o método para inclusão
                type: "POST",
                data: $('#formInclusao').serialize(), //codifica o formulário como uma string para envio.
                dataType: "JSON",
                success: function(data) {
                    $('[name="CSRF_token"]').val(data.token); // // Update CSRF hash
                    if (data.status) //if success close modal and reload ajax table
                    {  Swal.fire({
                            title: "Sucesso",
                            text: "Fornecedor Incluído Com Sucesso",
                            icon: "success",
                        });
                        $("#modalNovoFornecedor").modal('hide');
                    } else {
                        $('[name="mensagem_erro"]').addClass('alert alert-danger');
                        $('[name="mensagem_erro"]').html(data.erros);
                    }
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalNovoFornecedor").modal('hide');
                }
            });
        })


        // ************************************************************************
        // ALTERAÇÃO DOS DADOS DO USUÁRIO

        // Clicar no botão de alteração de dados de um usuário
        // observe que o botão é inserido dinamicamente na página

        $(document).on("click", "#btAlterar", function() {

            var id = $(this).attr("data-id");

            $("#razao_social_alteracao").val("");
            $("#cnpj_alteracao").val("");
            $("#endereco_alteracao").val("");
            $("#bairro_alteracao").val("");
            $("#cidade_alteracao").val("");
            $("#uf_alteracao").val("");
            $("#cep_alteracao").val("");
            $("#telefone_alteracao").val("");
            $("#email_alteracao").val("");
            $("#mensagem_erro_alteracao").html("");
            $("#mensagem_erro_alteracao").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('alteracaofornecedor') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash
                    $('[name="CSRF_token"]').val(data.token);

                    $('[name="razao_social_alteracao"]').val(data.razao_social);
                    $('[name="cnpj_alteracao"]').val(data.cnpj);
                    $('[name="endereco_alteracao"]').val(data.endereco);
                    $('[name="bairro_alteracao"]').val(data.bairro);
                    $('[name="cidade_alteracao"]').val(data.cidade);
                    $('[name="uf_alteracao"]').val(data.uf);
                    $('[name="cep_alteracao"]').val(data.cep);
                    $('[name="telefone_alteracao"]').val(data.telefone);
                    $('[name="email_alteracao"]').val(data.email);
                    $('[name="id_alteracao"]').val(data.id);

                    $("#modalAlterarFornecedor").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalAlterarFornecedor").modal('hide');
                }
            });

        });

        // salvar dados da altercao do fornecedor
        $('#btSalvarAlteracao').on('click', function() {

            $.ajax({
                url: "<?= url('gravaralteracaofornecedor') ?>",
                type: "POST",
                data: $('#formAltercao').serialize(),
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash
                    $('[name="CSRF_token"]').val(data.token);

                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            title: "Sucesso",
                            text: "Fornecedor Alterado Com Sucesso",
                            icon: "success",
                        });
                        $("#modalAlterarFornecedor").modal('hide');

                    } else {

                        $('[name="mensagem_erro_alteracao"]').addClass('alert alert-danger');
                        $('[name="mensagem_erro_alteracao"]').html(data.erros);

                    }
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalAlterarFornecedor").modal('hide');

                }
            });
        })

        // ************************************************************************
        // EXCLUSÃO DO USUÁRIO

        // Clicar no botão de exclusão de um usuário
        // observe que o botão é inserido dinamicamente na página
        $(document).on("click", "#btExcluir", function() {

            var id = $(this).attr("data-id");
            var cnpj = $(this).attr("data-cnpj");

            Swal.fire({
                title: 'Confirma a Exclusão do Fornecedor?',
                text: cnpj,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluirfornecedor') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status) //if success close modal and reload ajax table
                            {
                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Fornecedor Excluido Com Sucesso",
                                    icon: "success",
                                });

                            } else {
                                Swal.fire({
                                    title: "Erro",
                                    text: "Erro Inesperado",
                                    icon: "error",
                                });
                            }
                        },
                        error: function(data) {
                            Swal.fire({
                                title: "Erro",
                                text: "Erro Inesperado",
                                icon: "error",
                            });
                        }
                    });
                }
            })
        });
    });
</script>