<?php

/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<main>
    <div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col-md-8 order-md-1" id="modCadArmario">
                <div class="tituloModulo">Cadastro de Armários</div>
                <form method="post" id="formCadArmario" action="/cadastrarArmario">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="codigo">Código: </label>
                            <input class="form-control form-control-sm" type="text" name="codigo" id="codigo">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="nomeInterno">Nome Interno: </label>
                            <input class="form-control form-control-sm" type="text" name="nomeInterno" id="nomeInterno">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="nomeExterno">Nome Externo: </label>
                            <input class="form-control form-control-sm" type="text" name="nomeExterno" id="nomeExterno">
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <input type="button" id="btnCadArmario" value="Cadastrar" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <span class="alerta"></span>
                    </div>
                </form>
            </div>
        </div>
        <hr class="mb-4">
    </div>

    <div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col-md-8 order-md-1 form-control-padronizado">
                <h3>Gerenciamento de Armários</h3>
                <div class="container">
                    <div class="row">
                        <table class="table table-striped" id="listPaginas">
                            <thead>
                                <tr>
                                    <th scope="col">Nome do Armário</th>
                                    <th scope="col" colspan="4" style="text-align: center;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id='gradeArmarios'>
                                <?php require_once "partial_listar_armarios.php" ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modal fade" id="AlteraArmario" tabindex="-1" aria-labelledby="AlteraArmario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar dados do produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formAltArmario" action="/alterarArmario">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <label class="col-form-label">Código do armário: </label>
                        <input type="text" class="form-control form-control-sm" name="codigo" id="codigo">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nome Interno: </label>
                        <input type="text" class="form-control form-control-sm" id="nomeInterno" name="nomeInterno">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Nome Externo: </label>
                        <input type="text" class="form-control form-control-sm" id="nomeExterno" name="nomeExterno">
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoArmario" value="Alterar">
                        </div>
                    </div>
                    <div class="form-group row opcoesConfirmacao">
                        <hr class="linha-alert">
                        <span>Deseja realmente alterar essas informações?</span>
                        <div class="col-sm-3">
                            <input type="button" id="btnConfirmaAlteracaoArmario" value="Sim" class="btn btn-success">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaAlteracaoArmario" value="Não" class="btn btn-danger">
                        </div>
                    </div>
                </form>
                <span class="alerta"></span>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="GerenciarArmario" tabindex="-1" aria-labelledby="GerenciarArmario" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Gerenciar Tipo de documentos do armário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formListaDocumentos" name="formListaDocumentos" class="">
                    <input type="hidden" name="IdArmario" id="IdArmario">
                    <select class="form-select" id="listarDocumentos" name="listarDocumentos">
                        <option value="0">Selecione o documento</option>
                    </select>
                    <input type="button" name="vincArmarioTipoDoc" id="vincArmarioTipoDoc" class="vincArmarioTipoDoc btn btn-primary" value="Vincular tipo de documento">
                </form>
                <hr class="mb-4">
                <div class="container">
                    <div>
                        <div class="col-md-12 order-md-1 form-control-padronizado">
                            <h5>Tipos de documentos do armario:</h5>
                            <div class="container">
                                <div class="row">
                                    <table class="table table-striped" id="listPaginas">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nome do Armário</th>
                                                <th scope="col" style="text-align: center;">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id='GradeTipoDocArmario'>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="alerta"></span>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="ExcluirArmario" tabindex="-1" aria-labelledby="ExcluirArmario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente excluir esse Armario?</span>
                    <div class="col-sm-3">
                        <form id="formExcluirArmario">
                            <input type="hidden" name="id" id="id">
                        </form>
                        <input type="button" id="btnConfirmaExcluirArmario" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirArmario">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaExcluirArmario" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirArmario">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ConfDesArmTipoDoc" tabindex="-1" aria-labelledby="ConfDesArmTipoDoc" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente excluir esse tipo de documento do Armario?</span>
                    <div class="col-sm-3">
                        <form id="formDesArmTipoDoc">
                            <input type="hidden" name="idTipoDoc" id="idTipoDoc" value="">
                            <input type="hidden" name="idArmario" id="idArmario" value="">
                        </form>
                        <input type="button" id="btnConfirmaDesArmTipoDoc" data-id="" value="Sim" class="btn btn-success btnConfirmaDesArmTipoDoc">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaExcluirArmario" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirArmario">
                    </div>
                </div>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</div>
</body>

</html>

<?php require_once __DIR__ . "../../rodape.php" ?>