<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modAltSenha">
            <h3>Altere a sua senha</h3>
            <?php $_SERVER['REMOTE_ADDR']; ?>
            <form method="post" id="formAltSenha" action="">
                <div class="form-group">
                    <label class="col-form-label" for="senhaAtual">Digite o Nip: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="nip" id="nip">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="senhaAtual">Digite a senha Atual: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="senhaAtual" id="senhaAtual">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="novaSenha">Digite a nova senha: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="password" name="novaSenha" id="novaSenha">
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="confNovaSenha">confirme a senha </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="password" name="confNovaSenha" id="confNovaSenha">
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="alterarSenha" value="Alterar senha" class="btn btn-primary">
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

<script>
    $(document).ready(function() {
        $('#formLogin #nip').mask('00.0000.00');
    });
</script>