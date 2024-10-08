var vUrlListarArmarios = "/listarArmarios";
var vUrlListarTipoDocumento = "/listarTipodocumento";
var vUrlListarTipoPerfis = "/listarPerfis";
var vUrlListarUsuarios = "/listarUsuarios";
var vUrlListarOm = "/listarOms";
//var vUrlProdutos = "http://127.0.0.1:5000/Produtos";
let listDocumentos = [];
let listDocumentosPrimaria = [];
let listDocumentosServidor = [];
let totalDocumnetos = 0;
let nipValido = false;
$(document).ready(function (e) {
    //carregarSecoes();
    //carregarProdutos();
    //console.log("carraga");

});

function verificaHash() {
    let hash = $('#formCadDocumento #Hash').val()
    $.ajax({
        url: "./verifica-se-hash-existe?hash=" + hash,
        type: 'GET',
        data: "",
        processData: false,
        contentType: false,
        success: function (data) {
            if(data > 0){
                toastr.error('Esse Hash já existe em outra página');
                $('#AnexarDocumento').prop('disabled', true)
                $('#IndexarDocumento').prop('disabled', true)
            }else{
                $('#AnexarDocumento').prop('disabled', false)
                $('#IndexarDocumento').prop('disabled', false)
            }
        }
    });
}

function carregarArmarios() {
    $.ajax({
        url: vUrlListarArmarios,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            //console.log(data);
            var sel = $("#gradeArmarios");
            sel.empty();
            data.forEach(e => {
                sel.append('<tr><td>' + e.nomeexterno + '</td><td><form id="formGerenciarArmario" name="formGerenciarArmario"><input type="hidden" name="idArmario" id="idArmario" value="' + e.id + '" /><input type="button" class="btn btn-primary btnGerenciarArmario" data-bs-toggle="modal" data-bs-target="#GerenciarArmario" data-id="' + e.id + '" value="Gerenciar"></form></td><td><button class="btn btn-warning btnAlterarArmario" data-bs-toggle="modal" data-bs-target="#AlteraArmario" data-id="' + e.id + '" data-ni="' + e.nomeinterno + '" data-ne="' + e.nomeexterno + '" data-cd="' + e.codigo + '">Editar</button></td><td><form method="post" id="excluir' + e.id + '" action=""><input type="hidden" id="idArmario" name="idArmario" value="' + e.id + '"><button class="btn btn-danger excluir" data-id="' + e.id + '" data-bs-toggle="modal" data-bs-target="#ExcluirArmario" type="button">Excluir</button></form></td></tr>');
            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#btnCadArmario').on('click', function (e) {
    if (($('#formCadArmario #codigo').val() == "") || ($('#formCadArmario #nomeInterno').val() == "") || ($('#formCadArmario #nomeExterno').val() == "")) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    var formdata = new FormData($("form[id='formCadArmario']")[0]);
    $.ajax({
        type: 'POST',
        url: "/cadastrarArmario",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            console.log(d);
            carregarArmarios();
            $('#formCadArmario #codigo').val("");
            $('#formCadArmario #nomeInterno').val("");
            $('#formCadArmario #nomeExterno').val("");
            toastr.success('Armário cadastrado com sucesso');

        },
        error: function (d) {
            //console.log(d["status"]);
            if (d["status"] == 409)
                alertas("Armário já cadastrado no sistema", '#modCadArmario', 'alert_danger');

            if (d["status"] == 500)
                alertas("Houve um problema para cadastrar o armário. ", '#modCadArmario', 'alert_danger');
        }
    });
});

$(document).on('click', '.btnAlterarArmario', function (e) {
    $('#formAltArmario #codigo').val($(this).data("cd"));
    $('#formAltArmario #id').val($(this).data("id"));
    $('#formAltArmario #nomeInterno').val($(this).data("ni"));
    $('#formAltArmario #nomeExterno').val($(this).data("ne"));
    $('#AlteraArmario').modal()
    $('.opcoesConfirmacao').css('display', 'none');
});

function carregarTipoDocVincArmarios(id) {

    $.ajax({
        url: "/listarTipoDocumentosArmario?id=" + id,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            //console.log(data);

            var sel = $("#GradeTipoDocArmario");
            sel.empty();
            data.forEach(e => {
                //sel.append('<div><div>' + e.desctipo + 'Nome</div><div><button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ConfDesArmTipoDoc"" data-idArm="' + e.idArmario + '" data-idDoc="' + e.id + '" >Excluir Relação</button></div></div>')
                sel.append('<tr><td>' + e.desctipo + '</td><td><button class="btn btn-danger desvincularTipoDocArmario" data-bs-toggle="modal" data-bs-target="#ConfDesArmTipoDoc" data-id="' + e.id + '" data-ArmarioId="' + id + '">Excluir Relação</button></td></tr>');
            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });

}
function carregarTipoDocNaoVincArmarios(id) {
    var formdata = new FormData($("form[id='formGerenciarArmario']")[0]);
    $.ajax({
        type: 'GET',
        url: "/listarTipoDocumentosNaoPertencentesArmario?id=" + id,
        data: formdata,
        processData: false,
        dataType: 'json',
        contentType: 'application/json',
        success: function (d) {
            $("#GerenciarArmario").modal()
            var sel = $("#GerenciarArmario #formListaDocumentos select");
            sel.empty();
            d.forEach(e => {
                sel.append('<option value="' + e.id + '">' + e.desctipo + '</option>');
            });
        },
        error: function (d) {
            //console.log(d);
            alertas('Erro', '#modLogin', 'alert_danger');
        }
    });
}

$(document).on('click', '.btnGerenciarArmario', function (e) {
    carregarTipoDocNaoVincArmarios($(this).data("id"));
    $('#formListaDocumentos #IdArmario').val($(this).data("id"));
    carregarTipoDocVincArmarios($(this).data("id"));
});

$(document).on('click', '#GradeTipoDocArmario .desvincularTipoDocArmario', function (e) {
    $('#ConfDesArmTipoDoc').modal()
    $('#formDesArmTipoDoc #idTipoDoc').val($(this).data("id"));
    $('#formDesArmTipoDoc #idArmario').val($(this).data("armarioid"));
});

$(document).on('click', '#btnConfirmaDesArmTipoDoc', function (e) {
    dados = JSON.stringify({
        idArmario: $('#formDesArmTipoDoc #idArmario').val(),
        idTipoDoc: $('#formDesArmTipoDoc #idTipoDoc').val(),
    }, null, 2)

    $.ajax({
        type: 'POST',
        url: "/ExcluiVinculoArmaTipoDoc",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            carregarTipoDocNaoVincArmarios($('#formDesArmTipoDoc #idArmario').val());
            $('#formListaDocumentos #IdArmario').val($('#formDesArmTipoDoc #idArmario').val());
            carregarTipoDocVincArmarios($('#formDesArmTipoDoc #idArmario').val());
            toastr.success('Vinculo Excluido com sucesso');
            FecharModal('#ConfDesArmTipoDoc');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#ConfDesArmTipoDoc', 'alert_danger');
            console.log('erro ao excluir ' + d);
        }
    });
});

//$(document).on('click', '.vincArmarioTipoDoc', function (e) {
$('.vincArmarioTipoDoc').on('click', function (e) {
    var formdata = new FormData($("form[id='formListaDocumentos']")[0]);
    $.ajax({
        type: 'POST',
        url: "/vincular-documentos-armarios",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarTipoDocNaoVincArmarios($('#formListaDocumentos #IdArmario').val());
            carregarTipoDocVincArmarios($('#formListaDocumentos #IdArmario').val());

            toastr.success('Armário e documento vinculados com sucesso');
        },
        error: function (d) {
            toastr.error(d.responseJSON['msg']);
        }
    });
});

$(document).on('click', '#exibConfirmaAlteracaoArmario', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoArmario', function (e) {
    if (($('#formAltArmario #codigo').val() == "") || ($('#formAltArmario #nomeInterno').val() == "") || ($('#formAltArmario #nomeExterno').val() == "")) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    var formdata = new FormData($("form[id='formAltArmario']")[0]);
    $.ajax({
        type: 'POST',
        url: "/alterarArmario",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            carregarArmarios();
            $('#formAltArmario #id').val("");
            $('#formAltArmario #codigo').val("");
            $('#formAltArmario #nomeInterno').val("");
            $('#formAltArmario #nomeExterno').val("");
            $('.opcoesConfirmacao').css('display', 'none');
            toastr.success('Armário atualizado com sucesso');
            FecharModal('#AlteraArmario');
        },
        error: function (d) {
            toastr.error('Houve um problema para atualizar o armário');
        }

    });
});

$(document).on('click', '#btnNaoConfirmaAlteracaoArmario', function (e) {
    $('#formAltArmario #id').val("");
    $('#formAltArmario #codigo').val("");
    $('#formAltArmario #nomeInterno').val("");
    $('#formAltArmario #nomeExterno').val("");
    $('.opcoesConfirmacao').css('display', 'none');
    FecharModal('#AlteraArmario');
});

$(document).on('click', '.excluir', function (e) {
    $('#ExcluirArmario').modal()
    $('#ExcluirArmario #id').val($(this).data("id"));
});

