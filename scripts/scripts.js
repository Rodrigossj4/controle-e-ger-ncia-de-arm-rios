var vUrlListarArmarios = "/listarArmarios";
var vUrlListarTipoDocumento = "/listarTipodocumento";
var vUrlListarTipoPerfis = "/listarPerfis";
var vUrlListarUsuarios = "/listarUsuarios";
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
        alertas("Todos os campos do formulário são obrigatórios", '#modCadArmario', 'alert_danger');
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
            alertas('Armário cadastrado com sucesso', '#modCadArmario', 'alert_sucess');

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
            //console.log(d);
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
            alertas('Vinculo Excluido com sucesso', '#ConfDesArmTipoDoc', 'alert_sucess', 'true');
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

            alertas('Armário e documento vinculados com sucesso', '#GerenciarArmario', 'alert_sucess');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modLogin', 'alert_danger');
        }
    });
});

$(document).on('click', '#exibConfirmaAlteracaoArmario', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoArmario', function (e) {

    if (($('#formAltArmario #codigo').val() == "") || ($('#formAltArmario #nomeInterno').val() == "") || ($('#formAltArmario #nomeExterno').val() == "")) {
        alertas("Todos os campos do formulário são obrigatórios", '#AlteraArmario', 'alert_danger');
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
            alertas('Armário atualizado com sucesso', '#AlteraArmario', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas("Houve um problema para atualizar o armário", '#AlteraArmario', 'alert_danger');
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
            alertas('Armario excluído com sucesso', '#ExcluirArmario', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas("Houve um problema para excluir o armário. Verifique se existem tipo de documentos vinculados a ele antes de excluir.", '#ExcluirArmario', 'alert_danger', 'true');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirArmario', function (e) {
    FecharModal('#ExcluirArmario');
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
                sel.append('<div class="container_item_maior" id="gradeDocumentos"><div class=Descricao_maior>' + e.nip + '</div><div class=Descricao_maior>' + e.semestre + '</div><div class=Descricao_maior>' + e.ano + '</div><div class=Descricao_maior>' + e.desctipo + '</div><div class=Descricao_maior>' + e.nomeArmario + '</div><div class=Descricao_maior><form method="post" id="" name="" action="/tratar-documento"><input type="hidden" id="idDocumento" name="idDocumento" value="' + e.id + '"><input type="submit" id="btnAbrirDocumento" name="btnAbrirDocumento" class="btn btn-primary btnAbrirDocumento" value="Tratar Documento"></form></div></div>');
                //'<div class="container_item_maior" id="gradeDocumentos"><div class=Descricao_maior>' + e.nip + '</div><div class=Descricao_maior>' + e.semestre + '</div><div class=Descricao_maior>' + e.ano + '</div><div class=Descricao_maior>' + e.desctipo + '</div><div class=Descricao_maior>' + e.nomeArmario + '</div><div class=Descricao_maior><form method="post" id="" name="" action="/tratar-documento"><input type="hidden" id="idDocumento" name="idDocumento" value="' + e.id + '"><input type="submit" id="btnAbrirDocumento" name="btnAbrirDocumento" class="btn btn-primary btnAbrirDocumento" value="Tratar Documento"></form></div></div>'
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
            alertas('Documento cadastrado com Sucesso', '#modCadDocumento', 'alert_sucess');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modCadTipoDocumento', 'alert_danger');
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
            alertas('Documento cadastrado com sucesso', '#modIdxDocumento', 'alert_sucess');
            setTimeout(function () {
                location.reload();
            }, 3000);
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modCadTipoDocumento', 'alert_danger');
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
        alertas("Todos os campos do formulário são obrigatórios", '#modCadTipoDocumento', 'alert_danger');
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
            alertas('Tipo de documento cadastrado com sucesso', '#modCadTipoDocumento', 'alert_sucess');
        },
        error: function (d) {
            if (d["status"] == 409)
                alertas("Já existe um Tipo de documento com esse nome cadastrado", '#modCadTipoDocumento', 'alert_danger');

            if (d["status"] == 500)
                alertas("Houve um problema para cadastrar o Tipo de documento", '#modCadTipoDocumento', 'alert_danger');

        }
    });
});

