<?php

/** @var Marinha\Mvc\Models\LogOperacoes[] $LogList */
/** @var Marinha\Mvc\Models\Operacoes[] $OperacoesList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<main>
    <div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col-md-8 order-md-1" id="modBuscarLog">
                <div class="tituloModulo">Relatório de atividades do sistema</div>
                <form method="post" id="formBuscaLog" name="formBuscaLog" action="/buscarLog">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="operacao">Operação: </label>
                            <select name="operacao" id="operacao" class="form-select">
                                <?php foreach ($OperacoesList  as $op) : ?>
                                    <option value="<?= $op['codoperacao']; ?>"><?= $op['descoperacao'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="nip"> </label>
                            <input class="form-control form-control-sm form-control-padronizado" type="hidden" name="nip" id="nip">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="data"> </label>
                            <input class="form-control form-control-sm form-control-padronizado" type="hidden" name="data" id="data">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="col-form-label" for="ip">IP: </label>
                            <input class="form-control form-control-sm form-control-padronizado" type="text" name="ip" id="ip">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <input type="button" id="btnBuscarLog" value="Buscar" class="btn btn-primary">
                        </div>
                    </div>
                </form>
                <table class="table table-striped" id="listPaginas">
                    <thead>
                        <tr>
                            <th scope="col">Operação</th>
                            <th scope="col">Data Hora</th>
                            <th scope="col">Nip Usuario</th>
                            <th scope="col">Identificador do documento</th>
                            <th scope="col">Ip</th>
                        </tr>
                    </thead>

                    <tbody id='gradeLogs'>
                        <?php foreach ($LogList  as $log) : ?>
                            <tr>
                                <td><?= $log['codoperacao']; ?></td>
                                <td><?= $log['datahoraoperacao']; ?></td>
                                <td><?= $log['idusuario']; ?></td>
                                <td><?= $log['iddocumento']; ?></td>
                                <td><?= $log['ipacesso']; ?></td>
                            </tr>
                        <?php
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr class="mb-4">
    </div>
</main>

</body>

</html>

<?php require_once __DIR__ . "../../rodape.php" ?>