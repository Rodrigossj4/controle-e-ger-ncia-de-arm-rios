<?php

namespace Marinha\Mvc\Services;

use Exception;

use Marinha\Mvc\Infra\Repository\DocumentoRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sabberworm\CSS\CSSList\Document;

class DocumentoServices extends SistemaServices
{
    private $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    private $diretorio = "documentos/";

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


    public function retornarCaminhoDocumento(string $id): string
    {
        try {
            $repository = new DocumentoRepository($this->Conexao());
            $retorno =  $repository->retornarCaminhoDocumento($id);

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

    public function gerarArquivo(string $idPasta, string $tagsList): array
    {
        $caminhoArqImg = "{$this->diretorio}{$idPasta}/";
        $total = count($_FILES['documento']['name']);

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
        $diretorio = "documentos/";
        mkdir("{$diretorio}/{$idPasta}", 0777, true);
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

    private function gerarPDF(int $idPasta, string $diretorio, string $html): string
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
}