$(document).on('click', '.excluirTipoDoc', function (e) {
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
            alertas('Tipo documento excluído com sucesso', '#modexcluirTipoDoc', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas("Houve um problema para excluir o tipo de documento. Verifique se há documentos cadastrados com esse tipo.", '#modexcluirTipoDoc', 'alert_danger', 'true');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirTipoDoc', function (e) {
    FecharModal('#modexcluirTipoDoc');
});


$(document).on('click', '.btnAlterarTipoDoc', function (e) {
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
            alertas('Tipo documento atualizado com sucesso', '#AlteraTipoDoc', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas("Houve um problema para atualizar o tipo de documento", '#AlteraTipoDoc', 'alert_danger');
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
        alertas("Todos os campos do formulário são obrigatórios", '#modCadPerfil', 'alert_danger');
        return false;
    }

    $.ajax({
        type: 'POST',
        url: "/cadastrarPerfil",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            carregarPerfis();
            $('#formCadPerfil #nomePerfil').val("");
            alertas('Perfil cadastrado com sucesso', '#modCadPerfil', 'alert_sucess');
        },
        error: function (d) {
            alertas('Houve um problema para cadastrar esse perfil', '#modCadPerfil', 'alert_danger');
        }
    });
});

$(document).on('click', '.btnAlterarPerfil', function (e) {
    $('#formAltPerfil #id').val($(this).data("id"));
    $('#formAltPerfil #nomeperfil').val($(this).data("desc"));
    $('.opcoesConfirmacao').css('display', 'none');
});

$(document).on('click', '#exibConfirmaAlteracaoPerfil', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoPerfil', function (e) {
    var formdata = new FormData($("form[id='formAltPerfil']")[0]);

    if (($('#formAltPerfil #nomeperfil').val() == "")) {
        alertas("Todos os campos do formulário são obrigatórios", '#AlteraPerfil', 'alert_danger');
        return false;
    }

    $.ajax({
        type: 'POST',
        url: "/alterarPerfil",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarPerfis();
            $(this).data("nomeperfil", "");
            alertas('Perfil atualizado com sucesso', '#AlteraPerfil', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#AlteraPerfil', 'alert_danger');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaAlteracaoPerfil', function (e) {
    FecharModal('#AlteraPerfil');
});

$(document).on('click', '.excluirPerfil', function (e) {
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
            //console.log(d);
            carregarPerfis();
            $(this).data("id", "");
            alertas('Perfil excluído com sucesso', '#modexcluirPerfil', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas('Não foi possível excluir o perfil. Verifique se existem usuários ativos.', '#modexcluirPerfil', 'alert_danger', 'true');
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
                sel.append('<tr><td>' + e.nomeusuario + '</td><td><button class="btn btn-warning btnAlterarUsuario" data-bs-toggle="modal" data-bs-target="#AlteraUsuario" data-id="' + e.codusuario + '" data-desc="' + e.nomeusuario + '">Editar</button></td><td><form method="post" id="excluir' + e.codusuario + '" name="formAltUsuario" id="formAltUsuario" action=""><input type="hidden" name="idUsuario" value="' + e.codusuario + '"><button class="btn btn-danger excluirUsuario" data-bs-toggle="modal" data-bs-target="#modexcluirUsuario" data-id="' + e.codusuario + '" type="button">Excluir</button></form></td></tr>');
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
        alertas("Todos os campos do formulário são obrigatórios", '#modCadUsuario', 'alert_danger');
        return false;
    }

    if (($('#formCadUsuario #nip').val().replace(/[^\d]+/g, '').length != 8)) {
        alertas("Campo NIP inválido", '#modCadUsuario', 'alert_danger');
        return false;
    }

    if (!validarSenha($('#formCadUsuario #senhausuario').val())) {
        alertas("A senha não atende ao requisitos mínimos", '#modCadUsuario', 'alert_danger');
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
            alertas('Usuario cadastrado com sucesso', '#modCadUsuario', 'alert_sucess');
        },
        error: function (d) {
            alertas(d.responseText, '#modCadUsuario', 'alert_danger');
        }
    });
});


