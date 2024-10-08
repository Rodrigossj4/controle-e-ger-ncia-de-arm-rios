<?php

/** @var Marinha\Mvc\Models\PerfilAcesso[] $PerfilAcessoList */
/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col-md-8 order-md-1 form-control-padronizado" id="modCadPerfil">
            <div class="tituloModulo">Gerenciar Perfil de usuários</div>
            <form method="post" id="formCadPerfil" action="">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="col-form-label" for="nomePerfil">Nome do Perfil: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="nomePerfil" id="nomePerfil">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="col-form-label" for="Autor">Selecione o Armário </label>
                        <select name="armarios[]" id="armarios" class="form-select" multiple>
                            <?php foreach ($ArmariosList  as $armario) : ?>
                                <option value="<?= $armario['id']; ?>"><?= $armario['nomeexterno']; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="col-form-label" for="Autor">Nível de acesso </label>
                        <select name="nivelAcesso" id="nivelAcesso" class="form-select">
                            <option value="1">Administrador</option>
                            <option value="2">Operador</option>
                            <option value="3">Visualizador</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" id="btnCadPerfil" value="Cadastrar" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
            <span class="alerta"></span>
        </div>
    </div>
    <hr class="mb-4">
</div>

<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col-md-8 order-md-1 form-control-padronizado">
            <h3>Gerenciamento de Perfil</h3>
            <div class="container">
                <div class="row">
                    <table class="table table-striped" id="listPaginas">
                        <thead>
                            <tr>
                                <th scope="col">Nome do Perfil</th>
                                <th scope="col" colspan="2" style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody id='gradeListaPerfil'>
                            <?php foreach ($PerfilAcessoList  as $perfil) : ?>
                                <tr>
                                    <td><?= $perfil['nomeperfil']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btnAlterarPerfil" data-bs-toggle="modal" data-bs-target="#AlteraPerfil" data-id="<?= $perfil['id']; ?>" data-desc="<?= $perfil['nomeperfil']; ?>">Editar</button>
                                    </td>
                                    <td>
                                        <form method="post" id="excluir<?= $perfil['id']; ?>" action="">
                                            <input type="hidden" id="idPerfil" name="idPerfil" value="<?= $perfil['id']; ?>">
                                            <button class="btn btn-danger excluirPerfil" data-bs-toggle="modal" data-bs-target="#modexcluirPerfil" data-id="<?= $perfil['id']; ?>" type="button">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                        <input type="hidden" class="form-control form-control-sm" name="nomeperfilOriginal" id="nomeperfilOriginal">
                        <input type="text" class="form-control form-control-sm" name="nomeperfil" id="nomeperfil">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="col-form-label" for="armariosAlt">Selecione o Armário </label>
                        <select name="armariosAlt[]" id="armariosAlt" class="form-select" multiple>
                            <?php foreach ($ArmariosList  as $armario) : ?>
                                <option value="<?= $armario['id']; ?>"><?= $armario['nomeexterno']; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="col-form-label" for="nivelAcessoAlt">Nível de acesso </label>
                        <select name="nivelAcessoAlt" id="nivelAcessoAlt" class="form-select">
                            <option value="1">Administrador</option>
                            <option value="2">Operador</option>
                            <option value="3">Visualizador</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoPerfil" value="Alterar">
                        </div>
                    </div>
                    <div class="form-group row opcoesConfirmacao">
                        <hr class="linha-alert">
                        <span>Deseja realmente alterar essas informações?</span>
                        <div class="col-sm-3">
                            <input type="button" id="btnConfirmaAlteracaoPerfil" value="Sim" class="btn btn-success">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaAlteracaoPerfil" value="Não" class="btn btn-danger">
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
                        <input type="button" id="btnConfirmaExcluirPerfil" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirPerfil">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaExcluirPerfil" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirPerfil">
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