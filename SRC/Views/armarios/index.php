<?php

/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList */

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
            <div class="col divisao_bottom form-control-padronizado" id="modCadArmario">
                <h3>Cadastro de Armários</h3>
                <form method="post" id="formCadArmario" action="/cadastrarArmario">
                    <div class="form-group">
                        <label class="col-form-label" for="codigo">Código: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="codigo"
                            id="codigo">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="nomeInterno">Nome Interno: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text"
                            name="nomeInterno" id="nomeInterno">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="nomeExterno">Nome Externo: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text"
                            name="nomeExterno" id="nomeExterno">
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
                <div class="Armarios" id="gradeArmarios">                    
                    <?php foreach ($ArmariosList  as $video): ?>                    
                        <div class="container_item">
                            <div class="Descricao_Armario">
                                <?= $video['nomeexterno']; ?>
                            </div>
                            <div class="acoes-armarios">
                                <button class="btn btn-primary">Listar Documentos</button>
                                <button class="btn btn-warning btnAlterarArmario" data-bs-toggle="modal" data-bs-target="#AlteraArmario" data-id="<?= $video['id']; ?>" data-ni="<?= $video['nomeinterno']; ?>" data-ne="<?= $video['nomeexterno']; ?>" data-cd="<?= $video['codigo']; ?>">Editar</button>
                                <form method="post" id="excluir<?= $video['id']; ?>" action="">
                                    <input type="hidden" id="idArmario" name="idArmario" value="<?= $video['id']; ?>" >
                                    <button class="btn btn-danger excluir" data-id="<?= $video['id']; ?>" data-bs-toggle="modal" data-bs-target="#ExcluirArmario" type="button">Excluir</button>
                                </form>
                            </div>
                        </div>                 
                    <?php endforeach; ?>                    
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
                                <input type="button" class="btn btn-primary" id="exibConfirmaAlteracaoArmario"
                                    value="Alterar">
                            </div>
                        </div> 
                        <div class="form-group row opcoesConfirmacao">
                            <span>Deseja realmente alterar essas informações?</span>
                            <div class="col-sm-3">
                                <input type="button" id="btnConfirmaAlteracaoArmario" value="Sim"
                                    class="btn btn-success">
                            </div>
                            <div class="col-sm-3">
                                <input type="button" id="btnNaoConfirmaAlteracaoArmario" value="Não"
                                    class="btn btn-danger">
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
                            <input type="button" id="btnConfirmaExcluirArmario" data-id="" value="Sim"
                                class="btn btn-success btnConfirmaExcluirArmario">
                        </div>
                        <div class="col-sm-3">
                            <input type="button" id="btnNaoConfirmaExcluirArmario" data-id="" value="Não"
                                class="btn btn-danger btnNaoConfirmaExcluirArmario">
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
