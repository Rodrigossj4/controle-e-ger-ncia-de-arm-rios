<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $Documento */
/** @var  Marinha\Mvc\Models\Paginas[] $paginasList */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modIdxDocumento">
            <h3>Tratamento e Indexação de documento</h3>
            <div class="Grade_maior">
                <div class="container_item_maior">
                    <div class="Descricao_maior">
                        NIP
                    </div>
                    <div class="Descricao_maior">
                        Semestre
                    </div>
                    <div class="Descricao_maior">
                        Ano
                    </div>
                    <div class="Descricao_maior">
                        Tipo de documento
                    </div>
                    <div class="Descricao_maior">
                        Armário
                    </div>
                    <div class="Descricao_maior">
                    </div>
                    <!--<div class="Descricao">
                            Ações
                        </div>-->
                </div>
                <?php foreach ($Documento  as $documentos) : ?>
                    <div class="container_item_maior">
                        <div class="Descricao_maior">
                            <?= $documentos['nip']; ?>
                        </div>
                        <div class="Descricao_maior">
                            <?= $documentos['semestre']; ?>
                        </div>
                        <div class="Descricao_maior">
                            <?= $documentos['ano']; ?>
                        </div>
                        <div class="Descricao_maior">
                            <?= $documentos['desctipo']; ?>
                        </div>
                        <div class="Descricao_maior">
                            <?= $documentos['nomeArmario']; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (Count($paginasList) == 0) { ?>

                <h3>Incluir páginas</h3>
                <form method="post" id="formIncluirPagDoc" name="formIncluirPagDoc" action="" enctype="multipart/form-data">
                    <input type="hidden" id="IdDocumento" name="IdDocumento" value="<?= $documentos['id']; ?>">
                    <input type="hidden" id="IdPasta" name="IdPasta" value="<?= $documentos['idPasta']; ?>">
                    <input type="file" id="documento[]" name="documento[]" class="form-control" multiple>
                    <br>
                    <input type="buttton" class="btn btn-primary" name="btnIncluiPag" id="btnIncluiPag" value="Incluir páginas">
                </form>

            <?php } ?>
            <span class="alerta"></span>

            <?php if (Count($paginasList) > 0) { ?>
                <?php if (($paginasList[0]["flgcriptografado"] == null) || (!$paginasList[0]["flgcriptografado"])) { ?>
                    <h3><a class="abrirDocumento" data-id=<?= $documentos['id']; ?> data-cf="false">Veja o documento</a></h3>
                <?php } else { ?>
                    <h3><a class="abrirDocumento" data-id=<?= $documentos['id']; ?> data-cf="true">Veja o documento</a></h3>
            <?php }
            }
            ?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous">
</script>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>