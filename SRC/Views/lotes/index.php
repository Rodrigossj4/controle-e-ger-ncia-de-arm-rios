<?php

/** @var Marinha\Mvc\Models\Lotes[] $LotesList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modCadLote">
            <h3>Cadastro de Lotes</h3>
            <form method="post" id="formCadLote" action="">
                <div class="form-group">
                    <label class="col-form-label" for="numero">Número do lote: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="numeroLote" id="numeroLote">
                </div><br>
                <div class="form-group">
                    <label class="col-form-label" for="documento[]">Selecione os arquivos</label>
                    <input type="file" id="documento[]" name="documento[]" class="form-control" multiple>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="btnCadLote" value="Cadastrar" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <span class="alerta"></span>
        </div>
    </div>
</div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>