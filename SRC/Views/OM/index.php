<div?php ?>
    <?php require_once __DIR__ . "../../topo.php" ?>
    <div class="container">
        <div class="container">
            <div class="bg-body-tertiary rounded-3 row">
                <div class="col-md-8 order-md-1" id="modCadOM">
                    <div class="tituloModulo">Cadastro de OM</div>
                    <form method="post" id="formCadOM" action="/cadastrarOM">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label" for="codOM">Código OM: </label>
                                <input class="form-control form-control-sm" type="text" name="codOM" id="codOM">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label" for="sigla">Sigla: </label>
                                <input class="form-control form-control-sm" type="text" name="sigla" id="sigla">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label" for="nomeOM">Nome OM: </label>
                                <input class="form-control form-control-sm" type="text" name="nomeOM" id="nomeOM">
                            </div>
                            <br>
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <input type="button" id="btnCadOM" value="Cadastrar" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                    <span class="alerta"></span>
                </div>
            </div>
            <hr class="mb-4">
        </div>

    </div>
    </body>

    </html>

    <?php require_once __DIR__ . "../../rodape.php" ?>