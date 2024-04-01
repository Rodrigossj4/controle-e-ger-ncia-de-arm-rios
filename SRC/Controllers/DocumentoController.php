<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\ArmarioServices;
use Marinha\Mvc\Services\DocumentoServices;
use Marinha\Mvc\Services\LotesServices;
use Marinha\Mvc\Models\PDF;
use Marinha\Mvc\Services\LoteServices;

class DocumentoController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $this->validarSessao();
        $service = new DocumentoServices();
        $armariosService =  new ArmarioServices();

        $DocumentosList = $service->listaDocumentos();
        $ArmariosList = $armariosService->listaArmarios();
        require __DIR__ . '../../Views/documento/index.php';
    }

    public function listarDocumentos()
    {
        $service = new DocumentoServices();
        echo json_encode($service->listaDocumentos());
    }

    public function cadastrarDocumento(): bool
    {
        $idPasta = random_int(1, 999999);
        $service = new DocumentoServices();

        $service->gerarPastaDoc($idPasta);

        $documentosList = array();
        array_push($documentosList, array(
            'docid' => $idPasta,
            'armario' => filter_input(INPUT_POST, 'ListArmarioDocumento'),
            'tipodoc' => filter_input(INPUT_POST, 'SelectTipoDoc'),
            'folderid' => $idPasta,
            'semestre' => filter_input(INPUT_POST, 'semestre'),
            'ano' => filter_input(INPUT_POST, 'ano'),
            'nip' => filter_input(INPUT_POST, 'Nip')
        ));

        $service->cadastrarDocumentos($documentosList);
        return true;
    }

    public function BuscarDocumentos()
    {
        $documentosList = array();
        array_push($documentosList, array(
            'armario' => filter_input(INPUT_POST, 'ListArmarioDocumento'),
            'tipodoc' => filter_input(INPUT_POST, 'SelectTipoDoc'),
            'nip' => filter_input(INPUT_POST, 'Nip'),
            'semestre' => filter_input(INPUT_POST, 'semestre'),
            'ano' => filter_input(INPUT_POST, 'ano')
        ));

        $service = new DocumentoServices();
        $retorno = $service->BuscarDocumentos($documentosList);

        echo json_encode($retorno);
    }
    public function documento()
    {
        //$this->validarSessao();

        $service = new DocumentoServices();
        $Documento = $service->exibirDocumento(filter_input(INPUT_POST, 'idDocumento'));
        //$CaminhoDoc = $this->retornarCaminhoDocumento(filter_input(INPUT_POST, 'idDocumento'));
        $paginasList = $service->listaPaginas(filter_input(INPUT_POST, 'idDocumento'));

        require __DIR__ . '../../Views/documento/documento.php';
    }

    public function criptografarArquivo()
    {
        $service = new DocumentoServices();
        $service->criptografarArquivo($_GET['caminho']);
    }

    public function alterarDocumento(): bool
    {
        $documentosList = array();
        array_push($documentosList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'docid' => filter_input(INPUT_POST, 'docId'),
            'nip' => filter_input(INPUT_POST, 'nip'),
            'semestre' => filter_input(INPUT_POST, 'semestre'),
            'ano' => filter_input(INPUT_POST, 'ano'),
            'tipodocumento' => filter_input(INPUT_POST, 'tipodocumento'),
            'folderid' => filter_input(INPUT_POST, 'folderid'),
            'armario' => filter_input(INPUT_POST, 'armario')
        ));

        $service = new DocumentoServices();
        $service->alterarDocmentos($documentosList);
        return true;
    }

    public function excluir(): bool
    {
        $service = new DocumentoServices();
        $service->excluirDocumentos(filter_input(INPUT_POST, 'idDocumento'));
        return true;
    }

    public function retornarCaminhoDocumento(int $docid, int $pagina): string
    {
        $service = new DocumentoServices();
        return $service->retornarCaminhoDocumento($docid, $pagina);
    }

    public function retornaPdfs()
    {
        $service = new DocumentoServices();
        $tagsList = $this->montaArryaTags();

        $paginasList = $service->gerarPdfs($tagsList);
        echo json_encode($paginasList);
    }

    public function cadastrarPagina(): bool
    {
        $service = new DocumentoServices();
        $tags = filter_input(INPUT_POST, 'Nip') . ", " .  filter_input(INPUT_POST, 'ano');
        $tagsList = $this->montaArryaTags();
        //var_dump($tagsList[0]);
        $paginasList = $service->gerarArquivo(filter_input(INPUT_POST, 'IdPasta'),  $tagsList);
        return $service->cadastrarPaginas($paginasList);
    }

    public function carregarArquivos()
    {
        $service = new DocumentoServices();
        $caminho = $service->carregarArquivosDiretorioTemporario(filter_input(INPUT_POST, 'Nip'), "ARQ");

        echo $caminho;
    }
    public function carregarArquivosServidor()
    {

        $service = new DocumentoServices();
        $caminho = $service->carregarArquivoservidor(file_get_contents('php://input'));
        echo $caminho;
    }
    public function ExibirDireorio()
    {
        $caminho = filter_input(INPUT_POST, 'lote');
        $pasta = "{$caminho}\/";
        $paginasList = array();
        $types = array('pdf');
        if ($handle = opendir($pasta)) {
            while ($entry = readdir($handle)) {
                $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                if (in_array($ext, $types)) {
                    array_push($paginasList, array(
                        $entry
                    ));
                }
            }
            closedir($handle);
            echo json_encode($paginasList);
        }
    }

    public function retornaCaminhoTratado()
    {

        $caminho = filter_input(INPUT_GET, 'caminho');

        echo $caminho;
    }
    public function montaArryaTags(): string
    {
        $assunto = filter_input(INPUT_POST, 'Assunto');
        $autor = filter_input(INPUT_POST, 'Autor');
        $Titulo = filter_input(INPUT_POST, 'Titulo');
        $palavrasChave = filter_input(INPUT_POST, 'PalavrasChave');

        $tagsList = "";
        $tagsList .= "<meta name='description' content='{$assunto}'/>";
        $tagsList .= "<meta name='Author' content='{$autor}'/>";
        $tagsList .= "<title>{$Titulo}</title>";
        $tagsList .= "<meta name='KeyWords' content='{$palavrasChave}'/>";

        return $tagsList;
    }
    public function listarPaginas()
    {
        $service = new DocumentoServices();
        $paginasList = $service->listaPaginas(filter_input(INPUT_POST, 'iddocumento'));
        require __DIR__ . '../../Views/documento/index.php';
    }

    public function excluirPagina(): bool
    {
        $arquivo = json_decode(file_get_contents('php://input'));
        //unlink($b64->arquivoOriginal);
        var_dump($arquivo->idPagina);
        $service = new DocumentoServices();
        $service->excluirPagina($arquivo->idPagina);
        return true;
    }

    public function alterarPagina(): bool
    {
        $paginasList = array();
        array_push($paginasList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'documentoid' => filter_input(INPUT_POST, 'documentoid'),
            'volume' => filter_input(INPUT_POST, 'volume'),
            'numpagina' => filter_input(INPUT_POST, 'numpagina'),
            'codexp' => filter_input(INPUT_POST, 'codexp'),
            'arquivo' => filter_input(INPUT_POST, 'arquivo'),
            'filme' => filter_input(INPUT_POST, 'filme'),
            'fotograma' => filter_input(INPUT_POST, 'fotograma'),
            'imgencontrada' => filter_input(INPUT_POST, 'imgencontrada')
        ));

        $service = new DocumentoServices();
        $service->alterarPaginas($paginasList);
        return true;
    }

    public function visualizarDocumento()
    {
        $caminho = $this->retornarCaminhoDocumento(filter_input(INPUT_GET, 'docid'), filter_input(INPUT_GET, 'pagina'));
        $cifrado = "true";
        $service = new DocumentoServices();
        $arquivo  = $service->abrirArquivo($caminho, $cifrado);
        require __DIR__ . '../../Views/documento/visualizar.php';
    }
    public function visualizarDocumentoLote()
    {
        $caminho = filter_input(INPUT_GET, 'docid');
        $service = new DocumentoServices();
        $arquivo  = $service->abrirArquivo($caminho, "false");
        require __DIR__ . '../../Views/documento/visualizar.php';
    }
    public function asssinarDigital()
    {
        $service = new DocumentoServices();
        $tags = filter_input(INPUT_POST, 'Nip') . ", " .  filter_input(INPUT_POST, 'ano');
        $tagsList = $this->montaArryaTags();
        //var_dump($tagsList[0]);
        $paginasList = $service->gerarArquivo(filter_input(INPUT_POST, 'IdPasta'),  $tagsList);
        require __DIR__ . '../../Views/documento/Assinar.php';
    }

    public function ExibirArquivosDiretorio()
    {
        $caminho = filter_input(INPUT_POST, 'Caminho');
        var_dump($caminho);
        $pasta = "{$caminho}/";
        $paginasList = array();
        $types = array('jpg', 'png', 'pdf');
        if ($handle = opendir($pasta)) {
            while ($entry = readdir($handle)) {
                $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                if (in_array($ext, $types)) {
                    array_push($paginasList, array(
                        $entry
                    ));
                }
            }
            closedir($handle);
            var_dump($paginasList);
            echo json_encode($paginasList);
        }
    }

    public function arquivoBase64()
    {
        //var_dump("ei: ". pathinfo($documentos[0]->arquivo)['dirname']);
        $path = filter_input(INPUT_GET, 'caminho');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = base64_encode($data);
        echo json_encode($base64);
    }

    public function base64ArquivoPDF()
    {
        $b64 = json_decode(file_get_contents('php://input'));
        unlink($b64->arquivoOriginal);
        $bin = base64_decode($b64->arquivoB64, true);
        file_put_contents($b64->arquivoOriginal, $bin);
    }
    public function Indexar()
    {
        var_dump("vamos lá");
        //transformar imagens em pdf(incluindo as tags)
        //assinar
    }
}
