<?php

/** @var Marinha\Mvc\Models\Usuarios[] $UsuariosList */
/** @var Marinha\Mvc\Models\PerfilAcesso[] $PerfilAcessoList */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-6 row">
        <div class="col-md-8 order-md-1 form-control-padronizado" id="modCadUsuario">
            <div class="tituloModulo">Gerenciar usuários</div>
            <form method="post" id="formCadUsuario" name="formCadUsuario" action="/cadastrarUsuario">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="nomeusuario">Nome do usuário: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="nomeusuario" id="nomeusuario">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="nip">NIP: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="nip" id="nip">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="senhausuario">Senha do usuario: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="password" name="senhausuario" id="senhausuario">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="idacesso">Perfil de acesso: </label>
                        <select name="idacesso" id="idacesso" class="form-select">
                            <option value="0">Escolha o perfil de acesso</option>
                            <?php foreach ($PerfilAcessoList  as $perfil) : ?>
                                <option value="<?= $perfil['id']; ?>"><?= $perfil['nomeperfil']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="btnCadUsuario" value="Cadastrar" class="btn btn-primary">
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
            <h3>Gerenciamento de Usuario</h3>
            <div class="container">
                <div class="row">
                    <table class="table table-striped" id="listPaginas">
                        <thead>
                            <tr>
                                <th scope="col">Nome do Usuário</th>
                                <th scope="col" colspan="2" style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody id='gradeUsuario'>
                            <?php foreach ($UsuariosList  as $usuario) : ?>
                                <tr>
                                    <td><?= $usuario['nomeusuario']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btnAlterarUsuario" data-bs-toggle="modal" data-bs-target="#AlteraUsuario" data-id="<?= $usuario['codusuario']; ?>" data-desc="<?= $usuario['nomeusuario']; ?>">Editar</button>
                                    </td>
                                    <td>
                                        <form method="post" id="excluir<?= $usuario['codusuario']; ?>" name="formAltUsuario" id="formAltUsuario" action="">
                                            <input type="hidden" name="idUsuario" value="<?= $usuario['codusuario']; ?>">
                                            <button class="btn btn-danger excluirUsuario" data-bs-toggle="modal" data-bs-target="#modexcluirUsuario" data-id="<?= $usuario['codusuario']; ?>" type="button">Excluir</button>
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

<div class="modal fade" id="AlteraUsuario" tabindex="-1" aria-labelledby="AlteraUsuario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar dados do Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formAltUsuario" action="/alterarUsuario">
                    <div class="form-group">
                        <input type="hidden" name="id" id="idAlt">
                        <label class="col-form-label">Nome do usuário: </label>
                        <input type="text" class="form-control form-control-sm" id="nomeusuarioAlt" name="nomeusuario">
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoUsuario" value="Alterar">
                        </div>
                    </div>
                    <div class="form-group row opcoesConfirmacao">
                        <span>Deseja realmente alterar essas informações?</span>
                        <div class="col-sm-3">
                            <input type="button" id="btnConfirmaAlteracaoUsuario" value="Sim" class="btn btn-success">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaAlteracaoUsuario" value="Não" class="btn btn-danger">
                        </div>
                    </div>
                </form>
                <span class="alerta"></span>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modexcluirUsuario" tabindex="-1" aria-labelledby="modexcluirUsuario" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <span>Deseja realmente excluir esse Usuario?</span>
                    <div class="col-sm-3">
                        <form id="formExcluirUsuario">
                            <input type="hidden" name="id" id="id">
                        </form>
                        <input type="button" id="btnConfirmaExcluirUsuario" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirUsuario">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnNaoConfirmaExcluirUsuario" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirUsuario">
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

<script>
    $(document).ready(function() {
        $('#formCadUsuario #nip').mask('00.0000.00');
    });
</script>