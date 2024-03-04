<?php

/** @var Marinha\Mvc\Models\TipoDocumento[] $TipoDocumentoList */
?>
<?php require_once __DIR__ . "../../topo.php" ?>

nome armario
lista documentos do armario
lista documentos
<select>
    <?php foreach ($TipoDocumentosList  as $TipoDocumento) : ?>
        <option value="<?= $TipoDocumento['IdTipoDoc']; ?>"><?= $TipoDocumento['DescTipoDoc']; ?></option>
    <?php endforeach; ?>
</select>