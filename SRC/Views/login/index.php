<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modLogin">
            <h3>Login</h3>
            <form method="post" id="formLogin" action="">
                <div class="form-group">
                    <label class="col-form-label" for="nip">Nip: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="nip" id="nip">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="senha">Senha: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="password" name="senha" id="senha">
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

<?php require_once __DIR__ . "../../rodape.php" ?>