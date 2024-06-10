<?php

/** @var Marinha\Mvc\Models\LogOperacoes[] $LogList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<main>
    <div class="container">
        <div class="bg-body-tertiary rounded-3 row">
            <div class="col-md-8 order-md-1" id="modCadArmario">
                <div class="tituloModulo">Relatório de atividades do sistema</div>
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