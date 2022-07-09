<script>
    function load_data(page) {

        var ajax_request = new XMLHttpRequest();
        ajax_request.open('GET', '<?= url('navegacompra') . '/' ?>' + page);
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

        // carregar dos dados das compras
        load_data(1);

        // ************************************************************************
        // INCLUIR NOVA COMPRA

        // clicar no botão de nova compra
        $('#btIncluir').on('click', function() {

            $("#quantidade_compra").val("");
            $("#data_compra").val("");
            $("#valor_compra").val("");
            $("#id_fornecedor").val("");
            $("#id_produto").val("");
            $("#id_funcionario").val("<?= $_SESSION['id'] ?>");// limpar os inputs
            // limpar as mensagens de erros de validação
            $("#mensagem_erro").html("");
            $("#mensagem_erro").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('incluircompra') ?>", // chamar o método para obter o CSRF token
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // receber o CSRF token o colocá-lo no input hidden do form modal
                    $('[name="CSRF_token"]').val(data.token);
                    // apresentar o modal
                    $("#modalNovaCompra").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalNovaCompra").modal('hide');
                }
            });
        })


        // salvar os dados da inclusão
        $('#btSalvarInclusao').on('click', function() {
            $.ajax({
                url: "<?= url('salvarinclusaocompra') ?>", // chama o método para inclusão
                type: "POST",
                data: $('#formInclusao').serialize(), //codifica o formulário como uma string para envio.
                dataType: "JSON",
                success: function(data) {
                    $('[name="CSRF_token"]').val(data.token); // // Update CSRF hash
                    if (data.status) //if success close modal and reload ajax table
                    {  Swal.fire({
                            title: "Sucesso",
                            text: "Compra Incluída Com Sucesso",
                            icon: "success",
                        });
                        $("#modalNovaCompra").modal('hide');
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
                    $("#modalNovaCompra").modal('hide');
                }
            });
        })


        // ************************************************************************
        // ALTERAÇÃO DOS DADOS DA COMPRA

        // Clicar no botão de alteração de dados de uma compra
        // observe que o botão é inserido dinamicamente na página

        $(document).on("click", "#btAlterar", function() {

            var id = $(this).attr("data-id");

            $("#quantidade_compra_alteracao").val("");
            $("#data_compra_alteracao").val("");
            $("#valor_compra_alteracao").val("");
            $("#id_fornecedor_alteracao").val("");
            $("#id_produto_alteracao").val("");
            $("#id_funcionario_alteracao").val("<?= $_SESSION['id'] ?>");
            $("#mensagem_erro_alteracao").html("");
            $("#mensagem_erro_alteracao").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('alteracaocompra') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash
                    $('[name="CSRF_token"]').val(data.token);

                    $('[name="quantidade_compra_alteracao"]').val(data.quantidade_compra);
                    $('[name="data_compra_alteracao"]').val(data.data_compra);
                    $('[name="valor_compra_alteracao"]').val(data.valor_compra);
                    $('[name="id_fornecedor_alteracao"]').val(data.id_fornecedor);
                    $('[name="id_produto_alteracao"]').val(data.id_produto);
                    $('[name="id_funcionario_alteracao"]').val("<?= $_SESSION['id'] ?>");
                    $('[name="id_alteracao"]').val(data.id);

                    $("#modalAlterarCompra").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalAlterarCompra").modal('hide');
                }
            });

        });

        // salvar dados da altercao da compra
        $('#btSalvarAlteracao').on('click', function() {

            $.ajax({
                url: "<?= url('gravaralteracaocompra') ?>",
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
                            text: "Compra Alterada Com Sucesso",
                            icon: "success",
                        });
                        $("#modalAlterarCompra").modal('hide');

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
                    $("#modalAlterarCompra").modal('hide');

                }
            });
        })

        // ************************************************************************
        // EXCLUSÃO DA COMPRA

        // Clicar no botão de exclusão de uma compra
        // observe que o botão é inserido dinamicamente na página
        $(document).on("click", "#btExcluir", function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-produto");

            Swal.fire({
                title: 'Confirma a Exclusão da Compra?',
                //text: nome, //Adaptar para aparecer o nome do produto
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluircompra') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status) //if success close modal and reload ajax table
                            {
                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Compra Excluida Com Sucesso",
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