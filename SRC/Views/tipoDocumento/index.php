<?php

/** @var Marinha\Mvc\Models\TipoDocumento[] $TipoDocumentoList */
/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList  */
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modCadTipoDocumento">
            <h3>Cadastro de Tipos de documento</h3>
            <form method="post" id="formCadTipoDocumento" action="/cadastrarTipoDocumento">
                <div class="form-group">
                    <label class="col-form-label" for="desctipo">Descrição do tipo de documento: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="desctipo" id="desctipo">
                </div><br>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="btnCadTipoDoc" value="Cadastrar" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <span class="alerta"></span>
        </div>
    </div>
</div>

<div class="Container">
    <div class="p-5 bg-body-tertiary rounded-3 row">
        <div class="col">
            <h3>Gerenciamento de Tipo de documentos</h3>
            <div class="Grade" id="gradeListaDocumentos">
                <?php foreach ($TipoDocumentoList  as $tipoDoc) : ?>
                    <div class="container_item">
                        <div class="Descricao">
                            <?= $tipoDoc['desctipo']; ?>
                        </div>
                        <div class="acoes">
                            <button class="btn btn-warning btnAlterarTipoDoc" data-bs-toggle="modal" data-bs-target="#AlteraTipoDoc" data-id="<?= $tipoDoc['id']; ?>" data-desc="<?= $tipoDoc['desctipo']; ?>">Editar</button>
                            <form method="post" id="excluir<?= $tipoDoc['id']; ?>" action="">
                                <input type="hidden" id="idTipoDoc" name="idTipoDoc" value="<?= $tipoDoc['id']; ?>">
                                <button class="btn btn-danger excluirTipoDoc" data-bs-toggle="modal" data-bs-target="#modexcluirTipoDoc" data-id="<?= $tipoDoc['id']; ?>" type="button">Excluir</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AlteraTipoDoc" tabindex="-1" aria-labelledby="AlteraTipoDoc" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Tipo documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formAltTipoDoc" action="/alterarTipoDoc">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <label class="col-form-label">Descrição tipo de documento: </label>
                        <input type="text" class="form-control form-control-sm" name="descTipoDoc" id="descTipoDoc">
                    </div>

                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoDocumento" value="Alterar">
                        </div>
                    </div>
                    <div class="form-group row opcoesConfirmacao">
                        <span>Deseja realmente alterar essas informações?</span>
                        <div class="col-sm-3">
                            <input type="button" id="btnConfirmaAlteracaoTipoDocumento" value="Sim" class="btn btn-success">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaAlteracaoTipoDocumento" value="Não" class="btn btn-danger">
                        </div>
                    </div>
                </form>
                <span class="alerta"></span>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modexcluirTipoDoc" tabindex="-1" aria-labelledby="modexcluirTipoDoc" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente excluir esse tipo de documento?</span>
                    <div class="col-sm-3">
                        <form id="formExcluirTipoDoc">
                            <input type="hidden" name="id" id="id">
                        </form>
                        <input type="button" id="btnConfirmaExcluirTipoDoc" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirTipoDoc">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaExcluirTipoDoc" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirTipoDoc">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>
</body>

</html>

<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>