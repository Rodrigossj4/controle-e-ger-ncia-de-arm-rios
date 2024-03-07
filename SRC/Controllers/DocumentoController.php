<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\ArmarioServices;
use Marinha\Mvc\Services\DocumentoServices;
use Marinha\Mvc\Models\PDF;

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

    public function documento()
    {
        // $this->validarSessao();

        $service = new DocumentoServices();
        $Documento = $service->exibirDocumento(filter_input(INPUT_POST, 'idDocumento'));
        $CaminhoDoc = $this->retornarCaminhoDocumento(filter_input(INPUT_POST, 'idDocumento'));
        $paginasList = $service->listaPaginas(filter_input(INPUT_POST, 'idDocumento'));

        require __DIR__ . '../../Views/documento/documento.php';
    }

    public function criptografarArquivo()
    {
        $service = new DocumentoServices();
        $service->criptografarArquivo($this->retornarCaminhoDocumento(filter_input(INPUT_POST, 'docid')));
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

    public function retornarCaminhoDocumento(int $docid): string
    {
        $service = new DocumentoServices();

        return $service->retornarCaminhoDocumento($docid);
    }

    public function cadastrarPagina(): bool
    {
        $service = new DocumentoServices();
        $tags = filter_input(INPUT_POST, 'Nip') . ", " .  filter_input(INPUT_POST, 'ano');

        $paginasList = $service->gerarArquivo(filter_input(INPUT_POST, 'IdPasta'), $tags);

        $service = new DocumentoServices();
        return $service->cadastrarPaginas($paginasList);
    }

    public function listarPaginas()
    {
        $service = new DocumentoServices();
        $paginasList = $service->listaPaginas(filter_input(INPUT_POST, 'iddocumento'));
        require __DIR__ . '../../Views/documento/index.php';
    }

    public function excluirPagina(): bool
    {
        $service = new DocumentoServices();
        $service->excluirPagina(filter_input(INPUT_POST, 'idPagina'));
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
        $caminho = $this->retornarCaminhoDocumento(filter_input(INPUT_GET, 'docid'));
        $cifrado = filter_input(INPUT_GET, 'cf');
        $service = new DocumentoServices();
        $arquivo  = $service->abrirArquivo($caminho, $cifrado);
        require __DIR__ . '../../Views/documento/visualizar.php';
    }

    public function asssinarDigital()
    {
        $diretorio = getcwd() . '/';
        $nomeCertPFX = 'Documentos/certificado_2000359152.pfx';
        $documentoParaAssinar = 'Documentos/teste.pdf';
        $nomeCertCRT = 'Documentos/tcpdf.crt';
        $password = 'RoD@2109';



        // * Gera o .crt a partir do .pfx
        if (!file_exists('tcpdf.crt')) {
            shell_exec("openssl pkcs12 -in {$nomeCertPFX} -out {$nomeCertCRT} -nodes -passin pass: {$password}");
        }



        $p = file_get_contents($nomeCertCRT);
        var_dump($p);
        //Endereço do arquivo do certificado
        //Obs.: Tentei usar o certificado no formato PFX e não funcionou
        //Para converter use o comando no Prompt do Windows ou Terminal do Linux:
        //openssl pkcs12 -in certificado.pfx -out tcpdf.crt -nodes

        // $cert = '/home/tuchinski/Documentos/apoema/pdf/tcpdf.crt';

        $pkcs12 = file_get_contents($nomeCertPFX);

        // aqui a gente pega o certificado .crt, mas esse cara a gente tem que gerar
        $cert = openssl_x509_read($p);
        $cert_parsed = openssl_x509_parse($cert, true);

        // print_r($cert_parsed);

        $nome_cpf = explode(":", $cert_parsed['subject']['CN']);



        $res = [];
        $openSSL = openssl_pkcs12_read($pkcs12, $res, $password);
        if (!$openSSL) {
            throw new Exception("Error: " . openssl_error_string());
        }

        // // this is the CER FILE
        // file_put_contents('CERT.cer', $res['pkey'].$res['cert'].implode('', $res['extracerts']));

        // // this is the PEM FILE
        // $cert = $res['cert'].implode('', $res['extracerts']);
        // file_put_contents('KEY.pem', $cert);




        // aqui a gente pega o certificado .pfx
        if (openssl_pkcs12_read($pkcs12, $cert_info, $password)) {
            // echo "Certificate read\n";
        } else {
            echo "Error: Unable to read the cert store.\n";
            exit;
        }

        //Informações da assinatura - Preencha com os seus dados
        $info = array(
            'Name' => '',
            'Location' => '',
            'Reason' => '',
            'ContactInfo' => '',
        );

        $pdf = new PDF($nome_cpf[0], $nome_cpf[1]);
        //Configura a assinatura. Para saber mais sobre os parâmetros
        //consulte a documentação do TCPDF, exemplo 52.
        //Não esqueça de mudar 'senha' para a senha do seu certificado
        // var_dump($cert);
        //Importa uma página
        $numPages = $pdf->setSourceFile($documentoParaAssinar);
        // print_r($pdf->numPages());
        // print_r($numPages);

        for ($i = 0; $i < $numPages; $i++) {
            # code...
            $pdf->AddPage();
            // $text = "Documento assinado digitalmente por <b>$nome_cpf[0]</b>, CPF $nome_cpf[1]";
            // $pdf->writeHTML($text, true, 0, true, 0);
            $tplId = $pdf->importPage($i + 1);
            // $pdf->setSignature('file://'.$cert, 'file://'.realpath($cert), '','', 2, $info);
            $pdf->setSignature($cert_info['cert'], $cert_info['pkey'], '', '', 2, $info);


            $pdf->useTemplate($tplId, 0, 0); //Importa nas medidas originais
            // print a line of text
            $pdf->setSignatureAppearance(10, 10, 10, 10, 1);
        }

        $pdf->Output();
    }
}
