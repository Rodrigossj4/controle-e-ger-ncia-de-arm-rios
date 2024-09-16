<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Models\OM;
use Marinha\Mvc\Services\ArmarioServices;
use Marinha\Mvc\Services\DocumentoServices;
use Marinha\Mvc\Services\OMServices;
use Marinha\Mvc\Helpers\Helppers;

class DocumentoController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $this->validarSessao();
        if ($_SESSION['usuario'][0]["nivelAcesso"] == 3)
            header("location: /home");

        $service = new DocumentoServices();
        $armariosService =  new ArmarioServices();
        $OMServices =  new OMServices();
        $OMList =  $OMServices->listarOM();
        $dadosOM = null;

        if (isset($_SESSION['usuario']))
            $dadosOM = $OMServices->ObterDadosOM($_SESSION['usuario'][0]["omusuario"]);

        $DocumentosList = $service->listaDocumentos();
        $ArmariosList = $armariosService->listaArmariosPorPerfil($_SESSION['usuario'][0]["idacesso"]);
        require __DIR__ . '../../Views/documento/index.php';
    }

    public function VisualizarDocumentos()
    {
        $this->validarSessao();
        if ($_SESSION['usuario'][0]["nivelAcesso"] > 3)
            header("location: /home");

        $service = new DocumentoServices();
        $armariosService =  new ArmarioServices();

        $DocumentosList = $service->listaDocumentos();
        $ArmariosList = $armariosService->listaArmarios();
        require __DIR__ . '../../Views/documento/visualizarDocumentos.php';
    }

    public function listarDocumentos()
    {
        $service = new DocumentoServices();
        echo json_encode($service->listaDocumentos());
    }

    public function cadastrarDocumento()
    {
        $Arquivos = json_decode(file_get_contents('php://input'), true);

        $funcoes = new Helppers();

        if (strlen($Arquivos["nip"]) < 8 || strlen($Arquivos["ano"]) != 4 || $Arquivos["semestre"] == 0  || $Arquivos["idArmario"] == 0 || $Arquivos["tipoDoc"] == 0) {
            http_response_code(500);
            echo "Todos os campos são obrigatórios";
            return false;
        }

        if (!$funcoes->validarNip($funcoes->somenteNumeros($Arquivos["nip"]))) {
            http_response_code(500);
            echo "Nip inválido";
            return false;
        }

        $service = new DocumentoServices();
        echo json_encode($service->cadastrarDocumentos($Arquivos));
    }

    public function anexarPaginaDocumento()
    {
        $Arquivos = json_decode(file_get_contents('php://input'), true);
        $funcoes = new Helppers();
        if (strlen($Arquivos["nip"]) < 8 || strlen($Arquivos["ano"]) != 4 || $Arquivos["semestre"] == 0  || $Arquivos["idArmario"] == 0 || $Arquivos["tipoDoc"] == 0) {
            http_response_code(500);
            return "Todos os campos são obrigatórios";
        }

        if (!$funcoes->validarNip($funcoes->somenteNumeros($Arquivos["nip"]))) {
            http_response_code(500);
            echo "Nip inválido";
            return false;
        }

        $idPasta = random_int(1, 999999);
        $service = new DocumentoServices();

        //$service->gerarPastaDoc($idPasta);
        //var_dump($Arquivos["idArmario"]);

        $documentosList = array();
        array_push($documentosList, array(
            'docid' => $idPasta,
            'armario' => $Arquivos["idArmario"],
            'tipodoc' => $Arquivos["tipoDoc"],
            'folderid' => $idPasta,
            'semestre' =>  $Arquivos["semestre"],
            'ano' => $Arquivos["ano"],
            'nip' => $Arquivos["nip"]
        ));

        //var_dump($documentosList[0]["docid"]);
        $documentosList[0]["docid"] = $service->cadastrarDocumentos($documentosList);

        echo json_encode($documentosList[0]["docid"]);
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
        if (!isset($_SESSION)) {
            session_start();
            $codUsuario = $_SESSION['usuario'][0]["codusuario"];
        }

        $service = new DocumentoServices();
        $Documento = $service->exibirDocumento(filter_input(INPUT_POST, 'idDocumento'));
        //$CaminhoDoc = $this->retornarCaminhoDocumento(filter_input(INPUT_POST, 'idDocumento'));
        $paginasList = $service->listaPaginas(filter_input(INPUT_POST, 'idDocumento'), $codUsuario);

        require __DIR__ . '../../Views/documento/documento.php';
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
        $dadosDocumento = json_decode(file_get_contents('php://input'));
        var_dump($dadosDocumento);

        $service = new DocumentoServices();
        $service->excluirDocumentos($dadosDocumento->idDocumento);

        return true;
    }

    public function excluirPagina(): bool
    {
        $Arquivos = json_decode(file_get_contents('php://input'));
        $Arquivos->ip = $this->retornaIP();
        if (!isset($_SESSION))
            session_start();

        $Arquivos->codusuario = $_SESSION['usuario'][0]["codusuario"];
        $Arquivos->omusuario = $_SESSION['usuario'][0]["omusuario"];
        $Arquivos->idacesso = $_SESSION['usuario'][0]["idacesso"];

        $service = new DocumentoServices();

        return $service->excluirPagina($Arquivos);
    }

    public function retornarCaminhoDocumento(int $docid, int $pagina): string
    {
        $service = new DocumentoServices();
        return $service->retornarCaminhoDocumento($docid, $pagina);
    }

    public function retornaPdfs()
    {
        $dadosDocumento = json_decode(file_get_contents('php://input'));
        // var_dump($dadosDocumento);
        $service = new DocumentoServices();
        //$tagsList = $this->montaArryaTags(json_decode($dadosDocumento->tags));
        $paginasList = $service->gerarPdfs($dadosDocumento);
        echo json_encode($paginasList);
    }

    public function carregarArquivos()
    {
        $caminho = "";
        if (count($_FILES['documento']['name']) > 0) {
            $extensoesValidas = $this->validarExtensao();
            for ($i = 0; $i < count($_FILES['documento']['name']); $i++) {
                //$ext = pathinfo($_FILES['documento']['name'][$i], PATHINFO_EXTENSION);
                $ext = explode("/", mime_content_type($_FILES['documento']['tmp_name'][$i]))[1];
                //var_dump($ext);
                if ((!in_array(strtolower($ext), $extensoesValidas))) {
                    http_response_code(500);
                    echo "Extensão não permitida";
                    return false;
                }
            }

            if (!isset($_SESSION))
                session_start();

            $service = new DocumentoServices();

            $caminho = $service->carregarArquivosDiretorioTemporario(filter_input(INPUT_POST, 'Nip'), "ARQ", $_SESSION['usuario'][0]["codusuario"]);
        }

        echo $caminho;
    }
    public function carregarArquivosServidor()
    {
        //var_dump("oi " . file_get_contents('php://input'));
        $service = new DocumentoServices();
        $Arquivos = json_decode(file_get_contents('php://input'));
        $Arquivos->ip = $this->retornaIP();
        if (!isset($_SESSION))
            session_start();

        //var_dump($_SESSION);
        $Arquivos->codusuario = $_SESSION['usuario'][0]["codusuario"];
        $Arquivos->omusuario = $_SESSION['usuario'][0]["omusuario"];
        $Arquivos->idacesso = $_SESSION['usuario'][0]["idacesso"];

        $caminho = $service->carregarArquivoservidor($Arquivos);
        //echo $caminho;
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

    public function listarPaginas()
    {
        $codUsuario = "";
        if (!isset($_SESSION)) {
            session_start();
            $codUsuario = $_SESSION['usuario'][0]["codusuario"];
        }

        $service = new DocumentoServices();
        $paginasList = $service->listaPaginas(filter_input(INPUT_GET, 'idDocumento'),  $codUsuario);
        echo json_encode($paginasList);
    }

    public function ReIndexarPagina()
    {
        $funcoes = new Helppers();
        $Arquivos = json_decode(file_get_contents('php://input'));
        try {
            if (strlen($Arquivos->nip) < 8 || strlen($Arquivos->ano) != 4 || $Arquivos->semestre == 0  || $Arquivos->idArmario == 0 || $Arquivos->tipoDoc == 0) {
                http_response_code(500);
                echo "Todos os campos são obrigatórios";
                return false;
            }

            if (!$funcoes->validarNip($funcoes->somenteNumeros($Arquivos->nip))) {
                http_response_code(500);
                echo "Nip inválido";
                return false;
            }

            $service = new DocumentoServices();
            $Arquivos->ip = $this->retornaIP();
            if (!isset($_SESSION))
                session_start();

            $Arquivos->codusuario = $_SESSION['usuario'][0]["codusuario"];
            $Arquivos->omusuario = $_SESSION['usuario'][0]["omusuario"];
            $Arquivos->idacesso = $_SESSION['usuario'][0]["idacesso"];

            if ($service->reindexarPagina($Arquivos)) {
                http_response_code(200);
                echo "Documento Re-Indexado com sucesso";
                return true;
            }
        } catch (Exception) {
            http_response_code(500);
            return  "Houve um problema para Reindexar";
        }
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
        //var_dump($caminho);
        $cifrado = "true";
        $service = new DocumentoServices();
        $arquivo  = $service->abrirArquivo($caminho, $cifrado);
        require __DIR__ . '../../Views/documento/visualizar.php';
    }

    public function visualizarDocumentoLote()
    {
        $caminho = filter_input(INPUT_GET, 'docid');
        $service = new DocumentoServices();
        $arquivo  = $service->abrirArquivo($caminho, "true");
        require __DIR__ . '../../Views/documento/visualizar.php';
    }

    public function ExibirArquivosDiretorio()
    { //teste
        $caminho = filter_input(INPUT_POST, 'Caminho');
        //var_dump("caminho:" . $caminho);
        $pasta = "{$caminho}/";
        //var_dump($pasta);
        $paginasList = array();
        $types = array('jpg', 'jpeg', 'png', 'tif', 'pdf');
        if ($handle = opendir($pasta)) {
            while ($entry = readdir($handle)) {
                $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                if (in_array($ext, $types)) {
                    array_push($paginasList, array(
                        "../" . $caminho . "/" . $entry
                    ));
                }
            }
            closedir($handle);

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
        $bin = base64_decode($b64->arquivoB64);
        file_put_contents($b64->arquivoOriginal, $bin);
    }

    private function validarExtensao(): array
    {
        $extenssoes = ["jpg", "jpeg", "png", "tif", "tiff", "pdf"];
        return $extenssoes;
    }

    public function migrarLegado(string $caminhoArq): void
    {
        $service = new DocumentoServices();
        //ler do banco
        //transforma pdf       
        $caminhoArq = $service->gerarOcrs($caminhoArq);

        //cifra
        $service->criptografarArquivo($caminhoArq);

        //apaga antigo

        //atualiza banco
    }
}
