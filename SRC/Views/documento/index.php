<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $DocumentosList */
/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList  */

?>
<?php require_once __DIR__ . "../../topo.php" ?>

<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modCadDocumento">
            <h3>Gerenciar Documentos</h3>
            <form method="post" id="formCadDocumento" action="/cadastrarDocumento" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-form-label" for="Armario">Armario: </label><br>
                    <input type="hidden" id="flagCadastro" name="flagCadastro" />
                    <select id="ListArmarioDocumento" name="ListArmarioDocumento" class="form-select">
                        <option value="0">Selecione um armário</option>
                        <?php foreach ($ArmariosList  as $armarios) : ?>
                            <option value="<?= $armarios['id']; ?>"><?= $armarios['nomeexterno']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="Nip">NIP: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="Nip" id="Nip">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="TipoDoc">Tipo de documento: </label>
                    </br>
                    <select id="SelectTipoDoc" name="SelectTipoDoc" class="form-select">
                        <option value="0">Selecione o tipo de documento</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="col-form-label" for="semestre">Semestre: </label>
                    <br>
                    <select id="semestre" name="semestre" class="form-select">
                        <option value="0">Selecione o semestre</option>
                        <option value="1">Primeiro semestre</option>
                        <option value="2">Segundo semestre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="ano">Ano: </label>
                    <input class="form-control form-control-padronizado" type="number" name="ano" id="ano">
                </div>
                <br>
                <div class="form-group row">
                    <!-- <div class="col-sm-3">
                        <input type="button" id="btnBuscarDocumento" value="Buscar documentos" class="btn btn-primary">
                    </div>-->
                    <div class="col-sm-3">
                        <input type="button" id="btnCadDocumento" value="Cadastrar" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <span class="alerta"></span>
        </div>
    </div>
</div>

<div class="Container" style="border:1px ">
    <div class="p-5 bg-body-tertiary rounded-3 row">
        <div class="col">
            <h3>Gerenciamento de documentos</h3>
            <div class="Grade_maior">
                <div class="container_item_maior">
                    <div class="Descricao_maior">
                        NIP
                    </div>
                    <div class="Descricao_maior">
                        Semestre
                    </div>
                    <div class="Descricao_maior">
                        Ano
                    </div>
                    <div class="Descricao_maior">
                        Tipo de documento
                    </div>
                    <div class="Descricao_maior">
                        Armário
                    </div>
                    <div class="Descricao_maior">
                    </div>
                    <!--<div class="Descricao">
                            Ações
                        </div>-->
                </div>
                <div id="documentosLista">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="CadPagina" tabindex="-1" aria-labelledby="CadPagina" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastra Página</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formCadPagina" action="/cadastrarPagina">
                    <div class="form-group">
                        <input type="hidden" name="docid" id="docid">
                        <label class="col-form-label">Volume: </label>
                        <input type="text" class="form-control form-control-sm" name="volume" id="volume">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Número da página: </label>
                        <input type="text" class="form-control form-control-sm" name="numpagina" id="numpagina">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Arquivo: </label>
                        <input type="number" class="form-control form-control-sm" name="arquivo" id="arquivo">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">CODEXP: </label>
                        <input type="number" class="form-control form-control-sm" name="codexp" id="codexp">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Filme: </label>
                        <input type="number" class="form-control form-control-sm" name="filme" id="filme">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Fotograma: </label>
                        <input type="number" class="form-control form-control-sm" name="fotograma" id="fotograma">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Imagem Encontrada: </label>
                        <input type="number" class="form-control form-control-sm" name="imgencontrada" id="imgencontrada">
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" id="btnConfirmaCadPagina" value="Cadastrar Página">
                        </div>
                    </div>
                    <!--<div class="form-group row opcoesConfirmacao">
                            <span>Deseja realmente alterar essas informações?</span>
                            <div class="col-sm-3">
                                <input type="button" id="btnConfirmaAlteracaoProduto" value="Sim"
                                    class="btn btn-success">
                            </div>
                            <div class="col-sm-3">
                                <input type="button" id="btnNaoConfirmaAlteracaoProduto" value="Não"
                                    class="btn btn-danger">
                            </div>
                        </div>-->
                </form>
                <span class="alerta"></span>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="AlteraDocumento" tabindex="-1" aria-labelledby="AlteraDocumento" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formAltDocumento" action="/alterarDocumento">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <label class="col-form-label">Id do documento: </label>
                        <input type="text" class="form-control form-control-sm" name="docId" id="docId">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">NIP: </label>
                        <input type="text" class="form-control form-control-sm" name="nip" id="nip">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">semestre: </label>
                        <input type="number" class="form-control form-control-sm" name="semestre" id="semestre">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Ano: </label>
                        <input type="number" class="form-control form-control-sm" name="ano" id="ano">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Tipo do documento: </label>
                        <input type="number" class="form-control form-control-sm" name="tipodocumento" id="tipodocumento">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">FolderId: </label>
                        <input type="number" class="form-control form-control-sm" name="folderid" id="folderid">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Armario: </label>
                        <input type="number" class="form-control form-control-sm" name="armario" id="armario">
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" id="btnConfirmaAlteracaoDocumento" value="Alterar">
                        </div>
                    </div>
                    <!--<div class="form-group row opcoesConfirmacao">
                            <span>Deseja realmente alterar essas informações?</span>
                            <div class="col-sm-3">
                                <input type="button" id="btnConfirmaAlteracaoProduto" value="Sim"
                                    class="btn btn-success">
                            </div>
                            <div class="col-sm-3">
                                <input type="button" id="btnNaoConfirmaAlteracaoProduto" value="Não"
                                    class="btn btn-danger">
                            </div>
                        </div>-->
                </form>
                <span class="alerta"></span>
            </div>

        </div>
    </div>
</div>
</body>

</html>


<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>