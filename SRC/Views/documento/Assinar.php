<?php

/** @var Marinha\Mvc\ValueObjects\DocumentoPaginaVO[] $Documento */
/** @var  Marinha\Mvc\Models\Paginas[] $paginasList */

?>
<?php require_once __DIR__ . "../../topo.php" ?>
 <!-- is.min.js serve para identificar se o usuário está no Windows ou Linux -->
 <script src="../../../scripts/serpro/lib/serpro/is.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <script src="../../../scripts/jquery-3.1.1.slim.min.js"></script>
    <script src="../../../scripts/js/bootstrap.min.js"></script>

<!-- os próximos dois arquivos - em Javascript puro - são a API de comunicação com o Assinador SERPRO -->
<script src="../../../scripts/serpro/lib/serpro/serpro-signer-promise.js" type="text/javascript"></script>
<script src="../../../scripts/serpro/lib/serpro/serpro-signer-client.js" type="text/javascript"></script>


<!-- PDFJS, para converter bas364 em PDF -->
<!-- <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.6.82/pdf.min.mjs" type="module"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.6.82/pdf_viewer.min.css">

<div class="container">
<div class="">
        <div class="px-2 font-weight-bold">WebSocket Server está:</div>
        <div class="label label-success badge-pill js-server-status js-server-status-on">ONLINE</div>
        <div class="label label-danger badge-pill js-server-status js-server-status-off">OFFLINE</div>
      </div>
      <p><a class="js-server-authorization" href="http://127.0.0.1:65056/" target="_blank">Favor autorizar o assinador!</a></p>

<div class="row col-12">
        <div class="panel panel-default">
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

      <div class="row col-12">
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


<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/scripts.js"></script>
<script src="../../scripts/serpro/app/serpro-client-connector.js" type="text/javascript"></script>
    <script>
      function prettyCommandSign() {
        $('#sign-websocket').val(JSON.stringify({
          command: "sign",
          type: "pdf",
          inputData: $('#content-value').val()
        }, null, 2));
      }
      prettyCommandSign();
      
      // BASE 64
      // https://stackoverflow.com/questions/13538832/convert-pdf-to-a-base64-encoded-string-in-javascript
      function convertToBase64() {
        var selectedFile = document.getElementById("input-file").files;
        if (selectedFile.length > 0) {
          var fileToLoad = selectedFile[0];
          var fileReader = new FileReader();
          var base64;
          fileReader.onload = function(fileLoadedEvent) {
            base64 = fileLoadedEvent.target.result;
            if (base64.indexOf('data:application/pdf;base64,')==0) {
              base64 = base64.substring('data:application/pdf;base64,'.length, base64.length);
            } else {
              alert('O cabeçalho PDF não foi encontrado. Esse é mesmo um arquivo PDF?');
            }
            document.getElementById("content-value").value = base64;
            prettyCommandSign();
          };
          fileReader.readAsDataURL(fileToLoad);
        }
      }
      // Download PDF
      function downloadPdf() {
        const data = $('#assinatura').val();
        const linkSource = `data:application/pdf;base64,${data}`;
        const downloadLink = document.createElement("a");
        const fileName = "assinado.pdf";
        downloadLink.href = linkSource;
        downloadLink.download = fileName;
        downloadLink.click();
      }
    </script>