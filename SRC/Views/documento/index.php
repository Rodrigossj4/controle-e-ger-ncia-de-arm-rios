<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $DocumentosList */
/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList  */
/** @var Marinha\Mvc\Models\OM[] $dadosOM  */
/** @var Marinha\Mvc\Models\OM[] $OMList */
//$TotalArquivos = count($listaArquivosCarregados);
$contador = 0;
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<script src="../../../scripts/serpro/lib/serpro/is.min.js" type="text/javascript"></script>

<!-- os próximos dois arquivos - em Javascript puro - são a API de comunicação com o Assinador SERPRO -->
<script src="../../../scripts/serpro/lib/serpro/serpro-signer-promise.js" type="text/javascript"></script>
<script src="../../../scripts/serpro/lib/serpro/serpro-signer-client.js" type="text/javascript"></script>

<!-- PDFJS, para converter bas364 em PDF -->
<!-- <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.6.82/pdf.min.mjs" type="module"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.6.82/pdf_viewer.min.css">
<div class="container">
    <div class="tituloModulo">Gerenciar Documentos</div>
    <div style="border: 1px solid;" class="row" id="modCadDocumento">
        <div class="col-md-4 order-md-1">
            <form method="post" id="formCadDocumento" action="/cadastrarDocumento" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-form-label" for="Armario">Armario:<span class="text-danger">*</span> </label><br>
                    <input type="hidden" id="flagCadastro" name="flagCadastro" />
                    <input type="hidden" id="Caminho" name="Caminho" value="">
                    <input type="hidden" id="tratandoDocumento" name="tratandoDocumento" value="">
                    <select id="ListArmarioDocumento" name="ListArmarioDocumento" class="form-select">
                        <option value="0">Selecione um armário</option>
                        <?php foreach ($ArmariosList  as $armarios) : ?>
                            <option value="<?= $armarios['id']; ?>"><?= $armarios['nomeexterno']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="Nip">NIP:<span class="text-danger">*</span> </label> </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="Nip" id="Nip">
                </div>
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label class="col-form-label" for="semestre">Semestre:<span class="text-danger">*</span> </label> </label>
                        <br>
                        <select id="semestre" name="semestre" class="form-select">
                            <option value="0">Selecione o semestre</option>
                            <option value="1">Primeiro semestre</option>
                            <option value="2">Segundo semestre</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="ano">Ano:<span class="text-danger">*</span> </label> </label>
                        <input class="form-control form-control-padronizado" type="number" name="ano" id="ano">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="TipoDoc">Tipo de documento:<span class="text-danger">*</span> </label> </label>
                    </br>
                    <select id="SelectTipoDoc" name="SelectTipoDoc" class="form-select">
                        <option value="0">Selecione o tipo de documento</option>
                    </select>
                </div>
                <hr class="mb-4">
                <div class="form-group Metatags" id="Metatags">
                    <label><b>Informar MetaTags</b></label>
                </div>
                <div class="containerTags" id="containerTags">
                    <div class="form-group">
                        <label class="col-form-label" for="Assunto">Informe o assunto:<span class="text-danger">*</span> </label> </label>
                        <input type="hidden" id="RespDigitalizacao" name="RespDigitalizacao" value="<?php echo htmlspecialchars($nipUsuario); ?>">
                        <input type="text" id="Assunto" name="Assunto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="Autor">Informe o Autor:<span class="text-danger">*</span> </label> </label>
                        <select name="codOM" id="codOM" class="form-select">
                            <?php foreach ($OMList  as $om) : ?>
                                <option value="<?= $om['NomeAbreviado']; ?>"><?= $om['NomeAbreviado'] . " - " . $om['NomOM']; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="Titulo">Informe o Titulo:<span class="text-danger">*</span> </label></label>
                        <input type="text" id="Titulo" name="Titulo" class="form-control">
                    </div>
                    <!--<div class="form-group">
                        <label class="col-form-label" for="Identificador">Identificador do documento digital</label>
                        <input id="Identificador" name="Identificador" class="form-control" />
                    </div>-->
                    <div class="form-group">
                        <label class="col-form-label" for="Classe">Classe:<span class="text-danger">*</span> </label></label>
                        <input id="Classe" name="Classe" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="DataProdDoc">Data de produção:<span class="text-danger">*</span> </label></label>
                        <input id="DataProdDoc" name="DataProdDoc" class="form-control" type="date" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="DestinacaoDoc">Destinação prevista:<span class="text-danger">*</span> </label></label>
                        <select id="DestinacaoDoc" name="DestinacaoDoc" class="form-select">
                            <option value="0">Selecione a Destinação prevista</option>
                            <option value="transferencia">Transferência</option>
                            <option value="eliminação">Eliminação</option>
                            <option value="recolhimento">Recolhimento</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="Genero">Gênero:<span class="text-danger">*</span> </label></label>
                        <input id="Genero" name="Genero" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="PrazoGuarda">Prazo de guarda:<span class="text-danger">*</span> </label></label>
                        <input id="PrazoGuarda" name="PrazoGuarda" class="form-control" value="130 anos" />
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="Observacao">Observação:<span class="text-danger">*</span> </label>
                        <input id="Observacao" name="Observacao" class="form-control" />
                    </div>
                </div>
                <hr class="mb-4">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="col-form-label" for="documento[]">Selecione arquivos</label>
                        <input type="file" id="documento" name="documento[]" class="form-control" accept=".jpg,.jpeg,.png,.tif,.tiff,.pdf" multiple>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-9">
                        <input type="hidden" id="Caminho" name="Caminho" value="">
                        <input type="buttton" class="btn btn-primary" name="btnCarregarArquivosImg" id="btnCarregarArquivosImg" value="Carregar Arquivos" disabled="true">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <input type="checkbox" id="ConfAssinatura" value="1" checked> <label for="ConfAssinatura">Assinar documento</label>
                    </div>
                </div>
                <div class="form-group" id="blocoHash">
                    <label class="col-form-label" for="Hash">Insira o Hash:<span class="text-danger">*</span> </label></label>
                    <!--<input id="Hash" name="Hash" onkeyup="verificaHash()" class="form-control" value="" />-->
                    <textarea rows="3" cols="" id="Hash" name="Hash" onkeyup="verificaHash()" class="form-control" width="100%"></textarea>
                </div>
                <br>
                <div class="form-group row">

                    <div class="col-sm-3 btnIndexar">
                        <input type="button" data-bs-toggle="modal" data-bs-target="#ModIndexarDocumento" id="IndexarDocumento" name="IndexarDocumento" value="Indexar" class="btn btn-primary">
                    </div>
                    <div class="col-sm-3 btnAnexar">
                        <input type="button" data-bs-toggle="modal" data-bs-target="#ModAnexarDocumento" id="AnexarDocumento" value="Anexar" class="btn btn-primary">

                    </div>
                    <div class="col-sm-6 ">
                        <input type="button" name="excluirDocumento" id="excluirDocumento" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModReIndexarDocumento" value="ReIndexar página" />
                    </div>
                    <span class="alerta"></span>
                </div>

            </form>
            <input type="hidden" name="tipo-doc" id="tipo-doc" value="">

            <hr class="mb-4">
            <div class="bg-body-tertiary rounded-3 row">
                <div class="col order-md-1">
                    <div class="container">
                        <div class="row">
                            <table class="table table-striped" id="listPaginas">
                                <thead>
                                    <tr>
                                        <th scope="col">Itens</th>
                                        <th scope="col">NIP</th>
                                        <th scope="col">Semestre</th>
                                        <th scope="col">Ano</th>
                                        <th scope="col">Tipo de documento</th>
                                        <th scope="col">Páginas</th>
                                    </tr>
                                </thead>
                                <tbody id='documentosLista'>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-8 order-md-1" style="border: 1px solid;">
            <div class="container mt-8">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="0">
                    
                    <span id="download-doc"></span> <span id="documento-total"></span>

                    <div class="carousel-inner" id="listarDocumentos" data-docId="">

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" data-indice="0">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>

                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next" data-indice="0">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>

                    </a>
                </div>
            </div>

            <div class="container mt-8">
                <button name="incluirDocumento" id="incluirDocumento" class="btn btn-primary mt-2">incluir</button>
                <?php if($_SESSION['usuario'][0]['nivelAcesso']===1){ ?>
                <button name="excluirDocumentoMalIndexado" id="excluirDocumentoMalIndexado" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#ModExcluirPagina">Excluir</button>
                <?php } ?>
                <button name="verificarDocumentos" id="verificarDocumentos" class="btn btn-primary mt-2" data-toggle="modal" data-target=".bd-example-modal-lg">Verificar documentos inseridos</button>
            </div>
        </div>
    </div>
    <div class="row col-6">
        <div class="panel panel-default" style="display: none;">
            <div class="panel-heading">
                <h3 class="panel-title">Assinar PDF</h3>
            </div>
            <div class="panel-body">
                <form id="assinarPdf">
                    <div class="form-group">
                        <label for="file_input">Escolher Arquivo PDF</label>
                        <input id="input-file" type="file" id="arquivo" name="arquivo" value="$paginasList.firstOrDefault()" onchange="convertToBase64();" />
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="DocIdAtual" name="DocIdAtual" value="">
                        <input type="text" id="objetoAtual" name="objetoAtual" value="">
                        <label for="content-value">Conteúdo do PDF (Base 64)</label>
                        <textarea id="content-value" class="form-control" rows="5" disabled></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <button type="submit" id="assinarPdf" name="assinarPdf" class="btn btn-primary">Assinar PDF</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sign-websocket">Comando WebSocket</label>
                        <textarea id="sign-websocket" class="form-control" rows="7" disabled></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row col-6" style="display: none;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">PDF Assinado</h3>
            </div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="resultado">Arquivo Assinado (PDF + Assinatura em Base 64)</label>
                        <textarea id="assinatura" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <button id="validarAssinaturaPdf" type="button" class="btn btn-primary">Validar Assinatura</button>
                        <button type="button" class="btn btn-primary" onclick="downloadPdf();">Download PDF</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


