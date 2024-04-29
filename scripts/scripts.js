var vUrlListarArmarios = "/listarArmarios";
var vUrlListarTipoDocumento = "/listarTipodocumento";
var vUrlListarTipoPerfis = "/listarPerfis";
var vUrlListarUsuarios = "/listarUsuarios";
//var vUrlProdutos = "http://127.0.0.1:5000/Produtos";
let listDocumentos = [];
let listDocumentosServidor = [];
let totalDocumnetos = 0;
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
            //console.log(d);
            carregarArmarios();
            $('#formCadArmario #codigo').val("");
            $('#formCadArmario #nomeInterno').val("");
            $('#formCadArmario #nomeExterno').val("");
            alertas('Armário cadastrado com sucesso', '#modCadArmario', 'alert_sucess');

        },
        error: function (d) {
            console.log(d);
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
            console.log(d);
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

$('.btnGerenciarArmario').on('click', function (e) {
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
            console.log(data);
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
            console.log(d);
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
            alertas('Usuario cadastrado com sucesso', '#modCadUsuario', 'alert_sucess');
        },
        error: function (d) {
            alertas(d, '#modCadUsuario', 'alert_danger');
        }
    });
});


$(document).on('click', '.btnAlterarUsuario', function (e) {
    $('#formAltUsuario #idAlt').val($(this).data("id"));
    $('#formAltUsuario #nomeusuarioAlt').val($(this).data("desc"));
    $('.opcoesConfirmacao').css('display', 'none');
});

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
            //console.log(d);
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
                    sel.append('<tr><td>1</td><td>' + e.nip + '</td><td>' + e.semestre + '</td><td>' + e.ano + '</td><td>' + e.desctipo + '</td></tr>');

                    //'<div class="container_item_maior" id="gradeDocumentos"><div class=Descricao_maior>' + e.nip + '</div><div class=Descricao_maior>' + e.semestre + '</div><div class=Descricao_maior>' + e.ano + '</div><div class=Descricao_maior>' + e.desctipo + '</div><div class=Descricao_maior>' + e.nomeArmario + '</div><div class=Descricao_maior><form method="post" id="" name="" action="/tratar-documento"><input type="hidden" id="idDocumento" name="idDocumento" value="' + e.id + '"><input type="submit" id="btnAbrirDocumento" name="btnAbrirDocumento" class="btn btn-primary btnAbrirDocumento" value="Indexar Documento"></form></div></div>'
                });
            },
            error: function (d) {

            }
        });
    }
});

$('#SelectLote #lote').on('change', function () {

    var formdata = new FormData($("form[id='SelectLote']")[0]);
    var id = $(this).val();

    $.ajax({
        type: 'POST',
        url: "/ListarDocumentos",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            const arrayData = JSON.parse(data);
            var sel = $("#listarDocumentos");
            sel.empty();
            arrayData.forEach(e => {
                var url = id + '\/' + e;
                sel.append(e + ' - <input type="button" class="btn btn-primary" data-arquivo="' + url + '" id="arquivosLote"  value="Visualizar documento">  <input type="checkbox" name="documentoEscolhido" value="' + url + '"> Incluir no documento </br>');
            })
        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
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

$('#formCadLote #btnCadLote').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadLote']")[0]);

    $.ajax({
        type: 'POST',
        url: "/cadastrarLote",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
            console.log("cadastrar");
            /*const arrayData = JSON.parse(data);
            var sel = $("#listarDocumentos");
            sel.empty();
            arrayData.forEach(e => {
                var url = caminho + '\/' + e;
                sel.append(e + ' - <button class="btn btn-primary" data-arquivo="' + url + '" id="arquivos"> Visualizar documento</button> </br>');
            })*/
        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
});

$('#formCadDocumento #btnCarregarArquivosImg').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadDocumento']")[0]);
    $.ajax({
        type: 'POST',
        url: "/carregarArquivos",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
            $('#formCadDocumento #Caminho').val(data);
            ListarArquivos();
        },
        error: function (d) {
            console.log('erro ao carregar arquivos ' + d);
        }
    });
});

$('#incluirDocumento').on('click', function (e) {
    listDocumentos.push($("#listarDocumentos .active img").attr("src").replace(/\.\.\//g, ""));
    //console.log(listDocumentos);
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
            //console.log(data);
            $('#formAnexarPagDoc #Caminho').val(data);
            ListarArquivos();
        },
        error: function (d) {
            console.log('erro ao carregar arquivos ' + d);
        }
    });
});

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
            const arrayData = JSON.parse(data);
            var sel = $("#listarDocumentos");
            sel.empty();
            arrayData.forEach(e => {
                var active = (contador == 0) ? 'active' : '';
                sel.append('<div class="carousel-item ' + active + '" style="width: 400px; height: 100Vh;"><img src="' + e + '" class="d-block w-100" alt="Imagem 1"> </div>');
                contador++;
            });

            $("#carouselExampleControls").html();

        },
        error: function (d) {
            console.log('ei erro ' + d);
        }
    });
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
$(document).on('click', '#btnConfirmaIndexarDocumento', function (e) {

    if (($('#formCadDocumento #ListArmarioDocumento').val() == 0)) {
        alertas("Selecione um armário", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    if (($('#formCadDocumento #Nip').val() == "") || ($('#formCadDocumento #Nip').val().length != 8)) {
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

    if (($('#formTags #Assunto').val() == "") || ($('#formTags #Autor').val() == "") || ($('#formTags #Titulo').val() == "") || ($('#formTags #Identificador').val() == "") || ($('#formTags #Classe').val() == "")) {
        alertas("Existem tags não preenchidas. Verfique", '#ModIndexarDocumento', 'alert_danger');
        return false;
    }

    tags = JSON.stringify({
        assunto: $('#formTags #Assunto').val(),
        autor: $('#formTags #Autor').val(),
        titulo: $('#formTags #Titulo').val(),
        identificador: $('#formTags #Identificador').val(),
        classe: $('#formTags #Classe').val(),
        observacao: $('#formTags #Observacao').val(),
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
                    //console.log(data);
                    var ArrayDocumentos = JSON.parse(data);
                    totalDocumnetos = ArrayDocumentos.length;
                    ArrayDocumentos.forEach(doc => {
                        dadosDocumento = JSON.stringify(doc);
                        assinarDocumentos(dadosDocumento);
                    });
                },
                error: function (d) {
                    alertas("Erro ao cadastrar o documento. Verfique os dados inseridos", '#IndexarDocumento', 'alert_danger');

                }
            });
        },
        error: function (d) {
            alertas("Erro ao cadastrar o documento. Verfique os dados inseridoss", '#ModIndexarDocumento', 'alert_danger');
        }
    });


});

