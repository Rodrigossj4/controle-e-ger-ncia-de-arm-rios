<?php
if (!isset($_SESSION))
    session_start();

//var_dump($_SESSION['usuario'][0]["idacesso"])
$liberaAcesso = 3;
$nomeUsuario = "";
$nipUsuario = "";
if ((isset($_SESSION['usuario'])))
    $liberaAcesso = $_SESSION['usuario'][0]["nivelAcesso"];

if (isset($_SESSION['usuario'])) {
    $nomeUsuario = $_SESSION['usuario'][0]["nomeusuario"];
    $nipUsuario = $_SESSION['usuario'][0]["nip"];
    $OMUsuario =  $_SESSION['usuario'][0]["omusuario"];
}

$listaArquivosCarregados = [];
if ((isset($_SESSION['Arquivos'])))
    $listaArquivosCarregados  = $_SESSION['Arquivos'];

//echo  $liberaAcesso;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>SISTEMA DE DIGITALIZAÇÃO DE IMAGENS</title>

    <link href="/../../css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <script src="../../scripts/bootstrap.bundle.min.js"></script>
    <link href="/../../css/style.css" type="text/css" rel="stylesheet" />

</head>

<body>
    <nav class="menu">
        <input type="checkbox" class="menu-faketrigger" />
        <div class="menu-lines">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul>
            <?php if ($liberaAcesso == 1) { ?>
                <li class="nav-item" id="menuAdmin">
                    <a class="nav-link dropdown-toggle" href="#" id="menuAdmin" role="button">
                        Administração
                    </a>
                    <div id="submenu">
                        <a class="nav-link" href="/gerenciar-usuarios">Gerência de Usuários</a>
                        <a class="nav-link" href="/gerenciar-perfis">Gerência de Perfil de usuário</a>
                        <a class="nav-link" href="/gerenciar-om">Gerência OM</a>
                        <a class="nav-link" href="/auditoria">Auditoria</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/gerenciar-armarios">Gerência de Armários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/gerenciar-tipo-documentos">Gerência de Tipo de documentos</a>
                </li>
            <?php } ?>
            <?php if ($liberaAcesso < 3) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/gerenciar-documentos">Gerência de Documentos</a>
                </li>
            <?php } ?>
            <?php if ($liberaAcesso <= 3) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/visualizar-documentos">Visualizar Documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Modulo_img.php" target="_blank">Tratamento de Imagens</a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Sair</a>
            </li>
        </ul>
    </nav>