$(document).on('click', '.btnConfirmaExcluirArmario', function (e) {
    var formdata = new FormData($("form[id='formExcluirArmario']")[0]);
    $.ajax({
        type: 'POST',
        url: "/excluirArmario",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            console.log(d);
            carregarArmarios();
            $(this).data("id", "");
            toastr.success('Armário excluído com sucesso');
            FecharModal('#ExcluirArmario');
        },
        error: function (d) {
            toastr.error('Houve um problema para excluir o armário. Verifique se existem tipo de documentos vinculados a ele antes de excluir.');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirArmario', function (e) {
    FecharModal('#ConfDesArmTipoDoc');
});

function carregarDocumentos() {
    $.ajax({
        url: "/listarDocumentos",
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            console.log(data);
            var sel = $("#documentosLista");
            sel.empty();
            data.forEach(e => {
                sel.append('<tr class="clickDocumento" id="' + e.id + '" ><td>*1</td><td>' + e.nip + '</td><td>' + e.semestre + '</td><td>' + e.ano + '</td><td>' + e.desctipo + '</td><td><span id="qtd-paginas">' + e.quantidadepaginas + '</span></td></tr>');
                //sel.append('<div class="container_item_maior" id="gradeDocumentos"><div class=Descricao_maior>' + e.nip + '</div><div class=Descricao_maior>' + e.semestre + '</div><div class=Descricao_maior>' + e.ano + '</div><div class=Descricao_maior>' + e.desctipo + '</div><div class=Descricao_maior>' + e.nomeArmario + '</div><div class=Descricao_maior><form method="post" id="" name="" action="/tratar-documento"><input type="hidden" id="idDocumento" name="idDocumento" value="' + e.id + '"><input type="submit" id="btnAbrirDocumento" name="btnAbrirDocumento" class="btn btn-primary btnAbrirDocumento" value="Tratar Documento"></form></div></div>');

            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#formCadDocumento #btnCadDocumento').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadDocumento']")[0]);
    $.ajax({
        type: 'POST',
        url: "/cadastrarDocumento",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            $("#formCadDocumento #flagCadastro").val("1").trigger('change');
            //carregarDocumentos();
            /* $("#formCadDocumento #ListArmarioDocumento").val("");
             $('#formCadDocumento #SelectTipoDoc').val("");
             $('#formCadDocumento #semestre').val("");
             $('#formCadDocumento #ano').val("");
             $('#formCadDocumento #Nip').val("");*/
            toastr.success('Documento cadastrado com Sucesso')
        },
        error: function (d) {
            toastr.error(d.responseJSON['msg'])
        }
    });
});
function fileListFrom(files) {
    const b = new ClipboardEvent("").clipboardData || new DataTransfer()
    for (const file of files) b.items.add(file)
    return b.files
}

$('#btnIncluiPag').on('click', function (e) {
    var formdata = new FormData($("form[id='formIncluirPagDoc']")[0]);
    //var formdataDocumento = new FormData($("form[id='gradeDocumentos']")[0]);

    /*var teste = $('input[name="documentoEscolhido"]:checked').toArray().map(function (check) {
        return $(check).val();
    });
*/

    $.ajax({
        type: 'POST',
        url: "/cadastrarPagina",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            console.log(d);
            toastr.success('Documento cadastrado com sucesso');
            $('#formCadDocumento #Nip').mask('00.0000.00');
            setTimeout(function () {
                location.reload();
            }, 3000);
        },
        error: function (d) {
            toastr.error(d.responseJSON['msg']);
        }
    });
});

function carregarTipoDocumento() {
    $.ajax({
        url: vUrlListarTipoDocumento,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            //console.log(data);
            var sel = $("#gradeListaDocumentos");
            sel.empty();
            data.forEach(e => {
                sel.append('<tr><td>' + e.desctipo + '</td><td><button class="btn btn-warning btnAlterarTipoDoc" data-bs-toggle="modal" data-bs-target="#AlteraTipoDoc" data-id="' + e.id + '" data-desc="' + e.desctipo + '">Editar</button></td><td><form method="post" id="excluir' + e.id + '" action=""><input type="hidden" id="idTipoDoc" name="idTipoDoc" value="' + e.id + '"><button class="btn btn-danger excluirTipoDoc" data-bs-toggle="modal" data-bs-target="#modexcluirTipoDoc" data-id="' + e.id + '" type="button">Excluir</button></form></td></tr>');
            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#formCadTipoDocumento #btnCadTipoDoc').on('click', function (e) {

    if (($('#formCadTipoDocumento #desctipo').val() == "")) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    var formdata = new FormData($("form[id='formCadTipoDocumento']")[0]);

    $.ajax({
        type: 'POST',
        url: "/cadastrarTipoDocumento",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            carregarTipoDocumento();
            $('#formCadTipoDocumento #desctipo').val("");
            toastr.success('Tipo de documento cadastrado com sucesso');
        },
        error: function (d) {
            if (d["status"] == 409)
                toastr.error('Já existe um Tipo de documento com esse nome cadastrado');

            if (d["status"] == 500)
                toastr.error('Houve um problema para cadastrar o Tipo de documento');

        }
    });
});

$(document).on('click', '.excluirTipoDoc', function (e) {
    $('#modexcluirTipoDoc').modal()
    $('#formExcluirTipoDoc #id').val($(this).data("id"));
});

$(document).on('click', '.btnConfirmaExcluirTipoDoc', function (e) {
    var formdata = new FormData($("form[id='formExcluirTipoDoc']")[0]);

    $.ajax({
        type: 'POST',
        url: "/excluirTipoDocumento",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            // console.log(d);
            carregarTipoDocumento();
            $(this).data("id", "");
            toastr.success('Tipo documento excluído com sucesso');
            FecharModal('#modexcluirTipoDoc');
        },
        error: function (d) {
            toastr.error('Houve um problema para excluir o tipo de documento. Verifique se há documentos cadastrados com esse tipo.');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirTipoDoc', function (e) {
    FecharModal('#modexcluirTipoDoc');
});

$(document).on('click', '.btnAlterarTipoDoc', function (e) {
    $("#AlteraTipoDoc").modal()
    $('#formAltTipoDoc #id').val($(this).data("id"));
    $('#formAltTipoDoc #descTipoDoc').val($(this).data("desc"));
    $('.opcoesConfirmacao').css('display', 'none');
});

$(document).on('click', '#exibConfirmaAlteracaoDocumento', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoTipoDocumento', function (e) {

    /*if (($('#formAltTipoDoc #desctipo').val() == "")) {
        alertas("Todos os campos do formulário são obrigatórios", '#AlteraTipoDoc', 'alert_danger');
        return false;
    }*/
    var formdata = new FormData($("form[id='formAltTipoDoc']")[0]);

    $.ajax({
        type: 'POST',
        url: "/alterarTipoDoc",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarTipoDocumento();
            $(this).data("id", "");
            toastr.success('Tipo documento atualizado com sucesso');
            FecharModal('#AlteraTipoDoc');
        },
        error: function (d) {
            toastr.error('Houve um problema para atualizar o tipo de documento.');
        }
    }
    );
});

$(document).on('change', '#ListArmarioDocumento', function (e) {
    idArmario = $(this).val();

    $.ajax({
        type: 'GET',
        url: "/listarTipoDocumentosArmario?id=" + idArmario,
        data: "",
        processData: false,
        contentType: false,
        success: function (d) {
            var sel = $("#SelectTipoDoc");
            sel.empty();
            d.forEach(e => {
                sel.append('<option value="' + e.id + '">' + e.desctipo + '</option>');
            });
        },
        error: function (d) {

        }
    });
});

$(document).on('click', '#btnNaoConfirmaAlteracaoTipoDocumento', function (e) {
    FecharModal('#AlteraTipoDoc');
});

$(document).on('click', '.abrirDocumento', function (e) {

    window.open("/visualizarDocumento?docid=" + $(this).data("id") + "&pagina=" + $(this).data("pagina"), "janela1", "width=800, height=600, directories=no, location=no, menubar=no,scrollbars=no, status=no, toolbar=no, resizable=no")
});

$(document).on('click', '.abrirDocumentoLote', function (e) {
    var nomeForm = "docid_" + $(this).data("id");
    var formdata = new FormData($("form[id='" + nomeForm + "']")[0]);
    window.open("/visualizarDocumentoLote?docid=" + $(this).data("id") + "&cf=" + $(this).data("cf"), "janela1", "width=800, height=600, directories=no, location=no, menubar=no,scrollbars=no, status=no, toolbar=no, resizable=no")
});

$(document).on('click', '.criptofrarDocumento', function (e) {
    var nomeForm = "docidCript_" + $(this).data("id");
    var formdata = new FormData($("form[id='" + nomeForm + "']")[0]);
    console.log(nomeForm);
    $.ajax({
        url: "/criptografarArquivo",
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) { },
        error: function (d) {
            console.log("Ocorreu um erro: " + d);
        }
    });
});

$(document).on('click', '.btnAlterarDocumento', function (e) {
    $('#formAltDocumento #docId').val($(this).data("docid"));
    $('#formAltDocumento #id').val($(this).data("id"));
    $('#formAltDocumento #nip').val($(this).data("nip"));
    $('#formAltDocumento #semestre').val($(this).data("sm"));
    $('#formAltDocumento #ano').val($(this).data("ano"));
    $('#formAltDocumento #tipodocumento').val($(this).data("td"));
    $('#formAltDocumento #folderid').val($(this).data("fi"));
    $('#formAltDocumento #armario').val($(this).data("ar"));
});

function carregarPerfis() {
    $.ajax({
        url: vUrlListarTipoPerfis,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            //console.log(data);
            var sel = $("#gradeListaPerfil");
            sel.empty();
            data.forEach(e => {
                sel.append('<tr><td>' + e.nomeperfil + '</td><td><button class="btn btn-warning btnAlterarPerfil" data-bs-toggle="modal" data-bs-target="#AlteraPerfil" data-id="' + e.id + '" data-desc="' + e.nomeperfil + '">Editar</button></td><td><form method="post" id="excluir' + e.id + '" action=""><input type="hidden" id="idPerfil" name="idPerfil" value="' + e.nomeperfil + '"><button class="btn btn-danger excluirPerfil" data-bs-toggle="modal" data-bs-target="#modexcluirPerfil" data-id="' + e.id + '" type="button">Excluir</button></form></td></tr>');

            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#formCadPerfil #btnCadPerfil').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadPerfil']")[0]);

    if (($('#formCadPerfil #nomePerfil').val() == "")) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    $.ajax({
        type: 'POST',
        url: "/cadastrarPerfil",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            console.log(d);
            carregarPerfis();
            $('#formCadPerfil #nomePerfil').val("");
            $('#formCadPerfil #armarios').val("");
            $('#formCadPerfil #nivelAcesso').val("1");
            toastr.success('Perfil cadastrado com sucesso');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    });
});

$(document).on('click', '.btnAlterarPerfil', function (e) {
    $('#AlteraPerfil').modal()
    $('#formAltPerfil #id').val($(this).data("id"));
    $('#formAltPerfil #nomeperfil').val($(this).data("desc"));
    $('#formAltPerfil #nomeperfilOriginal').val($(this).data("desc"));

    dados = JSON.stringify({
        codperfil: $(this).data("id")
    }, null, 2);

    $.ajax({
        type: 'POST',
        url: "/buscar-perfil-id",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            data.forEach(e => {
                $('#formAltPerfil #nipAlt').mask('00.0000.00');
                $('#formAltPerfil #nivelAcessoAlt').val(e.nivelacesso);
                $('#formAltPerfil #armariosAlt').val(e.armarios);
            });
        },
        error: function (d) {
            console.log("Erro:" + d.responseText);
            nipValido = false;
        }
    });
    $('.opcoesConfirmacao').css('display', 'none');
});

$(document).on('click', '#btnBuscarLog', function (e) {

    dados = JSON.stringify({
        operacao: $('#formBuscaLog #operacao').val(),
        nip: $('#formBuscaLog #nip').val(),
        data: $('#formBuscaLog #data').val(),
        ip: $('#formBuscaLog #ip').val()
    }, null, 2);

    $.ajax({
        type: 'POST',
        url: "/buscarLog",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);

        },
        error: function (d) {
            nipValido = false;
        }
    });
});

$(document).on('click', '#exibConfirmaAlteracaoPerfil', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoPerfil', function (e) {
    var formdata = new FormData($("form[id='formAltPerfil']")[0]);

    if (($('#formAltPerfil #nomeperfil').val() == "")) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    $.ajax({
        type: 'POST',
        url: "/alterarPerfil",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            //console.log(d);
            carregarPerfis();
            $(this).data("nomeperfil", "");
            toastr.success('Perfil atualizado com sucesso');
            FecharModal('#AlteraPerfil');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaAlteracaoPerfil', function (e) {
    FecharModal('#AlteraPerfil');
});

$(document).on('click', '.excluirPerfil', function (e) {
    $('#modexcluirPerfil').modal()
    $('#formExcluirPerfil #id').val($(this).data("id"));
});

$(document).on('click', '.btnConfirmaExcluirPerfil', function (e) {
    var formdata = new FormData($("form[id='formExcluirPerfil']")[0]);

    $.ajax({
        type: 'POST',
        url: "/excluirPerfil",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            console.log(d);
            carregarPerfis();
            $(this).data("id", "");
            toastr.success('Perfil excluído com sucesso');
            FecharModal('#modexcluirPerfil');
        },
        error: function (d) {
            toastr.error('Não foi possível excluir o perfil. Verifique se existem usuários ativos.');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirPerfil', function (e) {
    FecharModal('#modexcluirPerfil');
});

function carregarUsuarios() {
    $.ajax({
        url: vUrlListarUsuarios,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            //console.log(data);
            var sel = $("#gradeUsuario");
            sel.empty();
            data.forEach(e => {

                if(e.ativo == false){
                    var status = `<span class="text-danger">Inativo</span>`
                    var botaoAtivarDesativar = `<form method="post" id="ativar${e.codusuario}" name="formAltUsuario" id="formAltUsuario" action="">
                                            <input type="hidden" name="idUsuario" value="${e.codusuario}">
                                            <button class="btn btn-success ativarUsuario col-md-12" data-bs-toggle="modal" data-bs-target="#modativarUsuario" data-id="${e.codusuario}" type="button">Ativar</button>
                                        </form>`
                }else{
                    var status = `<span class="text-success">Ativo</span>`
                    var botaoAtivarDesativar = `<form method="post" id="excluir${e.codusuario}" name="formAltUsuario" id="formAltUsuario" action="">
                                            <input type="hidden" name="idUsuario" value="${e.codusuario}">
                                            <button class="btn btn-danger excluirUsuario col-md-12" data-bs-toggle="modal" data-bs-target="#modexcluirUsuario" data-id="${e.codusuario}" type="button">Inativar</button>
                                        </form>`
                }

                sel.append(`<tr>
                                <td>${e.nomeusuario}</td>
                                <td>${status}</td>
                                <td><button class="btn btn-warning btnAlterarUsuario" data-bs-toggle="modal" data-bs-target="#AlteraUsuario" data-id="${e.codusuario}" data-desc="${e.nomeusuario}">Editar</button></td>
                                <td><button class="btn btn-warning btnAlterarSenhaUsuario" data-bs-toggle="modal" data-bs-target="#AlteraSenhaUsuario" data-id="${e.codusuario}">Alterar Senha</button></td>
                                <td><button class="btn btn-warning btnIncluirSenhaPadrao" data-bs-toggle="modal" data-bs-target="#IncluirSenhaPadrao" data-id="${e.codusuario}">Senha Padrão</button></td>
                                <td>
                                ${botaoAtivarDesativar}
                                </td>
                            </tr>`);
            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#formCadUsuario #btnCadUsuario').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadUsuario']")[0]);

    if (($('#formCadUsuario #nomeusuario').val() == "") || ($('#formCadUsuario #nip').val() == "") || ($('#formCadUsuario #senhausuario').val() == "") || ($('#formCadUsuario #idacesso').val() == 0)) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    if (($('#formCadUsuario #nip').val().replace(/[^\d]+/g, '').length != 8)) {
        toastr.error('Campo NIP inválido');
        return false;
    }

    if (!validarSenha($('#formCadUsuario #senhausuario').val())) {
        toastr.error('A senha não atende ao requisitos mínimos');
        return false;
    }

    /*validarNip($('#formCadUsuario #nip').val());

    if (nipValido === false) {
        alertas("Nip inválido", '#modCadUsuario', 'alert_danger');
        return false;
    }*/

    $.ajax({
        type: 'POST',
        url: "/cadastrarUsuario",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            //console.log(d);
            carregarUsuarios();
            $('#formCadUsuario #nomeusuario').val("");
            $('#formCadUsuario #nip').val("");
            $('#formCadUsuario #senhausuario').val("");
            $('#formCadUsuario #idacesso').val("");
            $('#formCadUsuario #om').val("");
            $('#formCadUsuario #setor').val("");
            toastr.success('Usuario cadastrado com sucesso');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    });
});


$(document).on('click', '.btnAlterarSenhaUsuario', function (e) {
    $('#AlteraSenhaUsuario').modal()
    dados = JSON.stringify({
        codusuario: $(this).data("id")
    }, null, 2);

    $.ajax({
        type: 'POST',
        url: "/buscar-usuario-id",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            // console.log(data);
            data.forEach(e => {
                $('#formAltSenha #nipAltSenha').mask('00.0000.00');
                $('#formAltSenha #nipAltSenha').val(e.nip).trigger('input');
            });
        },
        error: function (d) {
            nipValido = false;
        }
    });

    $('.opcoesConfirmacao').css('display', 'none');
});

$(document).on('click', '.btnAlterarUsuario', function (e) {
    $('#AlteraUsuario').modal()
    $('#formAltUsuario #idAlt').val($(this).data("id"));
    $('#formAltUsuario #nomeusuarioAlt').val($(this).data("desc"));


    dados = JSON.stringify({
        codusuario: $('#formAltUsuario #idAlt').val()
    }, null, 2);

    $.ajax({
        type: 'POST',
        url: "/buscar-usuario-id",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            // console.log(data);
            data.forEach(e => {

                $('#formAltUsuario #nipAlt').mask('00.0000.00');
                $('#formAltUsuario #nipAlt').val(e.nip).trigger('input');
                $('#formAltUsuario #nipOriginal').mask('00.0000.00');
                $('#formAltUsuario #nipOriginal').val(e.nip).trigger('input');
                $('#formAltUsuario #setorAlt').val(e.setorusuario);
                $('#formAltUsuario #idacessoAlt').val(e.idacesso);
                $('#formAltUsuario #omAlt').val(e.omusuario);
            });
        },
        error: function (d) {
            nipValido = false;
        }
    });

    $('.opcoesConfirmacao').css('display', 'none');
});

function validarNip(nip) {
    $.ajax({
        type: 'GET',
        url: "/validar-nip?nip=" + nip,
        data: '',
        processData: false,
        contentType: false,
        success: function (data) {
            nipValido = data;
        },
        error: function (d) {
            nipValido = false;
        }
    });
}

$(document).on('click', '#exibConfirmaAlteracaoUsuario', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoUsuario', function (e) {
    var formdata = new FormData($("form[id='formAltUsuario']")[0]);

    if (($('#formAltUsuario #nomeusuarioAlt').val() == "") || ($('#formAltUsuario #nipAlt').val() == "") || ($('#formAltUsuario #omAlt').val() == "") || ($('#formAltUsuario #idacessoAlt').val() == 0) || ($('#formAltUsuario #setorAlt').val() == "")) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false;
    }

    if (($('#formAltUsuario #nipAlt').val().replace(/[^\d]+/g, '').length != 8)) {
        alertas("Campo NIP inválido", '#AlteraUsuario', 'alert_danger');
        return false;
    }

    dados = JSON.stringify({
        codusuario: $('#formAltUsuario #idAlt').val(),
        nomeusuario: $('#formAltUsuario #nomeusuarioAlt').val(),
        nipusuariooriginal: $('#formAltUsuario #nipOriginal').val(),
        nipusuario: $('#formAltUsuario #nipAlt').val(),
        omusuario: $('#formAltUsuario #omAlt').val(),
        acessousuario: $('#formAltUsuario #idacessoAlt').val(),
        setorusuario: $('#formAltUsuario #setorAlt').val(),
    }, null, 2)

    $.ajax({
        type: 'POST',
        url: "/alterarUsuario",
        data: dados,
        processData: false,
        contentType: false,
        success: function (d) {
            //console.log(d);
            carregarUsuarios();
            $(this).data("nomeusuario", "");
            toastr.success('Dados do usuario atualizados com sucesso');
            FecharModal('#AlteraUsuario');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    }
    );
});

$(document).on('click', '.btnIncluirSenhaPadrao', function (e) {
    $('#IncluirSenhaPadrao').modal()
    $('#formAltSenhaPadrao #idSenhaPadrao').val($(this).data("id"));
});


$(document).on('click', '#btnNaoConfirmaAlteracaoUsuario', function (e) {
    FecharModal('#AlteraUsuario');
});

$(document).on('click', '.excluirUsuario', function (e) {
    $('#modexcluirUsuario').modal()
    $('#formExcluirUsuario #id').val($(this).data("id"));
});

// $(document).on('click', '.excluirUsuario', function (e) {
//     $('#modexcluirUsuario').modal()
//     $('#formExcluirUsuario #id').val($(this).data("id"));
// });

$(document).on('click', '.btnConfirmaExcluirUsuario', function (e) {
    var formdata = new FormData($("form[id='formExcluirUsuario']")[0]);

    $.ajax({
        type: 'POST',
        url: "/excluirUsuario",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarUsuarios();
            $(this).data("id", "");
            toastr.success('Usuario excluído com sucesso');
            FecharModal('#modexcluirUsuario');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    }
    );
});

$(document).on('click', '.ativarUsuario', function (e) {
    $('#modativarUsuario').modal()
    $('#formAtivarUsuario #id').val($(this).data("id"));
});

$(document).on('click', '.btnConfirmaAtivarUsuario', function (e) {
    var formdata = new FormData($("form[id='formAtivarUsuario']")[0]);
    $.ajax({
        type: 'POST',
        url: "/ativarUsuario",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarUsuarios();
            $(this).data("id", "");
            toastr.success('Usuario ativado com sucesso');
            FecharModal('#modativarUsuario');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    }
    );
});

$(document).on('click', '.btnConfirmaResetSenhaUsuario', function (e) {
    var formdata = new FormData($("form[id='formAltSenhaPadrao']")[0]);
    $.ajax({
        type: 'POST',
        url: "/ResetSenhaUsuario",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarUsuarios();
            $(this).data("idSenhaPadrao", "");
            toastr.success('Senha Padrão incluída com sucesso. Usuário deverá alterar a senha no próximo login');
            FecharModal('#IncluirSenhaPadrao');
        },
        error: function (d) {
            toastr.error(d.responseText,);
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirUsuario', function (e) {
    FecharModal('#modexcluirUsuario');
});

$('#formLogin #btnLogin').on('click', function (e) {
    var formdata = new FormData($("form[id='formLogin']")[0]);
    let nip = $('#nip').val()

    if (!nip) {
        toastr.warning('Informe seu Nip');
        return false
    }
    let senha = $('#senha').val()
    if (!senha) {
        toastr.warning('Informe sua Senha');
        return false
    }
    $.ajax({
        type: 'POST',
        url: "/login",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (data) {
            //console.log(JSON.parse(data))
            //console.log(JSON.parse(data)[0]['dataultimologin']);
            //console.log(JSON.parse(data));
            //JSON.parse(data)[0]['idperfil']
            if(data == 0){
                toastr.error('NIP não encontrado');
                return false
            }else if(data == 1){
                toastr.error('Falha ao efeturar login, 1 de 3 tentativas');
                return false
            }else if(data == 2){
                toastr.error('Falha ao efeturar login, 2 de 3 tentativas');
                return false
            }else if(data > 2){
                toastr.error('Usuário bloqueado');
                return false
            }/*else if(data > 3){
                toastr.error('Falha ao efeturar login, usuário bloqueado, favor informar ao administrador do sistema');
                return false
            }*/

            if (data != "null") {
                console.log(JSON.parse(data));
                if ((JSON.parse(data)[0]['dataultimologin'] == null)) {
                    //console.log("/trocasenha");
                    location.assign("/troca-senha");
                } else {
                    //console.log("/home");
                    location.assign("/home");
                }

            } else {

                $('#formLogin #senha').val("");
                $('#formLogin #nip').val("");
                toastr.error('Falha ao efeturar login');
            }
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modLogin', 'alert_danger');
        }
    });
});

$('#formAltSenha #alterarSenha').on('click', function (e) {
    var formdata = new FormData($("form[id='formAltSenha']")[0]);
    $.ajax({
        type: 'POST',
        url: "/alterarSenha",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data != "true") {
                toastr.error('Não foi possível alterar a senha');
            } else {
                FecharModal('#AlteraSenhaUsuario');
                toastr.success('Senha alterada com sucesso');
                setTimeout(function () {
                    location.assign("/gerenciar-usuarios");
                }, 3000);
            }
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    });
});

function abreArquivo(data) {
    $.ajax({
        url: "/visualizarDocumento",
        type: 'GET',
        data: data,
        processData: false,
        contentType: false,
        success: function (d) {
            console.log(d);
            window.open(data, "janela1", "width=800, height=600, directories=no, location=no, menubar=no,scrollbars=no, status=no, toolbar=no, resizable=no")
        },
        error: function (d) {
            console.log("Ocorreu um erro: " + d);
        }
    });
}

function FecharModal(local) {
    $(local).trigger('click');
}

$('#btnNaoConfirmaResetSenhaUsuario').on('click', function (e) {
    $('#tipo-doc').val('')
    $('.modal').trigger('click');
});
function fechar() {
    alert('oi');
}
$('.btn-close').on('click', function (e) {
    $('.modal').trigger('click');
});

$('.fechar').on('click', function (e) {

    $('.modal').trigger('click');
});

function alertas(msg, local, estilo, fecharAlerta) {
    $(local + ' .alerta').addClass(estilo);
    $(local + ' .alerta').html(msg);
    $(local + ' .alerta').show();
    setTimeout(function () {
        $(local + ' .alerta').hide();
        if (fecharAlerta == 'true')
            $(local + ' .btn-close').trigger('click');
        $(local + ' .alerta').removeClass(estilo);
    }, 2500);
}

$('#formCadDocumento').on('change paste keyup', 'input, select', function () {

    if ($('#formCadDocumento #Nip').val() != "") {

        //console.log($('#formCadDocumento #Nip').val());
        var formdata = new FormData($("form[id='formCadDocumento']")[0]);

        $.ajax({
            type: 'POST',
            url: "/BuscarDocumentos",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (data) {
                console.log(data);
                const arrayData = JSON.parse(data);
                exibeLinhasRegistros(arrayData.length)
                var sel = $("#documentosLista");
                sel.empty();
                arrayData.forEach(e => {
                    sel.append('<tr class="clickDocumento" id="' + e.id + '" ><td>1</td><td>' + e.nip + '</td><td>' + e.semestre + '</td><td>' + e.ano + '</td><td>' + e.desctipo + '</td><td>' + e.quantidadepaginas + '</td></tr>');
                    //'<div class="container_item_maior" id="gradeDocumentos"><div class=Descricao_maior>' + e.nip + '</div><div class=Descricao_maior>' + e.semestre + '</div><div class=Descricao_maior>' + e.ano + '</div><div class=Descricao_maior>' + e.desctipo + '</div><div class=Descricao_maior>' + e.nomeArmario + '</div><div class=Descricao_maior><form method="post" id="" name="" action="/tratar-documento"><input type="hidden" id="idDocumento" name="idDocumento" value="' + e.id + '"><input type="submit" id="btnAbrirDocumento" name="btnAbrirDocumento" class="btn btn-primary btnAbrirDocumento" value="Indexar Documento"></form></div></div>'
                });
            },
            error: function (d) {

            }
        });
    }
});

function exibeLinhasRegistros(quantidade) {
    //console.log("retorno: " + quantidade);
    if (quantidade >= 1) {
        $('.btnIndexar').css("display", "none");
        $('.btnAnexar').css("display", "block");
    } else {
        $('.btnIndexar').css("display", "block");
        $('.btnAnexar').css("display", "none");
    }
}

$(document).on('click', '#Metatags', function (e) {
    $("#containerTags").slideToggle('slow')
});

$(document).on('click', '.clickDocumento', function (e) {
    $('#regride').show()
    $('#documento-total').show()
    //console.log($(this).attr("id"));
    //var docId = $(this).attr("id");
    // var formdata = new FormData($("form[id='SelectLote']")[0]);
    //var id = $(this).val();
    //var
    $.ajax({
        type: 'GET',
        url: "/listarPaginas?idDocumento=" + $(this).attr("id"),
        data: '',
        processData: false,
        contentType: false,
        success: function (data) {
            const arrayData = JSON.parse(data);
            var sel = $("#listarDocumentos");

            listDocumentosServidor = [];
            arrayData.forEach(e => {
                listDocumentosServidor.push([e.id, e.arquivo]);
            });
            //console.log(listDocumentosServidor[1][1]);
            sel.empty();
            $('.carousel-control-prev').attr('id', 'regride');
            $('.carousel-control-next').attr('id', 'avanca');
            $('#avanca').attr('data-indice', 1);
            //$('#regride').attr('data-indice', 0);
            $('#regride').attr('data-indice', 1);
            $('#qtd-paginas').html(listDocumentosServidor.length)
            $('#documento-total').html(`Total de páginas(s): 1/${listDocumentosServidor.length}`)
            let url = listDocumentosServidor[0][1];
            $('#download-doc').html(`<a href="${url}" target="_blank">Baixar arquivo - 1</a>`)
            sel.attr('data-docId', listDocumentosServidor[0][0]);
            sel.append('<iframe src="/' + listDocumentosServidor[0][1] + '" width="100%" height="500"></iframe>');

            $("#carouselExampleControls").css('display', 'block');

            let path = window.location.pathname;
            if (!path.includes('/visualizar-documentos')) {
                $("#incluirDocumento").css('display', 'inline-block');
                $("#excluirDocumentoMalIndexado").css('display', 'inline-block');
            }
            $('#incluirDocumento').hide()
        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
});

$(document).on('click', '#modCadDocumento #avanca', function () {
    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) + 1 <= listDocumentosServidor.length)) {
        var sel = $("#listarDocumentos");
        let index = $(this).attr('data-indice');
        index++
        $('#qtd-paginas').html(listDocumentosServidor.length)
        $('#documento-total').html(`Total de páginas(s): ${index}/${listDocumentosServidor.length}`)
        let url = listDocumentosServidor[$(this).attr('data-indice')][1];
        $('#download-doc').html(`<a href="${url}" target="_blank">Baixar arquivo - ${index}</a>`)
        sel.empty();
        sel.append('<iframe src="/' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);

        //let indice = (parseInt($(this).attr('data-indice')) + 1) > (listDocumentosServidor.length - 1) ? listDocumentosServidor.length - 1 : parseInt($(this).attr('data-indice')) + 1;
        // $(this).attr('data-indice', indice);

        $(this).attr('data-indice', index);

        $('#modCadDocumento #regride').attr('data-indice', parseInt($(this).attr('data-indice')) - 1);

    }
});

$(document).on('click', '#avancaPdf', function () {
    console.log("yeye");
    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) + 1 <= listDocumentosServidor.length)) {
        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        sel.append('<iframe src="..' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);

        let indice = (parseInt($(this).attr('data-indice')) + 1) > (listDocumentosServidor.length - 1) ? listDocumentosServidor.length - 1 : parseInt($(this).attr('data-indice')) + 1;

        $(this).attr('data-indice', indice);


        $('#regridePdf').attr('data-indice', parseInt($(this).attr('data-indice')) - 1);

    }
});

$(document).on('click', '#modCadDocumento #regride', function () {

    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) >= 0)) {

        var sel = $("#listarDocumentos");
        let index = $(this).attr('data-indice');
        index++
        $('#qtd-paginas').html(listDocumentosServidor.length)
        $('#documento-total').html(`Total de páginas(s): ${index}/${listDocumentosServidor.length}`)
        let url = listDocumentosServidor[$(this).attr('data-indice')][1];
        $('#download-doc').html(`<a href="${url}" target="_blank">Baixar arquivo - ${index}</a>`)
        sel.empty();
        sel.append('<iframe src="/' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);
        let indice = (parseInt($(this).attr('data-indice')) - 1) < 0 ? 0 : parseInt($(this).attr('data-indice')) - 1;

        $(this).attr('data-indice', indice);
        $('#modCadDocumento #avanca').attr('data-indice', parseInt($(this).attr('data-indice')) + 1);
    }
});

$(document).on('click', '#regridePdf', function () {

    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) >= 0)) {

        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        sel.append('<iframe src="..' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);
        let indice = (parseInt($(this).attr('data-indice')) - 1) < 0 ? 0 : parseInt($(this).attr('data-indice')) - 1;

        $(this).attr('data-indice', indice);
        $('#avancaPdf').attr('data-indice', parseInt($(this).attr('data-indice')) + 1);
    }
});

