<?php

namespace Marinha\Mvc\Services;

use Exception;

use Marinha\Mvc\Infra\Repository\DocumentoRepository;
use Marinha\Mvc\Infra\Repository\ArmarioRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;

require_once 'vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

use setasign\Fpdi\Fpdi;
use TCPDF\TCPDF;

class DocumentoServices extends SistemaServices
{
    private $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    private $diretorio = "/marinha/sisimagem/";
    private $diretorioLote = "documentos/";

    public function __construct()
    {
    }
    public function listaDocumentos(): array
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            $retorno = $repository->listaDocumentos();

            return $retorno;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarDocumentos(array $Arquivos): int
    {
        try {
            //$Arquivos = json_decode(file_get_contents('php://input'), true);
            $idPasta = random_int(1, 999999);

            $documentosList = array();
            array_push($documentosList, array(
                'docid' => $idPasta,
                'armario' => $Arquivos["idArmario"],
                'tipodoc' => $Arquivos["tipoDoc"],
                'folderid' => $idPasta,
                'semestre' =>  $Arquivos["semestre"],
                'ano' => $Arquivos["ano"],
                'nip' => str_replace('.', '', $Arquivos["nip"])
            ));

            $repository = new DocumentoRepository($this->Conexao());
            //var_dump($repository);
            return $repository->cadastrarDocumentos($documentosList);
        } catch (Exception $e) {
            echo $e;
            return 0;
        }
    }

    public function BuscarDocumentos($documentosList): array
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->BuscarDocumentos($documentosList);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function BuscarDocumentosPorTipo(int $idTipoDocumento): array
    {
        $repository = new DocumentoRepository($this->Conexao());
        return $repository->BuscarDocumentosPorTipo($idTipoDocumento);
    }

