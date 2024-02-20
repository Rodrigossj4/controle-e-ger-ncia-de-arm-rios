var vUrlListarArmarios = "/listarArmarios";
var vUrlListarTipoDocumento = "/listarTipodocumento";
//var vUrlProdutos = "http://127.0.0.1:5000/Produtos";

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
                sel.append('<div class="container_item"><div class="Descricao">' + e.nomeexterno + '</div><div class="acoes"><button class="btn btn-warning btnAlterarArmario" data-bs-toggle="modal" data-bs-target="#AlteraArmario" data-id="' + e.id + '" data-ni="' + e.nomeinterno + '" data-ne="' + e.nomeexterno + '" data-cd="' + e.codigo + '">Editar</button><form method="post" id="excluir' + e.id + '" action=""><input type="hidden" id="idArmario" name="idArmario" value="' + e.id + '" ><button class="btn btn-danger excluir" data-id="' + e.id + '" data-bs-toggle="modal" data-bs-target="#ExcluirArmario" type="button">Excluir</button></form></div></div>');

            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#btnCadArmario').on('click', function (e) {
    var formdata = new FormData($("form[id='formCadArmario']")[0]);
    $.ajax({
        type: 'POST',
        url: "/cadastrarArmario",
        data: formdata,
        processData: false,
        contentType: false,

        success: function (d) {
            carregarArmarios();
            $('#formCadArmario #codigo').val("");
            $('#formCadArmario #nomeInterno').val("");
            $('#formCadArmario #nomeExterno').val("");
            alertas('Armário cadastrado com sucesso', '#modCadArmario', 'alert_sucess');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modCadArmario', 'alert_danger');
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

$(document).on('click', '#exibConfirmaAlteracaoArmario', function (e) {
    $('.opcoesConfirmacao').css('display', 'flex');
});

$(document).on('click', '#btnConfirmaAlteracaoArmario', function (e) {

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
            alertas('Armário atualizado com sucesso', '#AlteraArmario', 'alert_sucess');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#AlteraArmario', 'alert_danger');
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
            carregarArmarios();
            $(this).data("id", "");
            alertas('Armario excluído com sucesso', '#ExcluirArmario', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#ExcluirArmario', 'alert_danger');
        }
    }
    );
});

$(document).on('click', '#btnNaoConfirmaExcluirArmario', function (e) {
    FecharModal('#ExcluirArmario');
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
                sel.append('<div class="container_item"><div class="Descricao">' + e.desctipo + '</div><div class="acoes"><button class="btn btn-warning btnAlterarTipoDoc" data-bs-toggle="modal" data-bs-target="#AlteraTipoDoc" data-id="' + e.id + '" data-desc="' + e.desctipo + '">Editar</button><form method="post" id="excluir' + e.id + '"><input type="hidden" id="idTipoDoc" name="idTipoDoc" value="' + e.id + '"><button class="btn btn-danger excluirTipoDoc" data-id="' + e.id + '" data-bs-toggle="modal" data-bs-target="#modexcluirTipoDoc" type="button">Excluir</button></form></div></div>');

            });
        },
        error: function (data) {
            console.log("Ocorreu um erro: " + data);
        }
    });
}

$('#formCadTipoDocumento #btnCadTipoDoc').on('click', function (e) {
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
            alertas(d.responseJSON['msg'], '#modCadTipoDocumento', 'alert_danger');
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
            carregarTipoDocumento();
            $(this).data("id", "");
            alertas('Tipo documento excluído com sucesso', '#modexcluirTipoDoc', 'alert_sucess', 'true');
        },
        error: function (d) {
            alertas(d.responseJSON['msg'], '#modxcluirTipoDoc', 'alert_danger');
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
            alertas(d.responseJSON['msg'], '#AlteraTipoDoc', 'alert_danger');
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
    var nomeForm = "docid_" + $(this).data("id");
    var formdata = new FormData($("form[id='" + nomeForm + "']")[0]);
    window.open("/visualizarDocumento?docid=" + $(this).data("id"), "janela1", "width=800, height=600, directories=no, location=no, menubar=no,scrollbars=no, status=no, toolbar=no, resizable=no")
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