function verificaDadosReindexarPagina() {
    let hash = $('#formCadDocumento #Hash').val()
    $.ajax({
        url: "./verifica-se-hash-existe?hash=" + hash,
        type: 'GET',
        data: "",
        processData: false,
        contentType: false,
        success: function (data) {
            if(data > 0){
                toastr.error('Esse Hash já existe em outra página');
                $('#AnexarDocumento').prop('disabled', true)
                $('#IndexarDocumento').prop('disabled', true)
            }else{
                $('#AnexarDocumento').prop('disabled', false)
                $('#IndexarDocumento').prop('disabled', false)
            }
        }
    });
}

$(document).on('click', '#btnConfirmaReIndexarDocumento', function () {
    listDocumentosServidor = [];

    if (($('#formCadDocumento #ListArmarioDocumento').val() == 0)) {
        toastr.error('Selecione um armário');
        return false;
    }

    //console.log("reindexar:" + $('#formCadDocumento #Nip').val().replace(/\./g, ''));
    if (($('#formCadDocumento #Nip').val() != "")) {

        var nip = $('#formCadDocumento #Nip').val().replace(/\./g, '');
        //var nip = $('#formCadDocumento #Nip').val().replace(/\.\.\//g, "");

        if (nip.length != 8) {
            toastr.error('Informe um nip válido');
            //alertas("Informe um nip válido", '#ModReIndexarDocumento', 'alert_danger');
            return false;
        }
    } else {
        toastr.error('Informe um nip válido');
        //alertas("Informe um nip válido", '#ModReIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #semestre').val() == 0)) {
        toastr.error('Informe o semestre');
        //alertas("Informe o semestre", '#ModReIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #ano').val() == 0) || ($('#formCadDocumento #ano').val().length != 4) || ($('#formCadDocumento #ano').val() > new Date().getFullYear())) {
        toastr.error('Informe um ano válido');
        //alertas("Informe um ano válido", '#ModReIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #SelectTipoDoc').val() == 0)) {
        toastr.error('Informe o tipo de documento');
        //alertas("Informe o tipo de documento", '#ModReIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #ConfAssinatura').is(':checked') == true) && (($('#formCadDocumento #Assunto').val() == "")
        || ($('#formCadDocumento #Autor').val() == "")
        || ($('#formCadDocumento #Titulo').val() == "")
        || ($('#formCadDocumento #Identificador').val() == "")
        || ($('#formCadDocumento #Classe').val() == "")
        || ($('#formCadDocumento #DataProdDoc').val() == "")
        || ($('#formCadDocumento #DestinacaoDoc').val() == 0)
        || ($('#formCadDocumento #Genero').val() == "")
        || ($('#formCadDocumento #PrazoGuarda').val() == "")
        || ($('#formCadDocumento #Observacao').val() == ""))) {
        

        if (($('#formCadDocumento #Assunto').val() == "")) {
            toastr.error('Informe o assunto');
            return false;
        }
    
        if (($('#formCadDocumento #codOM').val() == "")) {
            toastr.error('Informe o autor');
            return false;
        }
    
        if (($('#formCadDocumento #Titulo').val() == "")) {
            toastr.error('Informe o título');
            return false;
        }
    
        if (($('#formCadDocumento #Classe').val() == "")) {
            toastr.error('Informe a Classe');
            return false;
        }
    
        if (($('#formCadDocumento #DataProdDoc').val() == "")) {
            toastr.error('Informe a data de produção');
            return false;
        }
    
        if (($('#formCadDocumento #DestinacaoDoc').val() == 0)) {
            toastr.error('Informe destinação prevista');
            return false;
        }
    
        if (($('#formCadDocumento #Genero').val() == "")) {
            toastr.error('Informe o gênero');
            return false;
        }
    
        if (($('#formCadDocumento #PrazoGuarda').val() == "")) {
            toastr.error('Informe o prazo de guarda');
            return false;
        }
    
        if (($('#formCadDocumento #Observacao').val() == "")) {
            toastr.error('Informe a observação');
            return false;
        }

       // verificaDadosReindexarPagina()

        toastr.error('Existem tags não preenchidas');
        return false;
    }

    if ($('#formCadDocumento #ConfAssinatura').is(':checked') === false) {
        if (($('#formCadDocumento #Hash').val() == "") || ($('#formCadDocumento #Hash').val() == 0)) {
            toastr.error('Informe o hash');
            return false;
        }
    }

    if ($("#listarDocumentos").attr("data-docid") == "") {
        //console.log("Nenhum documento sendo exibido");
        toastr.error('Nenhum documento sendo exibido');
        //alertas("Nenhum documento sendo exibido", '#modCadDocumento', 'alert_danger');
        return false;
    }

    dados = JSON.stringify({
        idArmario: $('#formCadDocumento #ListArmarioDocumento').val(),
        nomeArmario: "",
        nip: $('#formCadDocumento #Nip').unmask().val(),
        semestre: $('#formCadDocumento #semestre').val(),
        ano: $('#formCadDocumento #ano').val(),
        tipoDoc: $('#formCadDocumento #SelectTipoDoc').val(),
        caminho: $('#formCadDocumento #Caminho').val(),
        idPagina: $("#listarDocumentos").attr("data-docid"),
        arquivo: $("#listarDocumentos iframe").attr("src").replace(/\.\.\//g, ""),
        //arquivo: $("#listarDocumentos iframe").attr("src").replace(/\./g, ''),
        ip: 0,
        codusuario: 0,
        omUsuario: "",
        idacesso: 0
    }, null, 2);

    $(this).attr('disabled', 'disabled');
    $.ajax({
        type: 'POST',
        url: "/ReIndexarPagina",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            $('#semestre').trigger('change');
            $('#formCadDocumento #Nip').mask('00.0000.00');
            $('#btnConfirmaReIndexarDocumento').removeAttr('disabled');
            FecharModal('#ModReIndexarDocumento');
            toastr.success('Página Reindexada com sucesso');
            //alertas('Página Reindexada com sucesso', '#ModReIndexarDocumento', 'alert_sucess', 'true');
            /*setTimeout(function () {
                location.reload();
            }, 3000);*/
        },
        error: function (d) {
            //console.log(d);
            alertas(d.responseText, '#ModReIndexarDocumento', 'alert_danger', 'true');
            $('#formCadDocumento #Nip').mask('00.0000.00');
            //console.log('erro ao excluir a página ' + d);
        }
    });
});

