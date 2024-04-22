<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $DocumentosList */
/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList  */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div style="border: 1px solid;" class="row" id="modCadDocumento">
        <div class="col-md-4 order-md-1">
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
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label class="col-form-label" for="semestre">Semestre: </label>
                        <br>
                        <select id="semestre" name="semestre" class="form-select">
                            <option value="0">Selecione o semestre</option>
                            <option value="1">Primeiro semestre</option>
                            <option value="2">Segundo semestre</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="ano">Ano: </label>
                        <input class="form-control form-control-padronizado" type="number" name="ano" id="ano">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="TipoDoc">Tipo de documento: </label>
                    </br>
                    <select id="SelectTipoDoc" name="SelectTipoDoc" class="form-select">
                        <option value="0">Selecione o tipo de documento</option>
                    </select>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="btnCadDocumento" value="Indexar" class="btn btn-primary">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnCadDocumento" value="Anexar" class="btn btn-primary">
                    </div>
                    <span class="alerta"></span>
                </div>
            </form>

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
        <div class="col-md-8 order-md-1" style="border-left: 1px solid;">
            lado 2
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