</body>

</html>
<?php require_once __DIR__ . "../../rodape.php" ?>
<script src="../../scripts/serpro/app/serpro-client-connector.js" type="text/javascript"></script>

<div class="modal fade" id="ModIndexarDocumento" tabindex="-1" aria-labelledby="ModIndexarDocumento" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" id="btnNaoConfirmaIndexarDocumento" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente indexar esse novo documento?</span>
                    <div class="col-sm-3">

                        <input type="button" id="btnConfirmaIndexarDocumento" data-id="" value="Sim" class="btn btn-success btnConfirmaIndexarDocumento">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaIndexarDocumento" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaIndexarDocumento">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ModAnexarDocumento" tabindex="-1" aria-labelledby="ModAnexarDocumento" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" id="btnNaoConfirmaAnexarDocumento" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Já existe um documento cadastrado com essas caracteristicas. Deseja anexar páginas a esse documento?</span>
                    <div class="col-sm-3">
                        <input type="button" id="btnConfirmaAnexarDocumento" data-id="" value="Sim" class="btn btn-success btnConfirmaAnexarDocumento">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaAnexarDocumento" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaAnexarDocumento">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModReIndexarDocumento" tabindex="-1" aria-labelledby="ModReIndexarDocumento" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" id="btnNaoConfirmaReIndexarDocumento" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente Re-indexar esse novo documento?</span>
                    <div class="col-sm-3">
                        <input type="button" id="btnConfirmaReIndexarDocumento" data-id="" value="Sim" class="btn btn-success btnConfirmaReIndexarDocumento">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaReIndexarDocumento" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaReIndexarDocumento">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModExcluirPagina" tabindex="-1" aria-labelledby="ModExcluirPagina" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" id="btnNaoConfirmaExcluirPagina" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente excluir essa página?</span>
                    <div class="col-sm-3">
                        <input type="button" id="btnConfirmaExcluirPagina" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirPagina">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaExcluirPagina" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirPagina">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="ModalDocumentosListados" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="carouselExampleControlsSelecionados" class="carousel slide" data-ride="carousel" data-interval="0">
                    <div class="carousel-inner" id="listarDocumentosSelecionados" data-docId="">
                        <div class="carousel-item " style="width: 400px; height: 100Vh; ">

                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControlsSelecionados" role="button" data-slide="prev" data-indice="0">
                        
                        <span class="sr-only" style="color: black;"><span class="carousel-control-prev-icon" aria-hidden="true" style="color: black;"></span></span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControlsSelecionados" role="button" data-slide="next" data-indice="0">
                        
                        <span class="sr-only" style="color: black;"><span class="carousel-control-next-icon" aria-hidden="true"  style="color: black;"></span></span>
                    </a>
                </div>
            </div>
            <div class="container mt-8">
                <button name="removerDocumento" id="removerDocumento" class="btn btn-primary mt-2">Remover da seleção</button>
            </div>
        </div>

    </div>
</div>
<script>
    $(document).ready(function() {
        $('#formCadDocumento #Nip').mask('00.0000.00');
    });
</script>