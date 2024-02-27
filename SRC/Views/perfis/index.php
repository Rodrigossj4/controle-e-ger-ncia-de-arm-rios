<?php

/** @var Marinha\Mvc\Models\PerfilAcesso[] $PerfilAcessoList */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
    <div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col divisao_bottom form-control-padronizado" id="modCadPerfil">
                <h3>Cadastro de Perfil</h3>
                <form method="post" id="formCadPerfil" action="">
                    <div class="form-group">
                        <label class="col-form-label" for="nomePerfil">Nome do Perfil: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="nomePerfil"
                            id="nomePerfil">
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" id="btnCadPerfil" value="Cadastrar" class="btn btn-primary">
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
                <h3>Gerenciamento de Perfil</h3>
                <div class="Grade" id="gradeListaPerfil">
                    <?php foreach ($PerfilAcessoList  as $perfil): ?>                    
                        <div class="container_item">
                            <div class="Descricao">
                                <?= $perfil['nomeperfil']; ?>
                            </div>
                            <div class="acoes">
                                <button class="btn btn-warning btnAlterarPerfil" data-bs-toggle="modal" data-bs-target="#AlteraPerfil" data-id="<?= $perfil['id']; ?>" data-desc="<?= $perfil['nomeperfil']; ?>">Editar</button>
                                <form method="post" id="excluir<?= $perfil['id']; ?>" action="">
                                    <input type="hidden" id="idPerfil" name="idPerfil" value="<?= $perfil['id']; ?>" >
                                    <button class="btn btn-danger excluirPerfil" data-bs-toggle="modal" data-bs-target="#modexcluirPerfil" data-id="<?= $perfil['id']; ?>" type="button">Excluir</button>
                                </form>
                            </div>
                        </div>                 
                    <?php endforeach; ?>                   
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AlteraPerfil" tabindex="-1" aria-labelledby="AlteraPerfil" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar dados do perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formAltPerfil" name="formAltPerfil" action="">
                        <div class="form-group">
                            <input type="hidden" name="id" id="id">
                            <label class="col-form-label">Nome do Perfil: </label>
                            <input type="text" class="form-control form-control-sm" name="nomeperfil" id="nomeperfil">
                        </div>                    
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoPerfil"
                                    value="Alterar">
                            </div>
                        </div> 
                        <div class="form-group row opcoesConfirmacao">
                            <span>Deseja realmente alterar essas informações?</span>
                            <div class="col-sm-3">
                                <input type="button" id="btnConfirmaAlteracaoPerfil" value="Sim"
                                    class="btn btn-success">
                            </div>
                            <div class="col-sm-3">
                                <input type="button" id="btnNaoConfirmaAlteracaoPerfil" value="Não"
                                    class="btn btn-danger">
                            </div>
                        </div>
                    </form>
                    <span class="alerta"></span>
                </div>

            </div>
        </div>
    </div>
        
    <div class="modal fade" id="modexcluirPerfil" tabindex="-1" aria-labelledby="modexcluirPerfil" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <span>Deseja realmente excluir esse Perfil?</span>
                        <div class="col-sm-3">
                            <form id="formExcluirPerfil">
                                <input type="hidden" name="id" id="id">
                            </form>
                            <input type="button" id="btnConfirmaExcluirPerfil" data-id="" value="Sim"
                                class="btn btn-success btnConfirmaExcluirPerfil">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaExcluirPerfil" data-id="" value="Não"
                                class="btn btn-danger btnNaoConfirmaExcluirPerfil">
                        </div>
                    </div>
                    <span class="alerta"></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
    crossorigin="anonymous"></script>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>