$(document).on('click', '.btnAlterarUsuario', function (e) {
    $('#formAltUsuario #idAlt').val($(this).data("id"));
    $('#formAltUsuario #nomeusuarioAlt').val($(this).data("desc"));
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

    if (($('#formAltUsuario #nomeusuarioAlt').val() == "")) {
        alertas("Todos os campos do formulário são obrigatórios", '#AlteraUsuario', 'alert_danger');
        return false;
    }

    dados = JSON.stringify({
        codusuario: $('#formAltUsuario #idAlt').val(),
        nomeusuario: $('#formAltUsuario #nomeusuarioAlt').val(),
    }, null, 2)

    $.ajax({
        type: 'POST',
        url: "/alterarUsuario",
        data: dados,
        processData: false,
        contentType: false,
        success: function (d) {
            carregarUsuarios();
            $(this).data("nomeusuario", "");
            alertas('Dados do usuario atualizados com sucesso', '#AlteraUsuario', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#AlteraUsuario', 'alert_danger');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaAlteracaoUsuario', function (e) {
    FecharModal('#AlteraUsuario');
});

$(document).on('click', '.excluirUsuario', function (e) {
    $('#formExcluirUsuario #id').val($(this).data("id"));
});

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
            alertas('Usuario excluído com sucesso', '#modexcluirUsuario', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modexcluirUsuario', 'alert_danger');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirUsuario', function (e) {
    FecharModal('#modexcluirUsuario');
});


$('#formLogin #btnLogin').on('click', function (e) {
    var formdata = new FormData($("form[id='formLogin']")[0]);
    $.ajax({
        type: 'POST',
        url: "/login",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            console.log(d);
            if (d = true) {
                location.assign("/home");
            } else {
                $('#formLogin #senha').val("");
                $('#formLogin #nip').val("");
                alertas('Falha ao efeturar login', '#modLogin', 'alert_danger');
            }

        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modLogin', 'alert_danger');
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
    $(local + ' .btn-close').trigger('click');
}

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
                const arrayData = JSON.parse(data);
                //console.log(data);
                var sel = $("#documentosLista");
                sel.empty();
                arrayData.forEach(e => {
                    sel.append('<tr class="clickDocumento" id="' + e.id + '" ><td>1</td><td>' + e.nip + '</td><td>' + e.semestre + '</td><td>' + e.ano + '</td><td>' + e.desctipo + '</td></tr>');

                    //'<div class="container_item_maior" id="gradeDocumentos"><div class=Descricao_maior>' + e.nip + '</div><div class=Descricao_maior>' + e.semestre + '</div><div class=Descricao_maior>' + e.ano + '</div><div class=Descricao_maior>' + e.desctipo + '</div><div class=Descricao_maior>' + e.nomeArmario + '</div><div class=Descricao_maior><form method="post" id="" name="" action="/tratar-documento"><input type="hidden" id="idDocumento" name="idDocumento" value="' + e.id + '"><input type="submit" id="btnAbrirDocumento" name="btnAbrirDocumento" class="btn btn-primary btnAbrirDocumento" value="Indexar Documento"></form></div></div>'
                });

            },
            error: function (d) {

            }
        });
    }

    if ($("#listPaginas tr").length > 1) {
        $('.btnIndexar').css("display", "none");
        $('.btnAnexar').css("display", "block");
    } else {
        $('.btnIndexar').css("display", "block");
        $('.btnAnexar').css("display", "none");
    }

});

function exibeLinhasRegistros() {
    if ($("#listPaginas tr").length > 1) {
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
            //console.log(data);
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
            $('#regride').attr('data-indice', 0);
            sel.attr('data-docId', listDocumentosServidor[0][0]);
            sel.append('<iframe src="/' + listDocumentosServidor[0][1] + '" width="100%" height="500"></iframe>');

            $("#carouselExampleControls").css('display', 'block');

            let path = window.location.pathname;
            if (!path.includes('/visualizar-documentos'))
                $("#incluirDocumento").css('display', 'block');

        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
});
//te

$(document).on('click', '#modCadDocumento #avanca', function () {
    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) + 1 <= listDocumentosServidor.length)) {
        var sel = $("#listarDocumentos");
        sel.empty();
        sel.append('<iframe src="/' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);

        let indice = (parseInt($(this).attr('data-indice')) + 1) > (listDocumentosServidor.length - 1) ? listDocumentosServidor.length - 1 : parseInt($(this).attr('data-indice')) + 1;

        $(this).attr('data-indice', indice);


        $('#modCadDocumento #regride').attr('data-indice', parseInt($(this).attr('data-indice')) - 1);

    }
});

$(document).on('click', '#ModalDocumentosListados #avanca', function () {
    console.log("yeye");
    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) + 1 <= listDocumentosServidor.length)) {
        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        sel.append('<iframe src="/' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);

        let indice = (parseInt($(this).attr('data-indice')) + 1) > (listDocumentosServidor.length - 1) ? listDocumentosServidor.length - 1 : parseInt($(this).attr('data-indice')) + 1;

        $(this).attr('data-indice', indice);


        $('#ModalDocumentosListados #regride').attr('data-indice', parseInt($(this).attr('data-indice')) - 1);

    }
});

