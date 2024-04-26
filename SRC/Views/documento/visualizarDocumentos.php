<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $DocumentosList */
/** @var Marinha\Mvc\Models\Armarios[] $ArmariosList  */
//$TotalArquivos = count($listaArquivosCarregados);
$contador = 0;
?>
<?php require_once __DIR__ . "../../topo.php" ?>

<div class="container">
    <div class="tituloModulo">Visualizar Documentos</div>
    <div style="border: 1px solid;" class="row" id="modCadDocumento">
        <div class="col-md-6 order-md-1">
            <form method="post" id="formCadDocumento" action="/cadastrarDocumento" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-form-label" for="Armario">Armario: </label><br>
                    <input type="hidden" id="flagCadastro" name="flagCadastro" />
                    <select id="ListArmarioDocumento" name="ListArmarioDocumento" class="form-select">
                        <option value="0">Selecione um armário</option>
                        <?php foreach ($ArmariosList  as $armarios) : ?>
                            <option value="<?= $armarios['id']; ?>"><?= $armarios['nomeexterno']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="Nip">NIP: </label>
                    <input class="form-control form-control-sm form-control-padronizado" type="text" name="Nip" id="Nip">
                </div>
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label class="col-form-label" for="semestre">Semestre: </label>
                        <br>
                        <select id="semestre" name="semestre" class="form-select">
                            <option value="0">Selecione o semestre</option>
                            <option value="1">Primeiro semestre</option>
                            <option value="2">Segundo semestre</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="col-form-label" for="ano">Ano: </label>
                        <input class="form-control form-control-padronizado" type="number" name="ano" id="ano">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="TipoDoc">Tipo de documento: </label>
                    </br>
                    <select id="SelectTipoDoc" name="SelectTipoDoc" class="form-select">
                        <option value="0">Selecione o tipo de documento</option>
                    </select>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input type="button" id="btnCadDocumento" value="Indexar" class="btn btn-primary">
                    </div>
                    <div class="col-sm-3">
                        <input type="button" id="btnCadDocumento" value="Anexar" class="btn btn-primary">
                    </div>
                    <span class="alerta"></span>
                </div>
            </form>

            <hr class="mb-4">
            <div class="bg-body-tertiary rounded-3 row">
                <div class="col order-md-1">
                    <div class="container">
                        <div class="row">
                            <table class="table table-striped" id="listPaginas">
                                <thead>
                                    <tr>
                                        <th scope="col">Itens</th>
                                        <th scope="col">NIP</th>
                                        <th scope="col">Semestre</th>
                                        <th scope="col">Ano</th>
                                        <th scope="col">Tipo de documento</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 order-md-1" style="border: 1px solid;">
            <div class="container mt-6">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="0">
                    <div class="carousel-inner" id="listarDocumentos">

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Próximo</span>
                    </a>
                </div>
            </div>
            <div class="container">
                <button name="incluir" id="incluir" class="btn btn-primary">incluir</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>

<?php require_once __DIR__ . "../../rodape.php" ?>