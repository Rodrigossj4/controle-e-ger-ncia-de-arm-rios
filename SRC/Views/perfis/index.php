<?php

/** @var Marinha\Mvc\Models\PerfilAcesso[] $PerfilAcessoList */

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
                        <a class="nav-link" href="/gerenciar-perfis">Perfis de Usuários</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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
