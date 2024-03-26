<?php
if (!isset($_SESSION))
    session_start();

//var_dump($_SESSION['usuario'][0]["idacesso"])
$liberaAcessoAdmin = false;
if ((isset($_SESSION['usuario']) && ($_SESSION['usuario'][0]["idacesso"]) === 6))
    $liberaAcessoAdmin = true;

//echo  $liberaAcessoAdmin;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>SISTEMA DE DIGITALIZAÇÃO DE IMAGENS</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" >
    <script src="../../scripts/bootstrap.bundle.min.js"></script>


</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid" id="LinkInicio">
            <a class="navbar-brand" href="/home">Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <? /**php if (($liberaAcessoAdmin === true)) { **/ ?>
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
                        <a class="nav-link" href="/gerenciar-lotes">Gerencia de Lotes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gerenciar-perfis">Gerencia de Perfil de usuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gerenciar-usuarios">Gerencia de Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Modulo_img.php" target="_blank">Tratamento de Imagens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Sair</a>
                    </li>
                    <?/*php } */ ?>


                </ul>
            </div>
        </div>
    </nav>