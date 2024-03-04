<?php foreach ($ArmariosList  as $armario) : ?>
    <div class="container_item">
        <div class="Descricao">
            <?= $armario['nomeexterno']; ?>
        </div>
        <div class="acoes">
            <form id="formGerenciarArmario" name="formGerenciarArmario">
                <input type="hidden" name="idArmario" id="idArmario" value="<?= $armario['id']; ?>" />
                <input type="button" class="btn btn-primary btnGerenciarArmario" data-bs-toggle="modal" data-bs-target="#GerenciarArmario" data-id="<?= $armario['id']; ?>" value="Gerenciar">
            </form>
            <button class="btn btn-warning btnAlterarArmario" data-bs-toggle="modal" data-bs-target="#AlteraArmario" data-id="<?= $armario['id']; ?>" data-ni="<?= $armario['nomeinterno']; ?>" data-ne="<?= $armario['nomeexterno']; ?>" data-cd="<?= $armario['codigo']; ?>">Editar</button>
            <form method="post" id="excluir<?= $armario['id']; ?>" action="">
                <input type="hidden" id="idArmario" name="idArmario" value="<?= $armario['id']; ?>">
                <button class="btn btn-danger excluir" data-id="<?= $armario['id']; ?>" data-bs-toggle="modal" data-bs-target="#ExcluirArmario" type="button">Excluir</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>