$(document).on('click', '#modCadDocumento #regride', function () {

    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) >= 0)) {

        var sel = $("#listarDocumentos");
        sel.empty();
        sel.append('<iframe src="/' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);
        let indice = (parseInt($(this).attr('data-indice')) - 1) < 0 ? 0 : parseInt($(this).attr('data-indice')) - 1;

        $(this).attr('data-indice', indice);
        $('#modCadDocumento #avanca').attr('data-indice', parseInt($(this).attr('data-indice')) + 1);
    }
});

$(document).on('click', '#ModalDocumentosListados #regride', function () {

    if ((listDocumentosServidor.length > 0) && (parseInt($(this).attr('data-indice')) >= 0)) {

        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        sel.append('<iframe src="/' + listDocumentosServidor[$(this).attr('data-indice')][1] + '" width="100%" height="500"></iframe>');
        sel.attr('data-docId', listDocumentosServidor[parseInt($(this).attr('data-indice'))][0]);
        let indice = (parseInt($(this).attr('data-indice')) - 1) < 0 ? 0 : parseInt($(this).attr('data-indice')) - 1;

        $(this).attr('data-indice', indice);
        $('#ModalDocumentosListados #avanca').attr('data-indice', parseInt($(this).attr('data-indice')) + 1);
    }
});

$(document).on('click', '#btnConfirmaReIndexarDocumento', function () {
    listDocumentosServidor = [];

    if ($("#listarDocumentos").attr("data-docid") == "") {
        console.log("Nenhum documento sendo exibido");
        alertas("Nenhum documento sendo exibido", '#modCadDocumento', 'alert_danger');
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
        arquivo: $("#listarDocumentos iframe").attr("src").replace(/\.\.\//g, "")
    }, null, 2);


    $.ajax({
        type: 'POST',
        url: "/ReIndexarPagina",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
            $('#semestre').trigger('change');
            alertas('Página Reindexada com sucesso', '#ModReIndexarDocumento', 'alert_sucess', 'true');
            /*setTimeout(function () {
                location.reload();
            }, 3000);*/
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#ExcluirPagina', 'alert_danger');
            console.log('erro ao excluir a página ' + d);
        }
    });
});

$(document).on('click', '#btnNaoConfirmaReIndexarDocumento', function (e) {
    FecharModal('#ModReIndexarDocumento');
});