$(document).on('click', '#btnNaoConfirmaReIndexarDocumento', function (e) {
    $('#tipo-doc').val('')
    FecharModal('#ModReIndexarDocumento');
});

$(document).on('click', '#excluirDocumentoMalIndexado', function (e) {
    $('#ModExcluirPagina').modal();
});

$(document).on('click', '#btnConfirmaExcluirPagina', function () {
    dados = JSON.stringify({
        idPagina: $("#listarDocumentos").attr("data-docid"),
        docid: $('#listPaginas #documentosLista .clickDocumento').attr("id"),
        arquivo: $("#listarDocumentos iframe").attr("src").replace(/\.\.\//g, ""),
        ip: 0,
        codusuario: 0,
        omUsuario: "",
        idacesso: 0
    }, null, 2);

    $.ajax({
        type: 'POST',
        url: "/excluirDocumentoPagina",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            toastr.success('Página excluída com sucesso');
            FecharModal('#ModExcluirPagina');
            //alertas('Página excluida com sucesso', '#ModExcluirPagina', 'alert_sucess', 'true');
            $('#semestre').trigger('change');

            $('.clickDocumento').click();
            /*setTimeout(function () {
                location.reload();
            }, 3000);*/
        },
        error: function (d) {
            //console.log(d);
            alertas(d.responseText, '#ModExcluirPagina', 'alert_danger', 'true');
            //console.log('erro ao excluir a página ' + d);
        }
    });
});