    public function exibirDocumento(int $id): array
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->exibirDocumento($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function alterarDocmentos(array $documento): bool
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->alterarDocumentos($documento);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function excluirDocumentos(int $id): bool
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->excluirDocumentos($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function retornarCaminhoDocumento(string $id, int $pagina): string
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            $retorno =  $repository->retornarCaminhoDocumento($id, $pagina);

            return $retorno == null ? "" : $retorno;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function listaPaginas(int $id): array
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());

            // json_decode($arquivos->listDocumentosServidor[0], true)
            $listaArquivos =  $repository->listarPaginas($id);
            $diretorioOriginal = pathinfo($listaArquivos[0]["arquivo"], PATHINFO_DIRNAME);
            //var_dump($listaArquivos);
            $componentes = explode('/', $listaArquivos[0]["arquivo"]);
            $resultado = implode('/', array_slice($componentes, -3));
            $diretorioTemporario = $this->diretorioLote . "TEMP/" . pathinfo($resultado, PATHINFO_DIRNAME);

            if (!file_exists($diretorioTemporario)) {
                mkdir($diretorioTemporario, 0777, true);
            }

            foreach ($listaArquivos as &$arquivo) {
                copy($diretorioOriginal . "/" . pathinfo($arquivo["arquivo"], PATHINFO_BASENAME), $diretorioTemporario . "/" . pathinfo($arquivo["arquivo"], PATHINFO_BASENAME));
                $arquivo["arquivo"] = $diretorioTemporario . "/" . pathinfo($arquivo["arquivo"], PATHINFO_BASENAME);
            }

            return $listaArquivos;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarPaginas(array $pagina): int
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->cadastrarPagina($pagina);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function reindexarPagina(): bool
    {

        try {
            $arquivo = json_decode(file_get_contents('php://input'));

            $documentosList = array();
            array_push($documentosList, array(
                'docid' => "",
                'nip' => $arquivo->nip,
                'semestre' => $arquivo->semestre,
                'ano' => $arquivo->ano,
                'tipodoc' => $arquivo->tipoDoc,
                'folderid' => "",
                'armario' => $arquivo->idArmario
            ));

            //var_dump($arquivo);
            //verificar se existe documento com essa caracteristica nesse nip
            $retorno = $this->BuscarDocumentos($documentosList);
            $repository = new DocumentoRepository($this->Conexao());

            if (count($retorno) > 0) {
                //retornar os arquivos
                $listaArquivosDestino = $repository->listarPaginas($retorno[0]['id']);
                $listaArquivosOrigem = $repository->carminhoArquivos($arquivo->idPagina);

                // alterar o local do arquivo no servidor para a pasta do documento existente              
                copy($listaArquivosOrigem[0]["Arquivo"], pathinfo($listaArquivosDestino[0]["arquivo"], PATHINFO_DIRNAME) . "/" . pathinfo($listaArquivosOrigem[0]["Arquivo"], PATHINFO_BASENAME));
                unlink("{$listaArquivosOrigem[0]["Arquivo"]}");

                //caso exista alterar o documento pai e o campo do caminho na tabela documento pagina na tabela documento página desse item
                $repository->AlterarDocumentoDaPagina($retorno[0]['id'], $arquivo->idPagina, pathinfo($listaArquivosDestino[0]["arquivo"], PATHINFO_DIRNAME) . "/" . pathinfo($listaArquivosOrigem[0]["Arquivo"], PATHINFO_BASENAME));
            } else {

                $documentosList = array();
                array_push($documentosList, array(
                    'docid' => "",
                    'nip' => $arquivo->nip,
                    'semestre' => $arquivo->semestre,
                    'ano' => $arquivo->ano,
                    'tipoDoc' => $arquivo->tipoDoc,
                    'folderid' => "",
                    'idArmario' => $arquivo->idArmario
                ));
                $id = $this->cadastrarDocumentos($documentosList[0]);

                $armarioRepository = new ArmarioRepository($this->Conexao());
                $nomeArmario = $armarioRepository->BuscarArmarioId($arquivo->idArmario);

                $arquivoDiretorio = $this->gerarPasta($nomeArmario);

                //$listaArquivosDestino = $repository->listarPaginas($id);
                $listaArquivosOrigem = $repository->carminhoArquivos($arquivo->idPagina);

                // alterar o local do arquivo no servidor para a pasta do documento existente              
                copy($listaArquivosOrigem[0]["Arquivo"], $arquivoDiretorio  . "/" . pathinfo($listaArquivosOrigem[0]["Arquivo"], PATHINFO_BASENAME));
                unlink("{$listaArquivosOrigem[0]["Arquivo"]}");

                //caso exista alterar o documento pai e o campo do caminho na tabela documento pagina na tabela documento página desse item
                $repository->AlterarDocumentoDaPagina($id, $arquivo->idPagina, $arquivoDiretorio . "/" . pathinfo($listaArquivosOrigem[0]["Arquivo"], PATHINFO_BASENAME));

                // unlink("{$listaArquivosOrigem[0]["Arquivo"]}");
            }




            return true;
            // return $repository->excluirPagina($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function alterarPaginas(array $pagina): bool
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->alterarPagina($pagina);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function gerarPdfs($dadosDocumento): array
    {

        $total = count($dadosDocumento->imagens);
        // var_dump($total);
        $paginasList = [];

        $armarioRepository = new ArmarioRepository($this->Conexao());
        $nomeArmario = $armarioRepository->BuscarArmarioId($dadosDocumento->idArmario);
        // $origemOcr = $this->gerarOcrs($origem);
        for ($i = 0; $i < $total; $i++) {

            $ext = pathinfo($dadosDocumento->imagens[$i], PATHINFO_EXTENSION);
            $caminhoArqImgServ = "";
            if ($ext != "pdf") {
                $caminhoArqImgServ = $this->gerarOcrs($dadosDocumento->imagens[$i]);
                $this->IncluirTags($caminhoArqImgServ, $dadosDocumento->tags);
            } else {
                $caminhoArqImgServ = $dadosDocumento->imagens[$i];
            }
            //var_dump($caminhoArqImgServ);


            $arqui = random_int(1, 999999);
            $nomePDF = "/{$arqui}.pdf";

            $caminhoPDF = $dadosDocumento->caminho . "/" . pathinfo($caminhoArqImgServ, PATHINFO_BASENAME);

            move_uploaded_file($caminhoArqImgServ, filter_input(INPUT_POST, 'Caminho') . "" . $nomePDF);

            array_push($paginasList, array(
                'documentoid' =>  null,
                'idArmario' => $dadosDocumento->idArmario,
                'nomeArmario' => $nomeArmario,
                'tipoDocumento' => $dadosDocumento->tipoDoc,
                'volume' => "1",
                'numpagina' => $i,
                'codexp' => 1,
                'arquivo' => $caminhoPDF,
                'filme' => "1",
                'fotograma' => "1",
                'imgencontrada' => "0",
                'b64' => "",
                'tags' => $dadosDocumento->tags
            ));
        }

        //var_dump($paginasList);
        return $paginasList;
    }

    private function IncluirTags(string $arquivos, string $dadosTags)
    {

        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile($arquivos);
        $pageId = $pdf->importPage(1);
        $pdf->useTemplate($pageId);
        $tags = json_decode($dadosTags);

        if ($tags->titulo != "")
            $pdf->SetTitle($tags->titulo);

        if ($tags->autor)
            $pdf->SetAuthor($tags->autor);

        if ($tags->assunto)
            $pdf->SetSubject($tags->assunto);

        if ($tags->identificador)
            $pdf->SetKeywords("Identificador: " . $tags->identificador);

        $pdf->Output($arquivos, 'F');
    }

    public function abrirArquivo(string $caminhoarquivo, string $cifrado): string
    {
        if ($cifrado == "true") {

            $encrypted_code = file_get_contents($caminhoarquivo);
            //$decrypted_code = $this->my_decrypt($encrypted_code, $this->key);

            file_put_contents('documentos/ttt.pdf', $encrypted_code);
            return "documentos/ttt.pdf";
        } else {
            file_put_contents('documentos/ttt.pdf', file_get_contents($caminhoarquivo));
            return "documentos/ttt.pdf";
        }
    }

    public function carregarArquivosDiretorioTemporario(string $nip, string $tipoArquivo): string
    {
        $pasta = $this->gerarPastaTemporaria($nip, $tipoArquivo);
        if (count($_FILES['documento']['name']) > 0)
            $this->subirArquivos($pasta);

        /*if (count($_FILES['documentoPDF']['name']) > 0)
            $this->subirArquivosPDF($pasta);*/

        //var_dump($pasta);
        return $pasta;
    }

    private function gerarPastaTemporaria(string $nip, string $tipoArquivo): string
    {
        $idPasta = random_int(1, 999999);
        $pasta = $tipoArquivo . "-" . $nip . "-" . $idPasta;
        mkdir("{$this->diretorioLote}{$pasta}", 0777, true);
        return "{$this->diretorioLote}{$pasta}";
    }

    private function subirArquivos(string $diretorio)
    {
        $total = count($_FILES['documento']['name']);
        //var_dump($total);
        for ($i = 0; $i < $total; $i++) {

            $nomeArquivo = pathinfo($_FILES['documento']['name'][$i], PATHINFO_BASENAME);
            $arquivoExtensao = pathinfo($_FILES['documento']['name'][$i], PATHINFO_EXTENSION);

            $caminhoArqImgServ = $diretorio . "/" . $_FILES['documento']['name'][$i];
            $this->uploadImgPastaLote($caminhoArqImgServ, $i);

            if ($arquivoExtensao == "TIF") {
                $novoNome = $diretorio . "/" . pathinfo($_FILES['documento']['name'][$i], PATHINFO_FILENAME) . ".png";
                $this->TratarTifParaJpeg($caminhoArqImgServ, $novoNome);
                array_map('unlink', glob("$caminhoArqImgServ"));
                //rmdir("{$caminhoArqImgServ}");
            }
        }
    }

    private function subirArquivosPDF(string $diretorio)
    {
        $total = count($_FILES['documentoPDF']['name']);
        //var_dump("total: " . $total);
        for ($i = 0; $i < $total; $i++) {


            $caminhoArqImgServ = $diretorio . "/" . $_FILES['documentoPDF']['name'][$i];
            $this->uploadPDFPasta($caminhoArqImgServ, $i);
        }
    }
    private function TratarTifParaJpeg(string $caminhoTif, string $diretoriosaida): string
    {
        $input_tiff = $caminhoTif;
        $output_jpeg = $diretoriosaida;

        // Comando para chamar o ImageMagick para converter TIFF para JPEG
        //$command = "magick $input_tiff $output_jpeg";
        $command = "convert $input_tiff $output_jpeg";
        //var_dump($command);
        shell_exec($command);

        return  $output_jpeg;
    }

    private function uploadImgPastaLote(string $caminhoArqImg, int $indice)
    {
        //var_dump("testeart: " . $_FILES['documento']['tmp_name'][$indice]);
        move_uploaded_file($_FILES['documento']['tmp_name'][$indice],  $caminhoArqImg);
    }
    private function uploadPDFPasta(string $caminhoArqImg, int $indice)
    {
        //var_dump("testeart: " . $_FILES['documentoPDF']['tmp_name'][$indice]);
        move_uploaded_file($_FILES['documentoPDF']['tmp_name'][$indice],  $caminhoArqImg);
    }

    public function carregarArquivoservidor($arquivos): string
    {
        //var_dump("are: " . json_decode($arquivos, true));
        $arquivos = json_decode($arquivos);

        $nomeArmario = json_decode($arquivos->listDocumentosServidor[0], true);
        //var_dump($nomeArmario);
        $repository = new DocumentoRepository($this->Conexao());
        // $pasta =   $this->gerarPasta($nomeArmario['nomeArmario']);
        //var_dump("aq " . json_decode($arquivos->listDocumentosServidor[0], true)['imgencontrada']);

        $pasta =  (json_decode($arquivos->listDocumentosServidor[0], true)['imgencontrada'] == "0") ? $this->gerarPasta($nomeArmario['nomeArmario']) :
            $this->retornaCaminhoDocumentoServ(json_decode($arquivos->listDocumentosServidor[0], true)['documentoid']);

        $caminhoRaiz = "";
        //var_dump("ei: " . pathinfo($documentos[0]->arquivo)['dirname']);
        $total = Count($arquivos->listDocumentosServidor);

        for ($i = 0; $i < $total; $i++) {
            $documentos = json_decode($arquivos->listDocumentosServidor[$i], true);
            $tag = json_decode($documentos['tags'], true);
            //var_dump($documentos);
            $origem = $documentos['arquivo'];
            //var_dump($arquivos->listDocumentosServidor[$i]);
            $caminho = $this->subirArquivoss($pasta, $origem);

            $paginasList = [];
            array_push($paginasList, array(
                'documentoid' =>  $documentos['documentoid'],
                'volume' => "1",
                'numpagina' => 1,
                'codexp' => 1,
                'arquivo' => $caminho,
                'filme' => "1",
                'fotograma' => "1",
                'imgencontrada' => "0",
                'armario' => $documentos['idArmario']
            ));


            $idPagina = $repository->cadastrarPagina($paginasList);

            $tagList = [];
            array_push($tagList, array(
                'assunto' => $tag['assunto'],
                'Autor' => $tag['autor'],
                'DataDigitalizacao' => new DateTime(),
                'IdentDocDigital' => $tag['identificador'],
                'RespDigitalizacao' => "",
                'Titulo' => $tag['titulo'],
                'TipoDocumento' =>  $documentos['tipoDocumento'],
                'Hash' => $documentos['b64'],
                'Classe' => $tag['classe'],
                'DataProdDoc' => new DateTime($tag['dataProdDoc']),
                'DestinacaoDoc' => $tag['destinacaoDoc'],
                'Genero' => $tag['genero'],
                'PrazoGuarda' => $tag['prazoGuarda'],
                'Observacoes' => $tag['observacao'],
                'docId' => $documentos['documentoid'],
                'idPagina' => $idPagina
            ));

            $repository->cadastrarMetaTags($tagList, $documentos['documentoid'], $idPagina);
            $caminhoRaiz = pathinfo($documentos['arquivo'])['dirname'];
        }

        array_map('unlink', glob("$caminhoRaiz/*.*"));
        rmdir("{$caminhoRaiz}");

        return $pasta;
    }

    private function gerarOcrs(string $caminhoArq): string
    {
        //var_dump($caminhoArq);
        $pasta = random_int(1, 999999);
        $nomeArquivoOcr = pathinfo($caminhoArq, PATHINFO_DIRNAME) . "/{$pasta}.pdf";
        //var_dump($nomeArquivoOcr);
        /*var_dump($caminhoArq);*/
        (new TesseractOCR($caminhoArq))
            //->userWords("{$this->diretorioLote}user-words.odt")
            ->dpi(300)
            ->configFile('pdf')
            ->setOutputFile($nomeArquivoOcr)
            ->run();

        return $nomeArquivoOcr;
    }

    private function gerarPasta(string $armario): string
    {
        $pasta = random_int(1, 999999);
        mkdir("{$this->diretorio}/{$this->removerAcentos($armario)}/{$pasta}", 0777, true);
        return "{$this->diretorio}/{$this->removerAcentos($armario)}/{$pasta}";
    }

    function removerAcentos($string)
    {
        // Converte os caracteres UTF-8 para ASCII
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

        // Remove qualquer caractere que não seja ASCII (acentos, etc)
        $string = preg_replace('/[^A-Za-z0-9]/', '', $string);

        return $string;
    }
    private function subirArquivoss(string $diretorio, string $caminhoOrigem): string
    {
        //var_dump($diretorio . " - " . $caminhoOrigem);
        return $this->uploadImgPastaLotes($diretorio, $caminhoOrigem);
    }

    private function uploadImgPastaLotes(string $caminhoArq, string $caminhoOrigem): string
    {
        $caminhoArq = $caminhoArq . "/" . basename($caminhoOrigem);
        copy($caminhoOrigem, $caminhoArq);
        return $caminhoArq;
    }

    private function retornaCaminhoDocumentoServ(int $iddoc): string
    {
        $repository = new DocumentoRepository($this->Conexao());
        return  $repository->retornaCaminhoDocumentoServ($iddoc);
    }
}
