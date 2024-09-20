<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $Documento */
/** @var  Marinha\Mvc\Models\Paginas[] $paginasList */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modIdxDocumento">
            <h3>Indexação de documento Origem OL</h3>
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
                <div id="gradeOpcoes" name="gradeOpcoes" style="border: 1px #000 solid;">
                    <div style="display: inline-block;">
                        <form id="formDocOm" name="formDocOm" >
                            <input type="hidden" id="IdDocumento" name="IdDocumento" value="<?= $documentos['id']; ?>">
                            <input type="hidden" id="IdPasta" name="IdPasta" value="<?= $documentos['idPasta']; ?>">
                            <input type="button" class="btn btn-primary" name="btnDocOm" id="btnDocOm" value="Indexar documento de origem OM">                 
                        </form>
                    </div>
                    <div style="display: inline-block;">
                        <form id="formDocOl" name="formDocOl" >
                            <input type="hidden" id="IdDocumento" name="IdDocumento" value="<?= $documentos['id']; ?>">
                            <input type="hidden" id="IdPasta" name="IdPasta" value="<?= $documentos['idPasta']; ?>">
                            <input type="button" class="btn btn-primary" name="btnDocOl" id="btnDocOm" value="Indexar documento de origem x">                 
                        </form>
                    </div>
                </div>
                <h3>Informe as Tags</h3>
                <form method="post" id="formIncluirPagDoc" name="formIncluirPagDoc" action="" enctype="multipart/form-data">
                    <input type="hidden" id="IdDocumento" name="IdDocumento" value="<?= $documentos['id']; ?>">
                    <input type="hidden" id="IdPasta" name="IdPasta" value="<?= $documentos['idPasta']; ?>">
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label class="col-form-label" for="Assunto">Informe o assunto </label>
                            <input type="text" id="Assunto" name="Assunto" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="col-form-label" for="Autor">Informe o Autor </label>
                            <input type="text" id="Autor" name="Autor" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="col-form-label" for="Titulo">Titulo</label>
                            <input type="text" id="Titulo" name="Titulo" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <h3>Incluir páginas</h3>
                        <div class="col-md-3 mb-3">
                            <label class="col-form-label" for="documento[]">Selecione as páginas</label>
                            <input type="file" id="documento[]" name="documento[]" class="form-control" multiple>
                        </div>
                        <input type="buttton" class="btn btn-primary" name="btnIncluiPag" id="btnIncluiPag" value="Incluir páginas">
                    </div>
                    <div class="form-row">
                        <h3>Incluir páginas</h3>
                        <div class="col-md-3 mb-3">
                            <label class="col-form-label" for="caminhoDocumento">Selecione o diretório </label>
                            <input type="text" id="caminhoDocumento" name="caminhoDocumento" class="form-control" webkitdirectory>
                            <input type="buttton" class="btn btn-primary" name="carregarDocumentos" id="carregarDocumentos" value="Carregar Documentos">
                        </div>
                        <input type="buttton" class="btn btn-primary" name="btnIncluiPag" id="btnIncluiPag" value="Incluir páginas">
                    </div>
                </form>
            <?php } ?>
            <span class="alerta"></span>

            <?php if (Count($paginasList) > 0) { ?>
                <?php if (($paginasList[0]["flgcriptografado"] == null) || (!$paginasList[0]["flgcriptografado"])) { ?>
                    <h3><a class="abrirDocumento" data-id=<?= $documentos['id']; ?> data-cf="false">Veja o documento</a></h3>
                <?php } else { ?>
                    <h3><a class="abrirDocumento" data-id=<?= $documentos['id']; ?> data-cf="true">Veja o documento</a></h3>
                <?php } ?>
                <div class="form-row">
                    <input type="buttton" class="btn btn-primary" value="Liberar documento Somente para assinatura digital" />
                    <input type="buttton" class="btn btn-primary" value="Liberar documento para assinatura digital e criptografia" />
                </div>
            <?php }
            ?>
        </div>
    </div>
</div>
<form method="Post" id="gradeDocumentos" name="gradeDocumentos" enctype="multipart/form-data">
    <div id="listarDocumentos" name="listarDocumentos">

    </div>
</form>
<div id="visualizarDocumento" name="visualizarDocumento">
<iframe src="C:\Users\Rodrigo\OneDrive\Documentos\documentos novos\328.pdf" width="100%" height="500"></iframe>
</div>

<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>