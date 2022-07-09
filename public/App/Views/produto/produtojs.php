<script>
    function load_data(page) {

        var ajax_request = new XMLHttpRequest();
        ajax_request.open('GET', '<?= url('navegaproduto') . '/' ?>' + page);
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

        // carregar dos dados dos produtos
        load_data(1);

        // ************************************************************************
        // INCLUIR NOVO PRODUTO

        // clicar no botão de novo produto
        $('#btIncluir').on('click', function() {

            $("#nome_produto").val("");
            $("#descricao").val("");
            $("#liberado_venda").val("");
            $("#id_categoria").val("");
            $("#mensagem_erro").html("");
            $("#mensagem_erro").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('incluirproduto') ?>", // chamar o método para obter o CSRF token
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // receber o CSRF token o colocá-lo no input hidden do form modal
                    $('[name="CSRF_token"]').val(data.token);
                    // apresentar o modal
                    $("#modalNovoProduto").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalNovoProduto").modal('hide');
                }
            });
        })


        // salvar os dados da inclusão
        $('#btSalvarInclusao').on('click', function() {
            $.ajax({
                url: "<?= url('salvarinclusaoproduto') ?>", // chama o método para inclusão
                type: "POST",
                data: $('#formInclusao').serialize(), //codifica o formulário como uma string para envio.
                dataType: "JSON",
                success: function(data) {
                    $('[name="CSRF_token"]').val(data.token); // // Update CSRF hash
                    if (data.status) //if success close modal and reload ajax table
                    {  Swal.fire({
                            title: "Sucesso",
                            text: "Produto Incluído Com Sucesso",
                            icon: "success",
                        });
                        $("#modalNovoProduto").modal('hide');
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
                    $("#modalNovoProduto").modal('hide');
                }
            });
        })


        // ************************************************************************
        // ALTERAÇÃO DOS DADOS DO produto

        // Clicar no botão de alteração de dados de um produto
        // observe que o botão é inserido dinamicamente na página

        $(document).on("click", "#btAlterar", function() {

            var id = $(this).attr("data-id");

            $("#nome_produto_alteracao").val("");
            $("#descricao_alteracao").val("");
            $("#liberado_venda_alteracao").val("");
            $("#id_categoria_alteracao").val("");
            $("#mensagem_erro_alteracao").html("");
            $("#mensagem_erro_alteracao").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('alteracaoproduto') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash
                    $('[name="CSRF_token"]').val(data.token);

                    $('[name="nome_produto_alteracao"]').val(data.nome_produto);
                    $('[name="descricao_alteracao"]').val(data.descricao);
                    $('[name="liberado_venda_alteracao"]').val(data.liberado_venda);
                    $('[name="id_categoria_alteracao"]').val(data.id_categoria);
                    $('[name="id_alteracao"]').val(data.id);

                    $("#modalAlterarProduto").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalAlterarProduto").modal('hide');
                }
            });

        });

        // salvar dados da altercao do produto
        $('#btSalvarAlteracao').on('click', function() {

            $.ajax({
                url: "<?= url('gravaralteracaoproduto') ?>",
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
                            text: "Produto Alterado Com Sucesso",
                            icon: "success",
                        });
                        $("#modalAlterarProduto").modal('hide');

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
                    $("#modalAlterarProduto").modal('hide');

                }
            });
        })

        // ************************************************************************
        // EXCLUSÃO DO PRODUTO

        // Clicar no botão de exclusão de um produto
        // observe que o botão é inserido dinamicamente na página
        $(document).on("click", "#btExcluir", function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-nome");

            Swal.fire({
                title: 'Confirma a Exclusão do Produto?',
                text: nome,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluirproduto') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status) //if success close modal and reload ajax table
                            {
                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Produto Excluido Com Sucesso",
                                    icon: "success",
                                });

                            } else {
                                Swal.fire({
                                    title: "Erro",
                                    text: "Erro Inesperado, verifique se as compras e vendas do produto foram excluídas também.",
                                    icon: "error",
                                });
                            }
                        },
                        error: function(data) {
                            Swal.fire({
                                title: "Erro",
                                text: "Erro Inesperado, verifique se as compras e vendas do produto foram excluídas também.",
                                icon: "error",
                            });
                        }
                    });
                }
            })
        });
    });
</script>