function carregarLotes() {
    $.ajax({
        url: "/listar-lotes",
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        cache: false,
        success: function (data) {
            //console.log(data);
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

$('#formCadOM #btnCadOM').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadOM']")[0]);

    $.ajax({
        type: 'POST',
        url: "/cadastrarOM",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {

            alertas('OM cadastrada com sucesso', '#modCadOM', 'alert_sucess', 'true');
        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
});

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
            //console.log(data);
            $('#formCadDocumento #Caminho').val(data);
            ListarArquivos();
            $(".carousel-control-prev").removeAttr("id");
            $(".carousel-control-next").removeAttr("id");
        },
        error: function (d) {
            console.log('erro ao carregar arquivos ' + d);
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
    var index = listDocumentos.indexOf($("#listarDocumentosSelecionados .active img").attr("src"));
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
            //console.log('retorno: ' + data);
            listDocumentosPrimaria = [];
            listDocumentosPrimaria = JSON.parse(data);

            if (listDocumentos.length > 0)
                listDocumentosPrimaria = removeItems(listDocumentosPrimaria, listDocumentos);


            var sel = $("#listarDocumentos");
            sel.empty();
            if (listDocumentosPrimaria.length > 0) {
                var extensao = listDocumentosPrimaria[0][0].split('.').pop();

                if (extensao == "pdf") {
                    listDocumentosServidor = [];
                    let contador = 0;
                    listDocumentosPrimaria.forEach(e => {
                        listDocumentosServidor.push([contador, e]);
                        contador++;
                    });

                    //console.log(listDocumentosServidor);
                    $('#modCadDocumento .carousel-control-prev').attr('id', 'regride');
                    $('#modCadDocumento .carousel-control-next').attr('id', 'avanca');
                    $('#modCadDocumento #avanca').attr('data-indice', 1);
                    $('#modCadDocumento #regride').attr('data-indice', 0);
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
                $("#incluirDocumento").css('display', 'block');
                $("#carouselExampleControls").html();
            } else {
                $("#carouselExampleControls").css('display', 'none');
                $("#incluirDocumento").css('display', 'none');
                $("#carouselExampleControls").html();
            }
        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
}


function ListarArquivosSelecionados() {

    var extensao = listDocumentos.length > 0 ? listDocumentos[0].split('.').pop() : "";
    if (extensao == "pdf") {
        var sel = $("#listarDocumentosSelecionados");
        sel.empty();
        listDocumentosServidor = [];
        let contador = 0;
        listDocumentos.forEach(e => {
            listDocumentosServidor.push([contador, e]);
            contador++;
        });

        //console.log(listDocumentosServidor);
        $('#listarDocumentosSelecionados .carousel-control-prev').attr('id', 'regride');
        $('#listarDocumentosSelecionados .carousel-control-next').attr('id', 'avanca');
        $('#listarDocumentosSelecionados #avanca').attr('data-indice', 1);
        $('#listarDocumentosSelecionados #regride').attr('data-indice', 0);
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
        alertas("Selecione um armário", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    console.log($('#formCadDocumento #Nip').val().replace(/\./g, ''));
    if (($('#formCadDocumento #Nip').val() != "")) {

        var nip = $('#formCadDocumento #Nip').val().replace(/\./g, '');

        if (nip.length != 8) {
            alertas("Informe um nip válido", '#ModIndexarDocumento', 'alert_danger');
            return false;
        }
    } else {
        alertas("Informe um nip válido", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #semestre').val() == 0)) {
        alertas("Informe o semestre", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #ano').val() == 0) || ($('#formCadDocumento #ano').val().length != 4) || ($('#formCadDocumento #ano').val() > new Date().getFullYear())) {
        alertas("Informe um ano válido", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #SelectTipoDoc').val() == 0)) {
        alertas("Informe o tipo de documento", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if ((listDocumentos.length == 0)) {
        alertas("Ao menos um documento deve ser inserido para indexar", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #Assunto').val() == "") || ($('#formCadDocumento #Autor').val() == "") || ($('#formCadDocumento #Titulo').val() == "") || ($('#formCadDocumento #Identificador').val() == "") || ($('#formCadDocumento #Classe').val() == "")) {
        alertas("Existem tags não preenchidas. Verfique", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    tags = JSON.stringify({
        assunto: $('#formCadDocumento #Assunto').val(),
        autor: $('#formCadDocumento #Autor').val(),
        titulo: $('#formCadDocumento #Titulo').val(),
        identificador: $('#formCadDocumento #Identificador').val(),
        classe: $('#formCadDocumento #Classe').val(),
        observacao: $('#formCadDocumento #Observacao').val(),
        dataProdDoc: $('#formCadDocumento #DataProdDoc').val(),
        destinacaoDoc: $('#formCadDocumento #DestinacaoDoc').val(),
        genero: $('#formCadDocumento #Genero').val(),
        prazoGuarda: $('#formCadDocumento #PrazoGuarda').val(),
        tipoDoc: $('#formCadDocumento #SelectTipoDoc').val(),
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
        tags: tags,
        imagens: listDocumentos,
    }, null, 2);

    $.ajax({
        type: 'POST',
        url: "/cadastrarDocumento",
        data: dados,
        processData: false,
        contentType: false,
        success: function (retorno) {
            docid = retorno;

            $.ajax({
                type: 'POST',
                url: "/retorna-pdfs",
                data: dados,
                processData: false,
                contentType: false,
                success: function (data) {
                    console.log(data);
                    processoAssinaturaData(data);
                },
                error: function (d) {
                    alertas("Erro ao cadastrar o documento. Verfique os dados inseridos", '#IndexarDocumento', 'alert_danger');
                }
            });
        },
        error: function (d) {
            alertas(d.responseText, '#ModIndexarDocumento', 'alert_danger');
        }
    });
});

function processoAssinaturaData(data) {
    var ArrayDocumentos = JSON.parse(data);
    totalDocumnetos = ArrayDocumentos.length;
    if ($('#formCadDocumento #ConfAssinatura').is(':checked')) {
        processarListaDeItens(ArrayDocumentos).then(function () {
            console.log('Todos os itens foram processados.');

            if (listDocumentosServidor.length == totalDocumnetos)
                armazenaDocumentos(JSON.stringify({ listDocumentosServidor }, null, 2));

            console.log("Processo Terminado.");
            listDocumentos = [];
            listDocumentosServidor = [];
            $('#semestre').trigger('change');
            $('.btnIndexar').css("display", "none");
            $('.btnAnexar').css("display", "block");

            alertas('Documento Indexado com sucesso', '#ModIndexarDocumento', 'alert_sucess', 'true');
            alertas('Documento Anexado com sucesso', '#ModAnexarDocumento', 'alert_sucess', 'true');

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
        alertas('Documento Indexado com sucesso', '#ModIndexarDocumento', 'alert_sucess', 'true');
        alertas('Documento Anexado com sucesso', '#ModAnexarDocumento', 'alert_sucess', 'true');
        $('#semestre').trigger('change');
        $('.btnIndexar').css("display", "none");
        $('.btnAnexar').css("display", "block");

    }
}

$(document).on('click', '#btnNaoConfirmaAnexarDocumento', function (e) {
    FecharModal('#ModAnexarDocumento');
});

$(document).on('click', '#btnConfirmaAnexarDocumento', function (e) {
    listDocumentosServidor = [];
    if (($('#formCadDocumento #ListArmarioDocumento').val() == 0)) {
        alertas("Selecione um armário", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #Nip').val() == "") || ($('#formCadDocumento #Nip').unmask().val().length != 8)) {
        alertas("Informe um nip válido", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #semestre').val() == 0)) {
        alertas("Informe o semestre", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #ano').val() == 0) || ($('#formCadDocumento #ano').val().length != 4) || ($('#formCadDocumento #ano').val() > new Date().getFullYear())) {
        alertas("Informe um ano válido", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #SelectTipoDoc').val() == 0)) {
        alertas("Informe o tipo de documento", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    if ((listDocumentos.length == 0)) {
        alertas("Ao menos um documento deve ser inserido para indexar", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #Assunto').val() == "") || ($('#formCadDocumento #Autor').val() == "") || ($('#formCadDocumento #Titulo').val() == "") || ($('#formCadDocumento #Identificador').val() == "") || ($('#formCadDocumento #Classe').val() == "")) {
        alertas("Existem tags não preenchidas. Verfique", '#ModAnexarDocumento', 'alert_danger');
        return false;
    }

    tags = JSON.stringify({
        assunto: $('#formCadDocumento #Assunto').val(),
        autor: $('#formCadDocumento #Autor').val(),
        titulo: $('#formCadDocumento #Titulo').val(),
        identificador: $('#formCadDocumento #Identificador').val(),
        classe: $('#formCadDocumento #Classe').val(),
        observacao: $('#formCadDocumento #Observacao').val(),
        dataProdDoc: $('#formCadDocumento #DataProdDoc').val(),
        destinacaoDoc: $('#formCadDocumento #DestinacaoDoc').val(),
        genero: $('#formCadDocumento #Genero').val(),
        prazoGuarda: $('#formCadDocumento #PrazoGuarda').val(),
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
        tags: tags,
        imagens: listDocumentos,
    }, null, 2);


    docid = $('#listPaginas #documentosLista .clickDocumento').attr("id");
    $.ajax({
        type: 'POST',
        url: "/retorna-pdfs",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            possuiPasta = 1;
            processoAssinaturaData(data);
        },
        error: function (d) {
            alertas("Erro ao cadastrar o documento. Verfique os dados inseridos", '#ModAnexarDocumento', 'alert_danger');

        }
    });

});

function processarListaDeItens(lista) {
    // Inicia a Promise
    //console.log("lista: " + lista);
    return lista.reduce(function (promessaAnterior, item) {
        // Processa cada item em série
        return promessaAnterior.then(function () {
            // Processa o item atual

            return processarItemComResposta(item).then(function (resposta) {
                // Trata a resposta do item
                // console.log('Resposta para o item', item, ':', resposta);

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
}

function assinarDocumentos(documentos) {
    console.log("Rotina de assinar: ");
    var ArrayDocumentos = JSON.parse(documentos);

    //console.log("Dados recebidos: " + ArrayDocumentos);
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
    //console.log('arma: ' + documentos);
    $.ajax({
        type: 'POST',
        url: "/carregar-arquivos-servidor",
        data: documentos,
        processData: false,
        contentType: false,
        success: function (data) {
            // console.log("arm: " + data);
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
        inputData: $('#content-value').val()
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