function assinarDocumentos(documentos) {
    console.log("Rotina de assinar: ");
    var ArrayDocumentos = JSON.parse(documentos);

    $.ajax({
        type: 'GET',
        url: "/converter-base64?caminho=" + ArrayDocumentos["arquivo"],
        data: "",
        processData: false,
        contentType: false,
        success: function (data) {
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

$(document).ready(function () {
    $('#assinatura').change(function () {
        var valorInput = $(this).val();
        if (valorInput !== '') {
            docAtual = $('#objetoAtual').val();
            docBase64Atual = $(this).val();
            //console.log(dadosDocumento);
            finalizarAssinatura(function () {
                //console.log("Terminou: ");

                setTimeout(function () {
                    location.reload();
                }, 3000);
            });
            // finalizarCriptografia(function () {

            docAtual = JSON.parse(docAtual);
            docAtual["documentoid"] = docid;
            docAtual = JSON.stringify(docAtual);

            listDocumentosServidor.push(docAtual);
            if (listDocumentosServidor.length == totalDocumnetos)
                armazenaDocumentos(JSON.stringify({ listDocumentosServidor }, null, 2));

            // });
            alertas('Documento Indexado com sucesso', '#IndexarDocumento', 'alert_sucess', 'true');
        }
    });
});

function finalizarAssinatura(callback) {
    var ArrayDocumentos = JSON.parse(docAtual);
    //console.log("atual: " + ArrayDocumentos["arquivo"]);
    //console.log("atualB64: " + docBase64Atual);
    atualizarArquivo(JSON.stringify({
        arquivoOriginal: ArrayDocumentos["arquivo"],
        arquivoOriginalB64: ArrayDocumentos["b64"].replace(/[\\"]/g, ''),
        arquivoB64: docBase64Atual.replace(/[\\"]/g, ''),
    }, null, 2));

    setTimeout(function () {
        callback();
    }, 2000);
    console.log("Rotina de assinatura finalizada ");
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

    //var ArrayDocumentos = JSON.parse(documentos);
    // $('#tratandoDocumento').val(documentos);
    //console.log('arma: ' + documentos);
    $.ajax({
        type: 'POST',
        url: "/carregar-arquivos-servidor",
        data: documentos,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log("arm: " + data);
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
    //console.log('dados recebidos' + documentos);
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

$(document).on('click', '.btnConfirmaExcluirPagina', function (e) {

    dados = JSON.stringify({
        idPagina: $('#formExcluirPagina #id').val(),
        docId: $('#formExcluirPagina #docid').val(),
    }, null, 2)

    $.ajax({
        type: 'POST',
        url: "/excluirPagina",
        data: dados,
        processData: false,
        contentType: false,
        success: function (data) {
            //console.log(data);
            alertas('Página Excluida com sucesso', '#ExcluirPagina', 'alert_sucess', 'true');
            setTimeout(function () {
                location.reload();
            }, 3000);
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#ExcluirPagina', 'alert_danger');
            console.log('erro ao excluir a página ' + d);
        }
    });
});

$(document).on('click', '#btnNaoConfirmaExcluirPagina', function (e) {
    FecharModal('#ExcluirPagina');
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


