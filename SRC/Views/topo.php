<?php
    if (!isset($_SESSION))
    session_start();

    //var_dump($_SESSION['usuario'][0]["idacesso"])
    $liberaAcessoAdmin = false;
    if($_SESSION['usuario'][0]["idacesso"] === 6)
        $liberaAcessoAdmin = true;

        //echo $_SESSION['usuario'][0]["codusuario"];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>SISTEMA DE DIGITALIZAÇÃO DE IMAGENS</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid" id="LinkInicio">
            <a class="navbar-brand" href="/home">Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if($liberaAcessoAdmin){?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/gerenciar-armarios">Gerencia de Armarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="/gerenciar-tipo-documentos">Gerencia de Tipo de documentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gerenciar-documentos">Gerencia de Documentos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gerenciar-perfis">Gerencia de Perfil de usuario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gerenciar-usuarios">Gerencia de Usuarios</a>
                        </li>
                    <?php }?>
                    <li class="nav-item">
                        <a class="nav-link" href="/gerenciar-documentos">Consulta de Documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Sair</a>
                    </li>   
                </ul>
            </div>
        </div>
    </nav>