$(document).on('click', '#btnNaoConfirmaExcluirPagina', function (e) {
    FecharModal('#ModExcluirPagina');
});

function carregarLotes() {
    $.ajax({
        url: "/listar-lotes",
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            // console.log(data);
            var sel = $("#gradeDocumentos");
            sel.empty();
            data.forEach(e => {
                sel.append('<div class="container_item"><div class="Descricao">' + e.nomeexterno + '</div><div class="acoes"><form id="formGerenciarArmario" name="formGerenciarArmario"><input type="hidden" name="idArmario" id="idArmario" value="' + e.id + '" /><input type="button" class="btn btn-primary btnGerenciarArmario" data-bs-toggle="modal" data-bs-target="#GerenciarArmario" data-id="' + e.id + '" value="Gerenciar"></form><button class="btn btn-warning btnAlterarArmario" data-bs-toggle="modal" data-bs-target="#AlteraArmario" data-id="' + e.id + '" data-ni="' + e.nomeinterno + '" data-ne="' + e.nomeexterno + '" data-cd="' + e.codigo + '">Editar</button><form method="post" id="excluir' + e.id + '" action=""><input type="hidden" id="idArmario" name="idArmario" value="' + e.id + '" ><button class="btn btn-danger excluir" data-id="' + e.id + '" data-bs-toggle="modal" data-bs-target="#ExcluirArmario" type="button">Excluir</button></form></div></div>');

            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}
////////////////////////OM/////////////////
$('#formCadOM #btnCadOM').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadOM']")[0]);
    let codOM = $('#codOM').val()
    if (!codOM) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false
    }
    let sigla = $('#sigla').val()
    if (!sigla) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false
    }
    let nomeOM = $('#nomeOM').val()
    if (!nomeOM) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false
    }
    $.ajax({
        type: 'POST',
        url: "/cadastrarOM",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            carregarOm()
            toastr.success('OM cadastrada com sucesso');
        },
        error: function (d) {
            toastr.error('Erro ao cadastrar OM');
        }
    });
});

$(document).on('click', '.btnAlterarOm', function (e) {
    $("#AlterarOm").modal()
    $('#formAlterarOm #codOMAlter').val($(this).data("codom"));
    $('#formAlterarOm #siglaAlter').val($(this).data("sigla"));
    $('#formAlterarOm #nomeOMAlter').val($(this).data("nomeom"));
    $('.opcoesConfirmacao').css('display', 'none');
});



$(document).on('click', '#exibConfirmaAlteracaoOm', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});


