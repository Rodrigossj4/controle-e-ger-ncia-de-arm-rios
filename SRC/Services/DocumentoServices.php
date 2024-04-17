<?php

namespace Marinha\Mvc\Services;

use Exception;

use Marinha\Mvc\Infra\Repository\DocumentoRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            return $repository->listaDocumentos();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarDocumentos(array $armario): bool
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            var_dump($repository);
            return $repository->cadastrarDocumentos($armario);
        } catch (Exception $e) {
            echo $e;
            return false;
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
            return $repository->listarPaginas($id);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarPaginas(array $pagina): bool
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->cadastrarPagina($pagina);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function excluirPagina(int $id): bool
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            return $repository->excluirPagina($id);
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

    public function gerarPdfs(string $tagsList): array
    {
        $total = count($_POST['documentoEscolhido']);
        $paginasList = [];

        // $origemOcr = $this->gerarOcrs($origem);
        for ($i = 0; $i < $total; $i++) {

            $ext = pathinfo($_POST['documentoEscolhido'][$i], PATHINFO_EXTENSION);

            $caminhoArqImgServ = $ext != "pdf" ? $this->gerarOcrs($_POST['documentoEscolhido'][$i]) :
                $_POST['documentoEscolhido'][$i];

            $this->IncluirTags($caminhoArqImgServ);

            $pasta = random_int(1, 999999);
            $nomePDF = "/{$pasta}.pdf";

            $caminhoPDF = filter_input(INPUT_POST, 'Caminho') . "/" . pathinfo($caminhoArqImgServ, PATHINFO_BASENAME);

            move_uploaded_file($caminhoArqImgServ, filter_input(INPUT_POST, 'Caminho') . "" . $nomePDF);

            array_push($paginasList, array(
                'documentoid' =>  filter_input(INPUT_POST, 'IdDocumento'),
                'volume' => "1",
                'numpagina' => $i,
                'codexp' => 1,
                'arquivo' => $caminhoPDF,
                'filme' => "1",
                'fotograma' => "1",
                'imgencontrada' => "1",
                'b64' => ""
            ));
        }

        //var_dump($paginasList);
        return $paginasList;
    }

    private function IncluirTags(string $arquivos)
    {
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile($arquivos);
        $pageId = $pdf->importPage(1);
        $pdf->useTemplate($pageId);

        if (filter_input(INPUT_POST, 'Titulo') != "")
            $pdf->SetTitle(filter_input(INPUT_POST, 'Titulo'));

        if (filter_input(INPUT_POST, 'Autor'))
            $pdf->SetAuthor(filter_input(INPUT_POST, 'Autor'));

        if (filter_input(INPUT_POST, 'Assunto'))
            $pdf->SetSubject(filter_input(INPUT_POST, 'Assunto'));

        if (filter_input(INPUT_POST, 'PalavrasChave'))
            $pdf->SetKeywords(filter_input(INPUT_POST, 'PalavrasChave'));

        $pdf->Output($arquivos, 'F');
    }

    public function gerarArquivo(string $idPasta, string $tagsList): array
    {
        $caminhoArqImg = "{$this->diretorio}{$idPasta}/";
        $total = count(filter_input(INPUT_POST, 'documentoEscolhido'));

        $conteudo = "";

        for ($i = 0; $i < $total; $i++) {
            $caminhoArqImgServ = $caminhoArqImg . $_FILES['documento']['name'][$i];
            $caminhoArqImgTemp = $this->uploadImgPasta($idPasta, $this->diretorio, $caminhoArqImgServ, $i);
            $conteudo .= "<img src='{$caminhoArqImgServ}' /><br>";
        }

        $html = "<html><header>" .
            "{$tagsList}" .
            "</header><body>" .
            "{$conteudo}" .
            "</body></html>";

        $retorno = $this->gerarPDF($idPasta, $this->diretorio, $html);

        $paginasList = [];
        array_push($paginasList, array(
            'documentoid' =>  filter_input(INPUT_POST, 'IdDocumento'),
            'volume' => "1",
            'numpagina' => $total,
            'codexp' => 1,
            'arquivo' => $retorno,
            'filme' => "1",
            'fotograma' => "1",
            'imgencontrada' => "1"
        ));

        for ($i = 0; $i < $total; $i++) {
            $caminhoArqImgServ = $caminhoArqImg . $_FILES['documento']['name'][$i];
            unlink("{$caminhoArqImgServ}");
        }

        return $paginasList;
    }

    public function criptografarArquivo(string $retorno)
    {
        $code = file_get_contents($retorno);
        $encrypted_code = $this->my_encrypt($code, $this->key);
        file_put_contents("{$retorno}", $encrypted_code);
    }
    public function gerarPastaDoc(int $idPasta): string
    {
        mkdir("{$this->diretorio}/{$idPasta}", 0777, true);
        return "";
    }

    private function uploadImgPasta(int $idPasta, string $diretorio, string $caminhoArqImg, int $indice): string
    {
        if (!is_dir("{$diretorio}/{$idPasta}")) {
            mkdir("{$diretorio}/{$idPasta}", 0777, true);
        }
        move_uploaded_file($_FILES['documento']['tmp_name'][$indice],  $caminhoArqImg);
        return "{$diretorio}/{$idPasta}" . $_FILES['documento']['tmp_name'][$indice];
    }

    private function gerarPDF(string $diretorio, string $html): string
    {
        //var_dump("gerar");
        /*$options = new Options();
        $options->setChroot($diretorio);
        $options->setIsRemoteEnabled(true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadhtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();*/
        $pasta = random_int(1, 999999);
        $caminhoPDF = "{$diretorio}/{$pasta}.pdf";
        //file_put_contents($caminhoPDF, $dompdf->output());

        return $caminhoPDF;
    }

    private function gerarPDF_old(int $idPasta, string $diretorio, string $html): string
    {
        $options = new Options();
        $options->setChroot($diretorio);
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        $dompdf->loadhtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $pasta = random_int(1, 999999);
        $caminhoPDF = "{$diretorio}/{$idPasta}/{$pasta}.pdf";
        file_put_contents($caminhoPDF, $dompdf->output());

        return $caminhoPDF;
    }

    public function abrirArquivo(string $caminhoarquivo, string $cifrado): string
    {
        if ($cifrado == "true") {

            $encrypted_code = file_get_contents($caminhoarquivo);
            $decrypted_code = $this->my_decrypt($encrypted_code, $this->key);

            file_put_contents('documentos/ttt.pdf', $decrypted_code);
            return "documentos/ttt.pdf";
        } else {
            file_put_contents('documentos/ttt.pdf', file_get_contents($caminhoarquivo));
            return "documentos/ttt.pdf";
        }
    }
    public function teste()
    {

        $code = file_get_contents('documentos/100737/51196.pdf');
        $encrypted_code = $this->my_encrypt($code, $this->key);
        file_put_contents('documentos/encrypted.pdf', $encrypted_code);

        $encrypted_code = file_get_contents('documentos/encrypted.pdf');
        $decrypted_code = $this->my_decrypt($encrypted_code, $this->key);
        file_put_contents('documentos/encrypted2.pdf', $decrypted_code);
    }

    function my_encrypt($data, $key)
    {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    function my_decrypt($data, $key)
    {
        $encryption_key = base64_decode($key);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

    public function carregarArquivosDiretorioTemporario(string $nip, string $tipoArquivo): string
    {
        $pasta = $this->gerarPastaTemporaria($nip, $tipoArquivo);
        if (count($_FILES['documento']['name']) > 0)
            $this->subirArquivos($pasta);

        if (count($_FILES['documentoPDF']['name']) > 0)
            $this->subirArquivosPDF($pasta);

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

        for ($i = 0; $i < $total; $i++) {

            $nomeArquivo = pathinfo($_FILES['documento']['name'][$i], PATHINFO_BASENAME);
            $arquivoExtensao = pathinfo($_FILES['documento']['name'][$i], PATHINFO_EXTENSION);

            $caminhoArqImgServ = $diretorio . "/" . $_FILES['documento']['name'][$i];
            $this->uploadImgPastaLote($caminhoArqImgServ, $i);

            if ($arquivoExtensao == "TIF") {
                $novoNome = $diretorio . "/" . pathinfo($_FILES['documento']['name'][$i], PATHINFO_FILENAME) . ".jpg";
                $this->TratarTifParaJpeg($caminhoArqImgServ, $novoNome);
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

    public function carregarArquivoservidor(string $arquivos): string
    {

        $repository = new DocumentoRepository($this->Conexao());
        $pasta = $this->gerarPasta();
        $documentos = json_decode($arquivos);
        //var_dump("ei: " . pathinfo($documentos[0]->arquivo)['dirname']);
        //var_dump($documentos->arquivo);


        //var_dump($values);
        $origem = $documentos->arquivo;
        //var_dump($origem);
        $caminho = $this->subirArquivoss($pasta, $origem);

        $paginasList = [];
        array_push($paginasList, array(
            'documentoid' =>  $documentos->documentoid,
            'volume' => "1",
            'numpagina' => 1,
            'codexp' => 1,
            'arquivo' => $caminho,
            'filme' => "1",
            'fotograma' => "1",
            'imgencontrada' => "1"
        ));
        $repository->cadastrarPagina($paginasList);


        $caminhoRaiz = pathinfo($documentos->arquivo)['dirname'];

        //array_map('unlink', glob("$caminhoRaiz/*.*"));
        //rmdir("{$caminhoRaiz}");


        return $pasta;
    }

    public function carregarArquivoservidor2(string $arquivos, string $documentoid): string
    {
        // var_dump($arquivos);
        $repository = new DocumentoRepository($this->Conexao());
        $pasta = $this->gerarPasta();

        //var_dump("ei: " . pathinfo($documentos[0]->arquivo)['dirname']);
        $origem = $arquivos;
        //var_dump($origemOcr);
        $caminho = $this->subirArquivoss($pasta, $origem);

        $paginasList = [];
        array_push($paginasList, array(
            'documentoid' =>  $documentoid,
            'volume' => "1",
            'numpagina' => 1,
            'codexp' => 1,
            'arquivo' => $caminho,
            'filme' => "1",
            'fotograma' => "1",
            'imgencontrada' => "1"
        ));
        $repository->cadastrarPagina($paginasList);


        $caminhoRaiz = pathinfo($arquivos)['dirname'];

        array_map('unlink', glob("$caminhoRaiz/*.*"));
        rmdir("{$caminhoRaiz}");


        return $pasta;
    }
    private function gerarOcrs(string $caminhoArq): string
    {
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

    private function gerarPasta(): string
    {
        $pasta = random_int(1, 999999);
        mkdir("{$this->diretorio}{$pasta}", 0777, true);
        return "{$this->diretorio}{$pasta}";
    }

    private function subirArquivoss(string $diretorio, string $caminhoOrigem): string
    {
        //var_dump($diretorio." - " . $caminhoOrigem);
        return $this->uploadImgPastaLotes($diretorio, $caminhoOrigem);
    }

    private function uploadImgPastaLotes(string $caminhoArq, string $caminhoOrigem): string
    {
        $caminhoArq = $caminhoArq . "/" . basename($caminhoOrigem);
        copy($caminhoOrigem, $caminhoArq);
        return $caminhoArq;
    }
}
