<?php
if (!isset($_SESSION))
    session_start();

//var_dump($_SESSION['usuario'][0]["idacesso"])
$liberaAcessoAdmin = false;
if ((isset($_SESSION['usuario']) && ($_SESSION['usuario'][0]["idacesso"]) === 6))
    $liberaAcessoAdmin = true;

$listaArquivosCarregados = [];
if ((isset($_SESSION['Arquivos'])))
    $listaArquivosCarregados  = $_SESSION['Arquivos'];

//echo  $liberaAcessoAdmin;
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
            <li class="nav-item" id="menuAdmin">
                <a class="nav-link dropdown-toggle" href="#" id="menuAdmin" role="button">
                    Administração
                </a>
                <div id="submenu">
                    <a class="nav-link" href="/gerenciar-usuarios">Gerencia de Usuarios</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/gerenciar-armarios">Gerencia de Armarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/gerenciar-tipo-documentos">Gerencia de Tipo de documentos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/gerenciar-documentos">Gerencia de Documentos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/visualizar-documentos">Visualizar Documentos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/gerenciar-perfis">Gerencia de Perfil de usuario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/Modulo_img.php" target="_blank">Tratamento de Imagens</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Sair</a>
            </li>
        </ul>
    </nav>