$(document).on('click', '#btnConfirmaAlteracaoOm', function (e) {
    let siglaAlter = $('#siglaAlter').val()
    if (!siglaAlter) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false
    }
    let nomeOMAlter = $('#nomeOMAlter').val()
    if (!nomeOMAlter) {
        toastr.warning('Todos os campos do formulário são obrigatórios');
        return false
    }
    var formdata = new FormData($("form[id='formAlterarOm']")[0]);
    $.ajax({
        type: 'POST',
        url: "/alterarOM",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarOm();
            toastr.success('OM atualizado com sucesso');
            FecharModal('#AlterarOm');
        },
        error: function (d) {
            toastr.error('Houve um problema para atualizar OM.' + d.responseText);
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaAlteracaoOm', function (e) {
    $('.modal').trigger('click');
});

function carregarOm() {
    $.ajax({
        url: vUrlListarOm,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            var sel = $("#gradeListaOM");
            sel.empty();
            data.forEach(e => {
                sel.append(`<tr>
                    <td>${e.CodOM}</td>
                    <td>${e.NomeAbreviado}</td>
                    <td>${e.NomOM}</td>
                    <td>
                        <button class="btn btn-warning btnAlterarOm" data-bs-toggle="modal" data-bs-target="#AlteraOm" data-codom="${e.CodOM}" data-sigla="${e.NomeAbreviado}" data-nomeom="${e.NomOM}">Editar</button></td>
                     <td>
                        <form method="post" id="formExcluirOm${e.CodOM}" action="">
                            <input type="hidden" id="CodOMExcluir" name="CodOMExcluir" value="${e.CodOM}"><button class="btn btn-danger excluirOm" data-bs-toggle="modal" data-bs-target="#modexcluirOm" data-codom="${e.CodOM}" type="button">Excluir</button>
                        </form>
                    </td>
                    </tr>`);
            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$(document).on('click', '.excluirOm', function (e) {
    $('#modexcluirOm').modal()
    $('#formExcluirOm #codOMExcluir').val($(this).data("codom"));
});
$(document).on('click', '#btnNaoConfirmaExcluirOm', function (e) {
    FecharModal('#modexcluirOm');
});
$(document).on('click', '.btnConfirmaExcluirOm', function (e) {
    var formdata = new FormData($("form[id='formExcluirOm']")[0]);

    $.ajax({
        type: 'POST',
        url: "/excluirOM",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            carregarOm()
            FecharModal('#modexcluirOm');
            toastr.success('OM excluído com sucesso');
        },
        error: function (d) {
            toastr.error(d.responseText);
        }
    }
    );
});

////////////////////Documento////////////////////////
$('#formCadDocumento #btnCarregarArquivosImg').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadDocumento']")[0]);
    listDocumentos = [];
    listDocumentosServidor = [];
    $.ajax({
        type: 'POST',
        url: "/carregarArquivos",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            $('#formCadDocumento #Caminho').val(data);
            ListarArquivos();
            $(".carousel-control-prev").removeAttr("id");
            $(".carousel-control-next").removeAttr("id");
        },
        error: function (d) {
            toastr.error(d.responseText);
            //alertas(d.responseText, '#modCadDocumento', 'alert_danger', 'true');
            console.log('erro ao carregar arquivos ' + d.responseText);
        }
    });
});

$('#incluirDocumento').on('click', function (e) {
    if (typeof $("#listarDocumentos .active img").attr("src") !== "undefined") {
        listDocumentos.push($("#listarDocumentos .active img").attr("src").replace(/\.\.\//g, ""));

        listDocumentosPrimaria.forEach(function (subArray) {
            var index = subArray.indexOf($("#listarDocumentos .active img").attr("src"));
            if (index > -1) {
                subArray.splice(index, 1);
            }
        });

        listDocumentosPrimaria = listDocumentosPrimaria.filter(function (subArray) {
            return subArray.length > 0;
        });

        ListarArquivos();

    } else {
        listDocumentos.push($("#listarDocumentos  iframe").attr("src").replace(/\.\.\//g, ""));

        listDocumentosPrimaria.forEach(function (subArray) {
            var index = subArray.indexOf($("#listarDocumentos  iframe").attr("src"));
            if (index > -1) {
                subArray.splice(index, 1);
            }
        });

        listDocumentosPrimaria = listDocumentosPrimaria.filter(function (subArray) {
            return subArray.length > 0;
        });

        ListarArquivos();
    }

    if (listDocumentos.length > 0) {
        $('#verificarDocumentos').css("display", "block");
    } else {
        $('#verificarDocumentos').css("display", "none");
    }

});

$('#verificarDocumentos').on('click', function (e) {
    ListarArquivosSelecionados()
});

$(document).on('click', '#removerDocumento', function (e) {
    var index = listDocumentos.indexOf($("#listarDocumentosSelecionados .active img").attr("src")) !== -1 ? listDocumentos.indexOf($("#listarDocumentosSelecionados .active img").attr("src")) : listDocumentos.indexOf($("#listarDocumentosSelecionados iframe").attr("src").replace("..", ""));

    if (index !== -1) {
        //listDocumentos.push($("#listarDocumentosSelecionados .active img").attr("src").replace(/\.\.\//g, ""));
        item = "../" + [$("#listarDocumentosSelecionados .active img").attr("src")];
        arrayItem = [item];
        listDocumentosPrimaria.push(arrayItem);

        listDocumentos.splice(index, 1);

        ListarArquivos();
        ListarArquivosSelecionados();

        if (listDocumentos.length < 1) {
            $('.modal').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').hide();
            $('#verificarDocumentos').css("display", "none");
            $('#verificarDocumentos').click();
        }
    }
});


$('#formAnexarPagDoc #btnCarregarArquivosPDF').on('click', function (e) {
    var formdata = new FormData($("form[id='formAnexarPagDoc']")[0]);
    $.ajax({
        type: 'POST',
        url: "/carregarArquivos",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
            $('#formAnexarPagDoc #Caminho').val(data);
            ListarArquivos();
        },
        error: function (d) {
            console.log('erro ao carregar arquivos ' + d);
        }
    });
});

function removeItems(nestedArray, listToRemove) {
    nestedArray.forEach(function (subArray) {
        listToRemove.forEach(function (item) {
            //console.log(item);
            item = item.startsWith("/") ? ".." + item : "../" + item;
            var index = subArray.indexOf(item);
            if (index !== -1) {
                subArray.splice(index, 1);
            }
        });
    });
    return nestedArray.filter(subArray => subArray.length > 0);
}

function ListarArquivos() {
    var contador = 0;
    var formdata = new FormData($("form[id='formCadDocumento']")[0]);
    var id = $('#formCadDocumento #Caminho').val();
    $.ajax({
        type: 'POST',
        url: "/ListarDocumentos",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            $('#documento-total').show()
            $('#download-doc').show()
            listDocumentosPrimaria = [];
            listDocumentosPrimaria = JSON.parse(data);

            if (listDocumentos.length > 0)
                listDocumentosPrimaria = removeItems(listDocumentosPrimaria, listDocumentos);


            var sel = $("#listarDocumentos");
            sel.empty();
            if (listDocumentosPrimaria.length > 0) {

                var extensao = listDocumentosPrimaria[0][0].split('.').pop();
                if (extensao.toLowerCase() == "pdf") {
                    listDocumentosServidor = [];
                    let contador = 0;
                    listDocumentosPrimaria.forEach(e => {
                        listDocumentosServidor.push([contador, e]);
                        contador++;
                    });
                    console.log(contador)

                    //console.log(listDocumentosServidor);
                    $('#modCadDocumento .carousel-control-prev').attr('id', 'regride');
                    $('#modCadDocumento .carousel-control-next').attr('id', 'avanca');
                    $('#modCadDocumento #avanca').attr('data-indice', 1);
                    //$('#modCadDocumento #regride').attr('data-indice', 0);
                    $('#modCadDocumento #regride').attr('data-indice', 1);
                    let index = $(this).attr('data-indice');
                    index++
                    if (isNaN(index)) {
                        $('#documento-total').hide()
                        $('#download-doc').hide()
                    } else {
                        $('#documento-total').show()
                        $('#download-doc').show()
                        $('#qtd-paginas').html(listDocumentosServidor.length)
                        $('#documento-total').html(`Total de páginas(s): ${index}/${listDocumentosServidor.length}`)
                        let url = listDocumentosServidor[0][1];
                        $('#download-doc').html(`<a href="${url}" target="_blank">Baixar arqui0vo - ${index}</a>`)
                    }

                    sel.attr('data-docId', listDocumentosServidor[0][0]);
                    sel.append('<iframe src="/' + listDocumentosServidor[0][1] + '" width="100%" height="500"></iframe>');


                } else {
                    listDocumentosPrimaria.forEach(e => {
                        //console.log(e[0].split('.').pop());
                        var active = (contador == 0) ? 'active' : '';
                        sel.append('<div class="carousel-item ' + active + '" style="width: 400px; height: 100Vh;margin-left: 200px; "><img src="' + e + '" class="d-block w-100" alt="Imagem 1"> </div>');
                        contador++;
                    });
                }

                $("#carouselExampleControls").css('display', 'block');
                $("#incluirDocumento").css('display', 'inline-block');
                $("#excluirDocumentoMalIndexado").css('display', 'inline-block');
                $("#carouselExampleControls").html();
                let index = $(this).attr('data-indice');
                index++
                if (isNaN(index)) {
                    $('#documento-total').hide()
                    $('#download-doc').hide()
                } else {
                    $('#documento-total').show()
                    $('#download-doc').show()
                    $('#qtd-paginas').html(listDocumentosServidor.length)
                    $('#documento-total').html(`Total de páginas(s): ${index}/${listDocumentosServidor.length}`)
                    let url = listDocumentosServidor[0][1];
                    $('#download-doc').html(`<a href="${url}" target="_blank">Baixar arquivo - ${index}</a>`)
                }
            } else {
                $("#carouselExampleControls").css('display', 'none');
                $("#incluirDocumento").css('display', 'none');
                $("#carouselExampleControls").html();
            }
            $("#excluirDocumentoMalIndexado").hide()
        },
        error: function (d) {
            console.log('erro ' + d);
        }
    });
}


function ListarArquivosSelecionados() {
    $('#excluirDocumentoMalIndexado').hide()
    var extensao = listDocumentos.length > 0 ? listDocumentos[0].split('.').pop() : "";
    if (extensao.toLowerCase() == "pdf") {
        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        listDocumentosServidor = [];
        let contador = 0;
        listDocumentos.forEach(e => {
            listDocumentosServidor.push([contador, e]);
            contador++;
        });

        //console.log(listDocumentosServidor);
        $('#carouselExampleControlsSelecionados .carousel-control-prev').attr('id', 'regridePdf');
        $('#carouselExampleControlsSelecionados .carousel-control-next').attr('id', 'avancaPdf');
        $('#carouselExampleControlsSelecionados #avancaPdf').attr('data-indice', 1);
        $('#carouselExampleControlsSelecionados #regridePdf').attr('data-indice', 0);
        sel.attr('data-docId', listDocumentosServidor[0][0]);
        sel.append('<iframe src="..' + listDocumentosServidor[0][1] + '" width="100%" height="500"></iframe>');

    } else {
        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        let contador = 0;
        listDocumentos.forEach(e => {
            var active = (contador == 0) ? 'active' : '';
            sel.append('<div class="carousel-item ' + active + '" style="width: 100%; height: 100%;"><img src="' + e + '" class="d-block w-100" alt="Imagem 1"> </div>');
            contador++;
        });

    }
    $("#carouselExampleControlsSelecionados").html();
}

$(document).on('click', '#arquivosLote', function (e) {
    var sel = $("#visualizarDocumento");
    sel.empty();
    sel.append('<img src="' + $(this).data("arquivo") + '" width="100%" height="500"></img>');
});

function retornaCaminho(caminho) {
    $.ajax({
        url: "/retorna-caminhoTratado?caminho=" + caminho,
        type: 'GET',
        data: "",
        processData: false,
        contentType: false,
        success: function (data) {
            const arrayData = JSON.parse(data);
            return data;
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$(document).on('click', '#btnNaoConfirmaIndexarDocumento', function (e) {
    $('#btnConfirmaIndexarDocumento').prop('disabled', false)
    $('#tipo-doc').val('')
    FecharModal('#ModIndexarDocumento');
});


var dadosDocumento = "";
var docBase64 = "";
var docAtual = ""
var docBase64Atual = "";
let docid = 0;
let possuiPasta = 0;
$(document).on('click', '#btnConfirmaIndexarDocumento', function (e) {
    listDocumentosServidor = [];

    if (($('#formCadDocumento #ListArmarioDocumento').val() == 0)) {
        toastr.error('Selecione um armário');
        return false;
    }

    //console.log($('#formCadDocumento #Nip').val().replace(/\./g, ''));
    if (($('#formCadDocumento #Nip').val() != "")) {

        var nip = $('#formCadDocumento #Nip').val().replace(/\./g, '');
        //var nip = $('#formCadDocumento #Nip').val().replace(/\.\.\//g, "");

        if (nip.length != 8) {
            toastr.error('Informe um nip válido');
            //alertas("Informe um nip válido", '#ModIndexarDocumento', 'alert_danger');
            return false;
        }
    } else {
        toastr.error('Informe um nip válido');
        //alertas("Informe um nip válido", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #semestre').val() == 0)) {
        toastr.error('Informe o semestre');
        //alertas("Informe o semestre", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #ano').val() == 0) || ($('#formCadDocumento #ano').val().length != 4) || ($('#formCadDocumento #ano').val() > new Date().getFullYear())) {
        toastr.error('Informe um ano válido');
        //alertas("Informe um ano válido", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #SelectTipoDoc').val() == 0)) {
        toastr.error('Informe o tipo de documento');
        //alertas("Informe o tipo de documento", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if ((listDocumentos.length == 0)) {
        toastr.error('Ao menos um documento deve ser inserido para indexar');
        //alertas("Ao menos um documento deve ser inserido para indexar", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #ConfAssinatura').is(':checked') == true) && (($('#formCadDocumento #Assunto').val() == "")
        || ($('#formCadDocumento #Autor').val() == "")
        || ($('#formCadDocumento #Titulo').val() == "")
        || ($('#formCadDocumento #Identificador').val() == "")
        || ($('#formCadDocumento #Classe').val() == "")
        || ($('#formCadDocumento #DataProdDoc').val() == "")
        || ($('#formCadDocumento #DestinacaoDoc').val() == 0)
        || ($('#formCadDocumento #Genero').val() == "")
        || ($('#formCadDocumento #PrazoGuarda').val() == "")
        || ($('#formCadDocumento #Observacao').val() == ""))) {

            if (($('#formCadDocumento #Assunto').val() == "")) {
                toastr.error('Informe o assunto');
                return false;
            }
        
            if (($('#formCadDocumento #codOM').val() == "")) {
                toastr.error('Informe o autor');
                return false;
            }
        
            if (($('#formCadDocumento #Titulo').val() == "")) {
                toastr.error('Informe o título');
                return false;
            }
        
            if (($('#formCadDocumento #Classe').val() == "")) {
                toastr.error('Informe a Classe');
                return false;
            }
        
            if (($('#formCadDocumento #DataProdDoc').val() == "")) {
                toastr.error('Informe a data de produção');
                return false;
            }
        
            if (($('#formCadDocumento #DestinacaoDoc').val() == 0)) {
                toastr.error('Informe destinação prevista');
                return false;
            }
        
            if (($('#formCadDocumento #Genero').val() == "")) {
                toastr.error('Informe o gênero');
                return false;
            }
        
            if (($('#formCadDocumento #PrazoGuarda').val() == "")) {
                toastr.error('Informe o prazo de guarda');
                return false;
            }
        
            if (($('#formCadDocumento #Observacao').val() == "")) {
                toastr.error('Informe a observação');
                return false;
            }

        toastr.error('Existem tags não preenchidas');
        return false;
    }

    if ($('#formCadDocumento #ConfAssinatura').is(':checked') === false) {
        if (($('#formCadDocumento #Hash').val() == "") || ($('#formCadDocumento #Hash').val() == 0)) {
            toastr.error('Informe o hash');
            return false;
        }
    }

    tags = JSON.stringify({
        assunto: $('#formCadDocumento #Assunto').val(),
        autor: $('#formCadDocumento #codOM').val(),
        titulo: $('#formCadDocumento #Titulo').val(),
        identificador: $('#formCadDocumento #Nip').val() + Math.floor(Math.random() * 1000) + formatarDataHora(),
        classe: $('#formCadDocumento #Classe').val(),
        observacao: $('#formCadDocumento #Observacao').val(),
        dataProdDoc: $('#formCadDocumento #DataProdDoc').val(),
        destinacaoDoc: $('#formCadDocumento #DestinacaoDoc').val(),
        genero: $('#formCadDocumento #Genero').val(),
        prazoGuarda: $('#formCadDocumento #PrazoGuarda').val(),
        tipoDoc: $('#formCadDocumento #SelectTipoDoc').val(),
        hash: $('#formCadDocumento #Hash').val(),
        respDigitalizacao: $('#formCadDocumento #RespDigitalizacao').val(),
    }, null, 2);

    dados = JSON.stringify({
        idArmario: $('#formCadDocumento #ListArmarioDocumento').val(),
        nomeArmario: "",
        nip: $('#formCadDocumento #Nip').val(),
        semestre: $('#formCadDocumento #semestre').val(),
        ano: $('#formCadDocumento #ano').val(),
        tipoDoc: $('#formCadDocumento #SelectTipoDoc').val(),
        caminho: $('#formCadDocumento #Caminho').val(),
        assina: $('#formCadDocumento #ConfAssinatura').is(':checked'),
        tags: tags,
        imagens: listDocumentos,
    }, null, 2);

    $(this).attr('disabled', 'disabled');

    $.ajax({
        type: 'POST',
        url: "/cadastrarDocumento",
        data: dados,
        processData: false,
        contentType: false,
        success: function (retorno) {
            docid = retorno;
            $('#DocIdAtual').val(docid);

            $.ajax({
                type: 'POST',
                url: "/retorna-pdfs",
                data: dados,
                processData: false,
                contentType: false,
                success: function (data) {
                    //console.log(data);
                    processoAssinaturaData(data);
                },
                error: function (d) {
                    $('#btnConfirmaIndexarDocumento').prop('disabled', false)
                    toastr.error('Erro ao cadastrar o documento. Verfique os dados inseridos');
                    console.log("caso apresente erro de assinatura: " + d);
                }
            });
        },
        error: function (d) {
            $('#btnConfirmaIndexarDocumento').prop('disabled', false)
            toastr.error(d.responseText);
        }
    });
});

function formatarDataHora() {
    let data = new Date();

    let dia = String(data.getDate()).padStart(2, '0');
    let mes = String(data.getMonth() + 1).padStart(2, '0');
    let ano = data.getFullYear();

    let horas = String(data.getHours()).padStart(2, '0');
    let minutos = String(data.getMinutes()).padStart(2, '0');
    let segundos = String(data.getSeconds()).padStart(2, '0');

    return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
}

function processoAssinaturaData(data) {
    //console.log(data);
    try {
        var ArrayDocumentos = JSON.parse(data);
        totalDocumnetos = ArrayDocumentos.length;
        if ($('#formCadDocumento #ConfAssinatura').is(':checked')) {
            processarListaDeItens(ArrayDocumentos).then(function () {
                $('#documento-total').show()
                $('#download-doc').show()
                console.log('Todos os itens foram processados.');

                if (listDocumentosServidor.length == totalDocumnetos)
                    armazenaDocumentos(JSON.stringify({ listDocumentosServidor }, null, 2));

                console.log("Processo Terminado.");
                listDocumentos = [];
                listDocumentosServidor = [];
                $('#semestre').trigger('change');
                $('.btnIndexar').css("display", "none");
                $('.btnAnexar').css("display", "block");
                $('#btnConfirmaIndexarDocumento').removeAttr('disabled');
                $('#btnConfirmaAnexarDocumento').removeAttr('disabled');
                let tipoDoc = $('#tipo-doc').val()
                if (tipoDoc == 'indexar') {
                    toastr.success('Documento Indexado com sucesso');
                    FecharModal('#ModIndexarDocumento');
                    $('#verificarDocumentos').hide()
                    carregarDocumentos()
                } else if (tipoDoc == 'anexar') {
                    toastr.success('Documento Anexado com sucesso');
                    FecharModal('#ModAnexarDocumento');
                    $('#verificarDocumentos').hide()
                    carregarDocumentos()
                }
                $("#documento").val('')
                $("#Hash").val('')

            }).catch(function (error) {
                console.error('Ocorreu um erro:', error);
            });
        } else {
            listDocumentosServidor = [];
            ArrayDocumentos.forEach(doc => {
                doc["documentoid"] = docid;
                doc["imgencontrada"] = possuiPasta;
                dadosDocumento = JSON.stringify(doc);
                listDocumentosServidor.push(dadosDocumento);
            });

            armazenaDocumentos(JSON.stringify({ listDocumentosServidor }, null, 2));

            let tipoDoc = $('#tipo-doc').val()
            if (tipoDoc == 'indexar') {
                toastr.success('Documento Indexado com sucesso');
                FecharModal('#ModIndexarDocumento');
                //alertas('Documento Indexado com sucesso', '#ModIndexarDocumento', 'alert_sucess', 'true');
            } else if (tipoDoc == 'anexar') {
                toastr.success('Documento Anexado com sucesso');
                FecharModal('#ModAnexarDocumento');
                $('#verificarDocumentos').hide()
                carregarDocumentos()
            }
            $("#documento").val('')
            $("#Hash").val('')

            $('#semestre').trigger('change');
            $('.btnIndexar').css("display", "none");
            $('.btnAnexar').css("display", "block");
            $('#btnConfirmaIndexarDocumento').removeAttr('disabled');
            $('#btnConfirmaAnexarDocumento').removeAttr('disabled');
        }
    } catch (erro) {
        console.log("Erro bloco 4: " + erro.message)
    }
}

$(document).on('click', '#btnNaoConfirmaAnexarDocumento', function (e) {
    $('#btnConfirmaAnexarDocumento').prop('disabled', false)
    $('#tipo-doc').val('')
    FecharModal('#ModAnexarDocumento');
});

$(document).on('click', '.btnNaoConfirmaAnexarDocumento', function (e) {
    $('#btnConfirmaAnexarDocumento').prop('disabled', false)
    $('#tipo-doc').val('')
    FecharModal('#ModAnexarDocumento');
});

$(document).on('click', '#IndexarDocumento', function (e) {
    $('#tipo-doc').val('indexar');
    $("#ModIndexarDocumento").modal()
});

$(document).on('click', '#AnexarDocumento', function (e) {
    $('#tipo-doc').val('anexar');
    $("#ModAnexarDocumento").modal()
});

$(document).on('click', '#excluirDocumento', function (e) {
    $('#tipo-doc').val('reindexar');
    $("#ModReIndexarDocumento").modal()
});

$(document).on('click', '#btnConfirmaAnexarDocumento', function (e) {
    listDocumentosServidor = [];
    if (($('#formCadDocumento #ListArmarioDocumento').val() == 0)) {
        toastr.error('Selecione um armário');
        return false;
    }

    if (($('#formCadDocumento #Nip').val() == "") || ($('#formCadDocumento #Nip').unmask().val().length != 8)) {
        toastr.error('Informe um nip válido');
        return false;
    }

    if (($('#formCadDocumento #semestre').val() == 0)) {
        toastr.error('Informe o semestre');
        return false;
    }

    if (($('#formCadDocumento #ano').val() == 0) || ($('#formCadDocumento #ano').val().length != 4) || ($('#formCadDocumento #ano').val() > new Date().getFullYear())) {
        toastr.error('Informe um ano válido');
        return false;
    }

    if (($('#formCadDocumento #SelectTipoDoc').val() == 0)) {
        toastr.error('Informe o tipo de documento');
        return false;
    }

    if ((listDocumentos.length == 0)) {
        toastr.error('Ao menos um documento deve ser inserido para indexar');
        return false;
    }

    if (($('#formCadDocumento #ConfAssinatura').is(':checked') == true) && (($('#formCadDocumento #Assunto').val() == "")
        || ($('#formCadDocumento #Autor').val() == "")
        || ($('#formCadDocumento #Titulo').val() == "")
        || ($('#formCadDocumento #Identificador').val() == "")
        || ($('#formCadDocumento #Classe').val() == "")
        || ($('#formCadDocumento #DataProdDoc').val() == "")
        || ($('#formCadDocumento #DestinacaoDoc').val() == 0)
        || ($('#formCadDocumento #Genero').val() == "")
        || ($('#formCadDocumento #PrazoGuarda').val() == "")
        || ($('#formCadDocumento #Observacao').val() == ""))) {

        if (($('#formCadDocumento #Assunto').val() == "")) {
            toastr.error('Informe o assunto');
            return false;
        }
    
        if (($('#formCadDocumento #codOM').val() == "")) {
            toastr.error('Informe o autor');
            return false;
        }
    
        if (($('#formCadDocumento #Titulo').val() == "")) {
            toastr.error('Informe o título');
            return false;
        }
    
        if (($('#formCadDocumento #Classe').val() == "")) {
            toastr.error('Informe a Classe');
            return false;
        }
    
        if (($('#formCadDocumento #DataProdDoc').val() == "")) {
            toastr.error('Informe a data de produção');
            return false;
        }
    
        if (($('#formCadDocumento #DestinacaoDoc').val() == 0)) {
            toastr.error('Informe destinação prevista');
            return false;
        }
    
        if (($('#formCadDocumento #Genero').val() == "")) {
            toastr.error('Informe o gênero');
            return false;
        }
    
        if (($('#formCadDocumento #PrazoGuarda').val() == "")) {
            toastr.error('Informe o prazo de guarda');
            return false;
        }
    
        if (($('#formCadDocumento #Observacao').val() == "")) {
            toastr.error('Informe a observação');
            return false;
        }

        toastr.error('Existem tags não preenchidas');
        return false;
    }

    if ($('#formCadDocumento #ConfAssinatura').is(':checked') === false) {
        if (($('#formCadDocumento #Hash').val() == "") || ($('#formCadDocumento #Hash').val() == 0)) {
            toastr.error('Informe o hash');
            return false;
        }
        
    }

    tags = JSON.stringify({
        assunto: $('#formCadDocumento #Assunto').val(),
        autor: $('#formCadDocumento #codOM').val(),
        titulo: $('#formCadDocumento #Titulo').val(),
        identificador: $('#formCadDocumento #Nip').val() + Math.floor(Math.random() * 1000) + formatarDataHora(),
        classe: $('#formCadDocumento #Classe').val(),
        observacao: $('#formCadDocumento #Observacao').val(),
        dataProdDoc: $('#formCadDocumento #DataProdDoc').val(),
        destinacaoDoc: $('#formCadDocumento #DestinacaoDoc').val(),
        genero: $('#formCadDocumento #Genero').val(),
        prazoGuarda: $('#formCadDocumento #PrazoGuarda').val(),
        tipoDoc: $('#formCadDocumento #SelectTipoDoc').val(),
        hash: $('#formCadDocumento #Hash').val(),
        respDigitalizacao: $('#formCadDocumento #RespDigitalizacao').val(),
    }, null, 2);

    dados = JSON.stringify({
        idArmario: $('#formCadDocumento #ListArmarioDocumento').val(),
        nomeArmario: "",
        nip: $('#formCadDocumento #Nip').val(),
        semestre: $('#formCadDocumento #semestre').val(),
        ano: $('#formCadDocumento #ano').val(),
        tipoDoc: $('#formCadDocumento #SelectTipoDoc').val(),
        caminho: $('#formCadDocumento #Caminho').val(),
        assina: $('#formCadDocumento #ConfAssinatura').is(':checked'),
        tags: tags,
        imagens: listDocumentos,
    }, null, 2);


    docid = $('#listPaginas #documentosLista .clickDocumento').attr("id");
    $(this).attr('disabled', 'disabled');
    try {
        $.ajax({
            type: 'POST',
            url: "/retorna-pdfs",
            data: dados,
            processData: false,
            contentType: false,
            success: function (data) {
                //console.log(data);
                $('#formCadDocumento #Nip').mask('00.0000.00');
                possuiPasta = 1;
                processoAssinaturaData(data);
            },
            error: function (d) {
                alertas("Erro ao cadastrar o documento. Verfique os dados inseridos", '#ModAnexarDocumento', 'alert_danger');
                $('#formCadDocumento #Nip').mask('00.0000.00');
            }
        });
    } catch (erro) {
        console.log("Erro bloco 1: " + erro.message)
    }

});

function processarListaDeItens(lista) {
    // Inicia a Promise
    //console.log("lista: " + lista);
    try {
        return lista.reduce(function (promessaAnterior, item) {
            // Processa cada item em série
            return promessaAnterior.then(function () {
                // Processa o item atual

                return processarItemComResposta(item).then(function (resposta) {
                    // Trata a resposta do item
                    console.log('Resposta para o item', item, ':', resposta);

                    docBase64Atual = resposta;

                    finalizarAssinatura(function () {
                        /* setTimeout(function () {
                             location.reload();
                         }, 3000);*/
                    });

                    docAtual = JSON.parse(docAtual);
                    docAtual["documentoid"] = docid;
                    docAtual["imgencontrada"] = possuiPasta;
                    docAtual["b64"] = docBase64Atual;
                    docAtual = JSON.stringify(docAtual);

                    listDocumentosServidor.push(docAtual);
                    //console.log("lista: " + listDocumentosServidor);

                    // Retorna uma Promise resolvida para avançar para o próximo item
                    return Promise.resolve();
                });
            });
        }, Promise.resolve());
    } catch (erro) {
        console.log("Erro bloco 3: " + erro.message)
    }
}

function assinarDocumentos(documentos) {
    console.log("Rotina de assinar: ");
    var ArrayDocumentos = JSON.parse(documentos);

    //console.log("Dados recebidos: " + docid);
    $.ajax({
        type: 'GET',
        url: "/converter-base64?caminho=" + ArrayDocumentos["arquivo"],
        data: "",
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log("Dados recebidos da transformação em pdfB64: " + data);
            $('#assinarPdf #content-value').val(data.replace(/[\\"]/g, ''));
            $('#assinarPdf #objetoAtual').val(documentos);
            prettyCommandSign();
            $('#assinarPdf').submit();
            ArrayDocumentos["b64"] = data;
            docBase64 = data;
        },
        error: function (d) {
            retorno == "Não foi possivel converter";
        }
    });

}

function processarItemComResposta(item) {

    try {
        return new Promise(function (resolve, reject) {
            assinarDocumentos(JSON.stringify(item));
            // Seleciona o campo onde você espera a resposta
            var campo = $('#assinatura');

            // Adiciona um ouvinte de eventos para o evento 'change'
            campo.on('change', function (event) {

                var valorInput = $(this).val();
                if (valorInput !== '') {
                    docAtual = $('#objetoAtual').val();
                    // console.log("documento atual " + docAtual);
                }
                // Quando o evento 'change' ocorrer, resolve a Promise com o valor do campo
                resolve(event.target.value); // Você pode passar algum dado relevante para a resolução, se necessário
            });

            // Aqui você pode fazer algo com o item, se necessário
            console.log('Processando item:', item);
        });
    } catch (erro) {
        console.log("Erro bloco 2: " + erro.message)
    }
}


function finalizarAssinatura(callback) {
    var ArrayDocumentos = JSON.parse(docAtual);
    console.log("atual: " + ArrayDocumentos["arquivo"]);
    //console.log("atualB64: " + docBase64Atual);
    atualizarArquivo(JSON.stringify({
        arquivoOriginal: ArrayDocumentos["arquivo"],
        arquivoOriginalB64: ArrayDocumentos["b64"].replace(/[\\"]/g, ''),
        arquivoB64: docBase64Atual.replace(/[\\"]/g, ''),
    }, null, 2));

    setTimeout(function () {
        callback();
    }, 2000);
    //console.log("Rotina de assinatura finalizada ");
    //finalizarCriptografia(docAtual);
}

function finalizarCriptografia(callback) {
    //criptgrafarDocumento(docAtual);
    armazenaDocumentos(docAtual);
    setTimeout(function () {
        callback();
    }, 3000);
    //console.log("Rotina de criptografia finalizada ");

}


function criptgrafarDocumento(documentos) {
    var formdata = new FormData($("form[id='formAnexarPagDoc']")[0]);
    console.log("Rotina de criptografar");
    var ArrayDocumentos = JSON.parse(documentos);
    //console.log(ArrayDocumentos["arquivo"]);

    $('#tratandoDocumento').val(ArrayDocumentos["arquivo"]);
    // console.log("arquivo para cifrar: " + ArrayDocumentos["arquivo"]);
    $.ajax({
        type: 'GET',
        url: "/criptografar-pdfs?caminho=" + ArrayDocumentos["arquivo"],
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            console.log('Processo  concluido');

        },
        error: function (d) {
            console.log('erro ao carregar arquivos ' + d);
        }
    });
    console.log("Fim da Rotina de criptografar");
}

function armazenaDocumentos(documentos) {
    console.log("Rotina de armazenamento ");

    var ArrayDocumentos = JSON.parse(documentos);
    // $('#tratandoDocumento').val(documentos);
    //console.log('arma: ' + ArrayDocumentos);
    $.ajax({
        type: 'POST',
        url: "/carregar-arquivos-servidor",
        data: documentos,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log("arm: " + data);
        },
        error: function (d) {
            console.log('erro ao armazena Documentos ' + d);
        }
    });
}

function prettyCommandSign() {
    $('#sign-websocket').val(JSON.stringify({
        command: "sign",
        type: "pdf",
        inputData: $('#content-value').val(),
        docId: docid
    }, null, 2));
}

function atualizarArquivo(documentos) {
    //console.log('dados recebidos para atualizar' + documentos);
    $.ajax({
        type: 'POST',
        url: "/atualizar-arquivo-assinado",
        data: documentos,
        processData: false,
        contentType: false,
        success: function (data) {
            // console.log(data);
        },
        error: function (d) {
            console.log('erro ao armazena Documentos ' + d);
        }
    });
}

function downloadPdf() {
    const data = $('#assinatura').val();
    const linkSource = `data:application/pdf;base64,${data}`;
    const downloadLink = document.createElement("a");
    const fileName = "assinado.pdf";
    downloadLink.href = linkSource;
    downloadLink.download = fileName;
    downloadLink.click();
}

$('.ExcDoc').on('click', function (e) {
    $('#ExcluirPagina').modal()
    $('#formExcluirPagina #id').val($(this).data("idpagina"));
    $('#formExcluirPagina #docid').val($(this).data("docid"));
});



$(document).ready(function () {
    $('.carousel').carousel({
        pause: true,
        interval: false
    });

    $("#documento").on('change', function () {
        if (($('#documento').length > 0)) {
            $('#btnCarregarArquivosImg').attr("disabled", false);
        }

        if (($('#documento').length > 0)) {
            $('#btnCarregarArquivosPDF').attr("disabled", false);
        }
    })

    $("#documentoPDF").on('change', function () {
        if ($('#documento').length > 0) {
            $('#btnCarregarArquivosPDF').attr("disabled", false);
        }
    })

    $("input[name='origemDoc']").click(function () {
        if ($(this).prop('checked')) {
            if ($(this).val() == "imgToPdf") {
                $('#gerenImagem').css("display", "block");
                $('#gerenPDF').css("display", "none");
            }

            if ($(this).val() == "pdfPronto") {
                $('#gerenPDF').css("display", "block");
                $('#gerenImagem').css("display", "none");
            }
        }
    });

    $('#formCadDocumento #ConfAssinatura').on('click', function (e) {
        if ($('#formCadDocumento #ConfAssinatura').is(':checked') == true) {
            $('#blocoHash').css('display', 'none');
        } else {
            $('#blocoHash').css('display', 'block');
        }
    });

    $('#formCadDocumento #ConfAssinatura').each(function (e) {
        $('#blocoHash').css('display', 'none');
    });

});

function validarSenha(senha) {
    // Verifica se a senha tem pelo menos 10 caracteres
    if (senha.length < 10) {
        return false;
    }

    // Verifica se a senha possui pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial
    if (!/[A-Z]/.test(senha) || !/[a-z]/.test(senha) || !/[0-9]/.test(senha) || !/[^A-Za-z0-9]/.test(senha)) {
        return false;
    }

    // Se a senha passar por todas as verificações, é considerada válida
    return true;
}