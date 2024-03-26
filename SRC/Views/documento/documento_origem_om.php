<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $Documento */
/** @var  Marinha\Mvc\Models\Paginas[] $paginasList */
/** @var  Marinha\Mvc\Models\Lotes[] $Listalotes */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
<div class="container">
    <div class="bg-body-tertiary rounded-3 row">
        <div class="col divisao_bottom form-control-padronizado" id="modIdxDocumento">
            <h3>Indexação de documento Origem OM</h3>
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
                <div style="
                    border: 1px #000 solid;
                    width: 581px;
                    display: inline-block;
                    position:relative;
                ">
                <div class="form-row">
                <form id="SelectLote" name="SelectLote" method="POST">
                    <select id="lote" name="lote" class="form-control">
                        <option value="0">Selecione o lote de documentos</option>
                        <?php foreach ($Listalotes  as $lote) : ?>
                            <option value="<?= $lote['pasta']; ?>"><?= $lote['numeroLote']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
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
                    <div class="form-row" style="display: none;">
                        <input type="text" id="documento" name="documento" class="form-control">
                   </div>                   
                </form>
                </div>
                <div style="
                        border: 1px #000 solid;
                        width: 501px;
                        display: inline-block;
                    " id="visualizarDocumento" name="visualizarDocumento">
                    </div>
            <?php } ?>
               
            
            </div>
            <span class="alerta"></span>          
        </div>
    </div>
</div>
<div class="container" style="
                    border: 1px #000 solid;
                    width: 581px;
                    display: inline-block;
                    position:relative;
                ">

<form method="Post" id="gradeDocumentos" name="gradeDocumentos" enctype="multipart/form-data">
    <div id="listarDocumentos" name="listarDocumentos">

    </div>
</form>
<input type="buttton" class="btn btn-primary" name="btnIncluiPag" id="btnIncluiPag" value="Incluir páginas">
</div>


<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>