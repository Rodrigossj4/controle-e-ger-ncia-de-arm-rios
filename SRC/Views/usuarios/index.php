<?php

/** @var Marinha\Mvc\Models\Usuarios[] $UsuariosList */
/** @var Marinha\Mvc\Models\PerfilAcesso[] $PerfilAcessoList */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
    <div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col divisao_bottom form-control-padronizado" id="modCadUsuario">
                <h3>Cadastro de Usuários</h3>
                <form method="post" id="formCadUsuario" action="/cadastrarUsuario">
                    <div class="form-group">
                        <label class="col-form-label" for="nomeusuario">Nome do usuário: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="nomeusuario"
                            id="nomeusuario">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="nip">NIP: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text"
                            name="nip" id="nip">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="senhausuario">Senha do usuario: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text"
                            name="senhausuario" id="senhausuario">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="idacesso">Perfil de acesso: </label>
                        <select name="idacesso" id="idacesso" class="form-select">
                            <option value="0">Escolha o perfil de acesso</option>
                        <?php foreach ($PerfilAcessoList  as $perfil): ?> 
                            <option value="<?= $perfil['id']; ?>"><?= $perfil['nomeperfil']; ?></option>
                        <?php endforeach; ?>  
                        </select>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" id="btnCadUsuario" value="Cadastrar" class="btn btn-primary">
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
                <h3>Gerenciamento de Usuario</h3>
                <div class="Grade" id="gradeUsuario">                    
                <?php foreach ($UsuariosList  as $usuario): ?>                    
                        <div class="container_item">
                            <div class="Descricao">
                                <?= $usuario['nomeusuario']; ?>
                            </div>
                            <div class="acoes">
                                <button class="btn btn-warning btnAlterarUsuario" data-bs-toggle="modal" data-bs-target="#AlteraUsuario" data-id="<?= $usuario['codusuario']; ?>" data-desc="<?= $usuario['nomeusuario']; ?>">Editar</button>
                                <form method="post" id="excluir<?= $usuario['codusuario']; ?>" action="">
                                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?= $usuario['codusuario']; ?>" >
                                    <button class="btn btn-danger excluirUsuario" data-bs-toggle="modal" data-bs-target="#modexcluirUsuario" data-id="<?= $usuario['codusuario']; ?>" type="button">Excluir</button>
                                </form>
                            </div>
                        </div>                 
                    <?php endforeach; ?>               
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
                            <input type="hidden" name="id" id="id">
                            <label class="col-form-label">Nome do usuário: </label>
                            <input type="text" class="form-control form-control-sm" name="nomeusuario" id="nomeusuario">
                        </div>                        
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoUsuario"
                                    value="Alterar">
                            </div>
                        </div> 
                        <div class="form-group row opcoesConfirmacao">
                            <span>Deseja realmente alterar essas informações?</span>
                            <div class="col-sm-3">
                                <input type="button" id="btnConfirmaAlteracaoUsuario" value="Sim"
                                    class="btn btn-success">
                            </div>
                            <div class="col-sm-3">
                                <input type="button" id="btnNaoConfirmaAlteracaoUsuario" value="Não"
                                    class="btn btn-danger">
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
                            <input type="button" id="btnConfirmaExcluirUsuario" data-id="" value="Sim"
                                class="btn btn-success btnConfirmaExcluirUsuario">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaExcluirUsuario" data-id="" value="Não"
                                class="btn btn-danger btnNaoConfirmaExcluirUsuario">
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
