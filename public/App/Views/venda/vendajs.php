<script>
    function load_data(page) {

        var ajax_request = new XMLHttpRequest();
        ajax_request.open('GET', '<?= url('navegavenda') . '/' ?>' + page);
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

        // carregar dos dados das vendas
        load_data(1);

        // ************************************************************************
        // INCLUIR NOVA VENDA

        // clicar no botão de nova venda
        $('#btIncluir').on('click', function() {

            $("#quantidade_venda").val("");
            $("#data_venda").val("");
            $("#valor_venda").val("");
            $("#id_cliente").val("");
            $("#id_produto").val("");
            $("#id_funcionario").val("<?= $_SESSION['id'] ?>");// limpar os inputs
            // limpar as mensagens de erros de validação
            $("#mensagem_erro").html("");
            $("#mensagem_erro").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('incluirvenda') ?>", // chamar o método para obter o CSRF token
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // receber o CSRF token o colocá-lo no input hidden do form modal
                    $('[name="CSRF_token"]').val(data.token);
                    // apresentar o modal
                    $("#modalNovaVenda").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalNovaVenda").modal('hide');
                }
            });
        })


        // salvar os dados da inclusão
        $('#btSalvarInclusao').on('click', function() {
            $.ajax({
                url: "<?= url('salvarinclusaovenda') ?>", // chama o método para inclusão
                type: "POST",
                data: $('#formInclusao').serialize(), //codifica o formulário como uma string para envio.
                dataType: "JSON",
                success: function(data) {
                    $('[name="CSRF_token"]').val(data.token); // // Update CSRF hash
                    if (data.status) //if success close modal and reload ajax table
                    {  Swal.fire({
                            title: "Sucesso",
                            text: "Venda Incluída Com Sucesso",
                            icon: "success",
                        });
                        $("#modalNovaVenda").modal('hide');
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
                    $("#modalNovaVenda").modal('hide');
                }
            });
        })


        // ************************************************************************
        // ALTERAÇÃO DOS DADOS DA VENDA

        // Clicar no botão de alteração de dados de uma venda
        // observe que o botão é inserido dinamicamente na página

        $(document).on("click", "#btAlterar", function() {

            var id = $(this).attr("data-id");

            $("#quantidade_venda_alteracao").val("");
            $("#data_venda_alteracao").val("");
            $("#valor_venda_alteracao").val("");
            $("#id_cliente_alteracao").val("");
            $("#id_produto_alteracao").val("");
            $("#id_funcionario_alteracao").val("<?= $_SESSION['id'] ?>");
            $("#mensagem_erro_alteracao").html("");
            $("#mensagem_erro_alteracao").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('alteracaovenda') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash
                    $('[name="CSRF_token"]').val(data.token);

                    $('[name="quantidade_venda_alteracao"]').val(data.quantidade_venda);
                    $('[name="data_venda_alteracao"]').val(data.data_venda);
                    $('[name="valor_venda_alteracao"]').val(data.valor_venda);
                    $('[name="id_cliente_alteracao"]').val(data.id_cliente);
                    $('[name="id_produto_alteracao"]').val(data.id_produto);
                    $('[name="id_funcionario_alteracao"]').val("<?= $_SESSION['id'] ?>");
                    $('[name="id_alteracao"]').val(data.id);

                    $("#modalAlterarVenda").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalAlterarVenda").modal('hide');
                }
            });

        });

        // salvar dados da altercao da venda
        $('#btSalvarAlteracao').on('click', function() {

            $.ajax({
                url: "<?= url('gravaralteracaovenda') ?>",
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
                            text: "Venda Alterada Com Sucesso",
                            icon: "success",
                        });
                        $("#modalAlterarVenda").modal('hide');

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
                    $("#modalAlterarVenda").modal('hide');

                }
            });
        })

        // ************************************************************************
        // EXCLUSÃO DA VENDA

        // Clicar no botão de exclusão de uma venda
        // observe que o botão é inserido dinamicamente na página
        $(document).on("click", "#btExcluir", function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-produto");

            Swal.fire({
                title: 'Confirma a Exclusão da Venda?',
                //text: nome, //Adaptar para aparecer o nome do produto
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluirvenda') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status) //if success close modal and reload ajax table
                            {
                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Venda Excluida Com Sucesso",
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