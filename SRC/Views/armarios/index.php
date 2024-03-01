<?php

/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>

<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modCadArmario">
            <h3>Cadastro de Armários</h3>
            <form method="post" id="formCadArmario" action="/cadastrarArmario">
                <div class="form-group">
                    <label class="col-form-label" for="codigo">Código: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="codigo" id="codigo">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="nomeInterno">Nome Interno: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="nomeInterno" id="nomeInterno">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="nomeExterno">Nome Externo: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="nomeExterno" id="nomeExterno">
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="btnCadArmario" value="Cadastrar" class="btn btn-primary">
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
            <h3>Gerenciamento de Armários</h3>
            <div class="Grade" id="gradeArmarios">
                <?php require_once "partial_listar_armarios.php" ?>
            </div>
        </div>
    </div>
</div>

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
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>