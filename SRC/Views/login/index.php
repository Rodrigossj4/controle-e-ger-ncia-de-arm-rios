<?php

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
<div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col divisao_bottom form-control-padronizado" id="modLogin">
                <h3>Login</h3>
                <form method="post" id="formLogin" action="">
                    <div class="form-group">
                        <label class="col-form-label" for="nip">Nip: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="text" name="nip"
                            id="nip">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="senha">Senha: </label>
                        <input class="form-control form-control-sm form-control-padronizado" type="password"
                            name="senha" id="senha">
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" id="btnLogin" value="Login" class="btn btn-primary">
                        </div>
                    </div>
                </form>
                <span class="alerta"></span>
            </div>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
    crossorigin="anonymous"></script>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>