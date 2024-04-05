<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $Documento */
/** @var  Marinha\Mvc\Models\Paginas[] $paginasList */
$numPag = 1;
?>
<?php require_once __DIR__ . "../../topo.php" ?>
<!-- is.min.js serve para identificar se o usuário está no Windows ou Linux -->
<script src="../../../scripts/serpro/lib/serpro/is.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../../../css/Bootstrap.min.css">
<script src="../../../scripts/jquery-3.1.1.slim.min.js"></script>
<script src="../../../scripts/js/bootstrap.min.js"></script>

<!-- os próximos dois arquivos - em Javascript puro - são a API de comunicação com o Assinador SERPRO -->
<script src="../../../scripts/serpro/lib/serpro/serpro-signer-promise.js" type="text/javascript"></script>
<script src="../../../scripts/serpro/lib/serpro/serpro-signer-client.js" type="text/javascript"></script>

<!-- PDFJS, para converter bas364 em PDF -->
<script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

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
      <div class="container">
        <div class="row">
          <h4>Páginas do documento</h4>
          <?php if (count($paginasList) > 0) { ?>
            <table class="table table-striped" id="listPaginas">
              <thead>
                <tr>
                  <th scope="col">Pagina</th>
                  <th scope="col">Documento</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($paginasList as $pagina) : ?>
                  <tr>
                    <th scope="row"><?= $numPag ?></th>
                    <td><a class="abrirDocumento" data-id=<?= $pagina['documentoid']; ?> data-pagina=<?= $pagina['id']; ?>>Veja o documento</a></td>
                    <td>
                      <input type="button" data-bs-toggle="modal" data-bs-target="#ExcluirPagina" class="btn btn-danger ExcDoc" data-idPagina="<?= $pagina['id']; ?>" data-docId="<?= $pagina['documentoid']; ?>" value="Excluir página">
                    </td>
                  </tr>
                <?php $numPag++;
                endforeach; ?>
              </tbody>
              <tr>
                <span class="alerta"></span>
              </tr>
            </table>
          <?php } else { ?>
            <h4>Ainda não há páginas cadastradas nesse documento.</h4>
          <?php } ?>
        </div>
        <hr class="mb-4">
        <div class="row">
          <div class="col-md-8 order-md-1">
            <h2>Cadastrar nova página.</h2>
            <h3>Informe as Tags</h3>
            <form method="post" id="formAnexarPagDoc" name="formAnexarPagDoc" action="" enctype="multipart/form-data">
              <input type="hidden" id="IdDocumento" name="IdDocumento" value="<?= $documentos['id']; ?>">
              <input type="hidden" id="Nip" name="Nip" value="<?= $documentos['nip']; ?>">
              <input type="hidden" id="Caminho" name="Caminho" value="">
              <input type="hidden" id="tratandoDocumento" name="tratandoDocumento" value="">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="col-form-label" for="Assunto">Informe o assunto </label>
                  <input type="text" id="Assunto" name="Assunto" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                  <label class="col-form-label" for="Autor">Informe o Autor </label>
                  <input type="text" id="Autor" name="Autor" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                  <label class="col-form-label" for="Titulo">Informe o Titulo</label>
                  <input type="text" id="Titulo" name="Titulo" class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-md-9 mb-3">
                  <label class="col-form-label" for="PalavrasChave">Informe as palavras chaves</label>
                  <input type="text" id="PalavrasChave" name="PalavrasChave" class="form-control">
                </div>
              </div>
              <hr class="mb-4">
              <div class="d-block my-3">
                <div class="custom-control custom-radio">
                  <input id="imgToPdf" name="origemDoc" type="radio" value="imgToPdf" class="form-control-input" required>
                  <label class="form-control-label" for="imgToPdf">Tratar Imagens para Pdf e Anexar</label>
                </div>
                <div class="custom-control custom-radio">
                  <input id="pdfPronto" name="origemDoc" type="radio" value="pdfPronto" class="form-control-input" required>
                  <label class="form-control-label" for="pdfPronto">Anexar pdf pronto</label>
                </div>
                <hr class="mb-4">
                <div id="gerenImagem" name="gerenImagem" style="display: none" ;>
                  <div class=" row">
                    <div class="col-md-9 mb-3">
                      <label class="col-form-label" for="documento[]">Selecione as imagens</label>
                      <input type="file" id="documento" name="documento[]" class="form-control" multiple>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9 mb-3">
                      <input type="buttton" class="btn btn-primary" name="btnCarregarArquivosImg" id="btnCarregarArquivosImg" value="Carregar Imagens" disabled="true">
                    </div>
                  </div>
                </div>
                <div id="gerenPDF" name="gerenPDF" style="display: none" ;>
                  <div class=" row">
                    <div class="col-md-9 mb-3">
                      <label class="col-form-label" for="documentoPDF[]">Selecione os PDF</label>
                      <input type="file" id="documentoPDF" name="documentoPDF[]" class="form-control" multiple>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9 mb-3">
                      <input type="buttton" class="btn btn-primary" name="btnCarregarArquivosPDF" id="btnCarregarArquivosPDF" value="Carregar PDF" disabled="true">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9 mb-3">
                      <input type="checkbox" name="AssinaDocumentos" value="1"> Assina documentos
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <div id="listarDocumentos" name="listarDocumentos">

                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div id="visualizarDocumento" name="visualizarDocumento">
                    <iframe src="C:\Users\Rodrigo\OneDrive\Documentos\documentos novos\328.pdf" width="100%" height="500"></iframe>
                  </div>
                </div>
              </div>
              <hr class="mb-4">
              <div class="row">
                <button class="btn btn-primary btn-lg btn-block" data-bs-toggle="modal" data-bs-target="#IndexarDocumento" id="AnexarDocumento" name="AnexarDocumento" type="button">Indexar</button>
              </div>
          </div>

          </form>
        </div>

        <div class="row col-12">
          <div class="panel panel-default" style="display: none;">
            <div class="panel-heading">
              <h3 class="panel-title">Assinar PDF</h3>
            </div>
            <div class="panel-body">
              <form id="assinarPdf">
                <div class="form-group">
                  <label for="file_input">Escolher Arquivo PDF</label>
                  <input id="input-file" type="file" id="arquivo" name="arquivo" value="$paginasList.firstOrDefault()" onchange="convertToBase64();" />
                </div>
                <div class="form-group">
                  <label for="content-value">Conteúdo do PDF (Base 64)</label>
                  <textarea id="content-value" class="form-control" rows="5" disabled></textarea>
                </div>
                <div class="form-group row">
                  <div class="col-sm-2">
                    <button type="submit" id="assinarPdf" name="assinarPdf" class="btn btn-primary">Assinar PDF</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="sign-websocket">Comando WebSocket</label>
                  <textarea id="sign-websocket" class="form-control" rows="7" disabled></textarea>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="row col-12" style="display: none;">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">PDF Assinado</h3>
            </div>
            <div class="panel-body">
              <form>
                <div class="form-group">
                  <label for="resultado">Arquivo Assinado (PDF + Assinatura em Base 64)</label>
                  <textarea id="assinatura" class="form-control" rows="5" disabled></textarea>
                </div>
                <div class="form-group">
                  <button id="validarAssinaturaPdf" type="button" class="btn btn-primary">Validar Assinatura</button>
                  <button type="button" class="btn btn-primary" onclick="downloadPdf();">Download PDF</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
    <span class="alerta"></span>
  </div>
