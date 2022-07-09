<script>
    function load_data(page) {

        var ajax_request = new XMLHttpRequest();
        ajax_request.open('GET', '<?= url('navegacategoria') . '/' ?>' + page);
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

        // carregar dos dados das categorias
        load_data(1);

        // ************************************************************************
        // INCLUIR NOVA CATEGORIA

        // clicar no botão de nova categoria
        $('#btIncluir').on('click', function() {

            $("#nome_categoria").val(""); // limpar os inputs
            //$("#senha").val(""); // limpar as mensagens de erros de validação
            $("#mensagem_erro").html("");
            $("#mensagem_erro").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('incluircategoria') ?>", // chamar o método para obter o CSRF token
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // receber o CSRF token o colocá-lo no input hidden do form modal
                    $('[name="CSRF_token"]').val(data.token);
                    // apresentar o modal
                    $("#modalNovaCategoria").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalNovaCategoria").modal('hide');
                }
            });
        })


        // salvar os dados da inclusão
        $('#btSalvarInclusao').on('click', function() {
            $.ajax({
                url: "<?= url('salvarinclusaocategoria') ?>", // chama o método para inclusão
                type: "POST",
                data: $('#formInclusao').serialize(), //codifica o formulário como uma string para envio.
                dataType: "JSON",
                success: function(data) {
                    $('[name="CSRF_token"]').val(data.token); // // Update CSRF hash
                    if (data.status) //if success close modal and reload ajax table
                    {  Swal.fire({
                            title: "Sucesso",
                            text: "Categoria Incluída Com Sucesso",
                            icon: "success",
                        });
                        $("#modalNovaCategoria").modal('hide');
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
                    $("#modalNovaCategoria").modal('hide');
                }
            });
        })


        // ************************************************************************
        // ALTERAÇÃO DOS DADOS DA CATEGORIA

        // Clicar no botão de alteração de dados de uma categoria
        // observe que o botão é inserido dinamicamente na página

        $(document).on("click", "#btAlterar", function() {

            var id = $(this).attr("data-id");

            $("#nome_categoria_alteracao").val("");
            $("#mensagem_erro_alteracao").html("");
            $("#mensagem_erro_alteracao").removeClass("alert alert-danger")

            $.ajax({
                url: "<?= url('alteracaocategoria') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash
                    $('[name="CSRF_token"]').val(data.token);

                    $('[name="nome_categoria_alteracao"]').val(data.nome_categoria);
                    $('[name="id_alteracao"]').val(data.id);

                    $("#modalAlterarCategoria").modal('show');
                },
                error: function(data) {
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });
                    $("#modalAlterarCategoria").modal('hide');
                }
            });

        });

        // salvar dados da altercao da categoria
        $('#btSalvarAlteracao').on('click', function() {

            $.ajax({
                url: "<?= url('gravaralteracaocategoria') ?>",
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
                            text: "Categoria Alterada Com Sucesso",
                            icon: "success",
                        });
                        $("#modalAlterarCategoria").modal('hide');

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
                    $("#modalAlterarCategoria").modal('hide');

                }
            });
        })

        // ************************************************************************
        // EXCLUSÃO DA CATEGORIA

        // Clicar no botão de exclusão de um usuário
        // observe que o botão é inserido dinamicamente na página
        $(document).on("click", "#btExcluir", function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-nome");

            Swal.fire({
                title: 'Confirma a Exclusão da Categoria?',
                text: nome,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluircategoria') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status) //if success close modal and reload ajax table
                            {
                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Categoria Excluida Com Sucesso",
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