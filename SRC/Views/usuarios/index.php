<?php

/** @var Marinha\Mvc\Models\Usuarios[] $UsuariosList */

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>SISPAD - </title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/">Armarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/gerenciar-tipo-documentos">Tipo de documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gerenciar-documentos">Documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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
                        <input class="form-control form-control-sm form-control-padronizado" type="text"
                            name="idacesso" id="idacesso">
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
                                <button class="btn btn-warning btnAlterarUsuario" data-bs-toggle="modal" data-bs-target="#AlteraUsuario" data-id="<?= $usuario['id']; ?>" data-desc="<?= $usuario['desctipo']; ?>">Editar</button>
                                <form method="post" id="excluir<?= $usuario['id']; ?>" action="">
                                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?= $usuario['id']; ?>" >
                                    <button class="btn btn-danger excluirUsuario" data-bs-toggle="modal" data-bs-target="#modexcluirUsuario" data-id="<?= $usuario['id']; ?>" type="button">Excluir</button>
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
                            <label class="col-form-label">Código do armário: </label>
                            <input type="text" class="form-control form-control-sm" name="codigo" id="codigo">
                        </div>                        
                        <div class="form-group">
                            <label class="col-form-label">Nome Interno: </label>
                            <input type="text" class="form-control form-control-sm" id="nomeInterno"
                                name="nomeInterno">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Nome Externo: </label>
                            <input type="text" class="form-control form-control-sm" id="nomeExterno"
                                name="nomeExterno">
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
        
    <div class="modal fade" id="ExcluirUsuario" tabindex="-1" aria-labelledby="ExcluirUsuario" aria-hidden="true">
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