</div>
</div>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>
<script src="../../scripts/serpro/app/serpro-client-connector.js" type="text/javascript"></script>

<div class="modal fade" id="IndexarDocumento" tabindex="-1" aria-labelledby="IndexarDocumento" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <span>Deseja realmente indexar esse novo documento?</span>
          <div class="col-sm-3">

            <input type="button" id="btnConfirmaIndexarDocumento" data-id="" value="Sim" class="btn btn-success btnConfirmaIndexarDocumento">
          </div>
          <div class="col-sm-3">
            <input type="button" id="btnNaoConfirmaIndexarDocumento" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaIndexarDocumento">
          </div>
        </div>
        <span class="alerta"></span>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ExcluirPagina" tabindex="-1" aria-labelledby="ExcluirPagina" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <span>Deseja realmente excluir essa página?</span>
          <div class="col-sm-3">
            <form id="formExcluirPagina">
              <input type="hidden" name="id" id="id">
              <input type="hidden" name="docid" id="docid">
            </form>
            <input type="button" id="btnConfirmaExcluirPagina" data-id="" value="Sim" class="btn btn-success btnConfirmaExcluirPagina">
          </div>
          <div class="col-sm-3">
            <input type="button" id="btnNaoConfirmaExcluirPagina" data-id="" value="Não" class="btn btn-danger btnNaoConfirmaExcluirPagina">
          </div>
        </div>
        <span class="alerta"></span>
      </div>
    </div>
  </div>
</div>