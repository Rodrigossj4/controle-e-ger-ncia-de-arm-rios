<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\DocumentoRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

class DocumentoServices
{
    private $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    public function __construct()
    {
    }
    public function listaDocumentos(): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->listaDocumentos();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    
    public function cadastrarDocumentos(array $armario): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->cadastrarDocumentos($armario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function alterarDocmentos(array $documento): bool
    {       
        try{
            var_dump($documento);
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->alterarDocumentos($documento);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    
    public function excluirDocumentos(int $id): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->excluirDocumentos($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }

    public function retornarCaminhoDocumento(string $id): string
    { 
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);  
            $returno =  $repository->retornarCaminhoDocumento($id);  
           
            return $returno;

        }catch(Exception $e){
            echo $e;
            return [];
        }
    }

    public function listaPaginas(int $id): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->listarPaginas($id);

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }

    public function cadastrarPaginas(array $pagina): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->cadastrarPagina($pagina);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function excluirPagina(int $id): bool
    {
        var_dump($id);
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->excluirPagina($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
    
    public function alterarPaginas(array $pagina): bool
    {       
        try{
            var_dump($pagina);
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->alterarPagina($pagina);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function gerarArquivo(int $idPasta, string $tags):string
    {
     //var_dump($_FILES['documento']);
     $extensao = strtolower(substr($_FILES['documento']['name'], -4)); 
     
     //$novo_nome = md5(time()) . $extensao; 
   
     $diretorio = "documentos/"; 
     $caminhoArqImg = "{$diretorio}{$idPasta}/".$_FILES['documento']['name'];

     $this->uploadImgPasta($idPasta, $diretorio, $caminhoArqImg);
     $retorno = $this->gerarPDF($idPasta, $tags, $diretorio, $caminhoArqImg);      
     
     $code = file_get_contents($retorno); 
     $encrypted_code = $this->my_encrypt($code, $this->key); 
     file_put_contents("{$retorno}", $encrypted_code); 

     unlink("{$caminhoArqImg}");
     //$this->teste();
     return $retorno;
    }

    public function gerarPastaDoc(int $idPasta):string
    {    
        $diretorio = "documentos/"; 
        mkdir("{$diretorio}/{$idPasta}", 0777, true);
        return "";
    }
    
    private function uploadImgPasta(int $idPasta, string $diretorio, string $caminhoArqImg):void
    {
       mkdir("{$diretorio}/{$idPasta}", 0777, true);
       move_uploaded_file($_FILES['documento'] ['tmp_name'],  $caminhoArqImg);
    }

    private function gerarPDF(int $idPasta, string $tags, string $diretorio, string $caminhoArqImg):string
    {
       $options = new Options();
       $options->setChroot($diretorio);
       $options->setIsRemoteEnabled(true);
 
       $dompdf = new Dompdf($options);
       $dompdf->loadhtml("<meta name='Keywords' content='{$tags}' /><img src='{$caminhoArqImg}' />");
       $dompdf->setPaper('A4');
       $dompdf->render();
      //var_dump($dompdf);
       $pasta = random_int(1,999999);
       $caminhoPDF = "{$diretorio}/{$idPasta}/{$pasta}.pdf";
       file_put_contents($caminhoPDF, $dompdf->output());

       return $caminhoPDF;
    }

    public function abrirArquivo(string $caminhoarquivo):string
    {        
        $encrypted_code = file_get_contents($caminhoarquivo);
        $decrypted_code = $this->my_decrypt($encrypted_code, $this->key); 
       
        file_put_contents('documentos/ttt.pdf', $decrypted_code);
       
        return "documentos/ttt.pdf";
    }
    public function teste(){

        $code = file_get_contents('documentos/100737/51196.pdf'); 
        $encrypted_code = $this->my_encrypt($code, $this->key); 
        file_put_contents('documentos/encrypted.pdf', $encrypted_code); 

        $encrypted_code = file_get_contents('documentos/encrypted.pdf'); 
        $decrypted_code = $this->my_decrypt($encrypted_code, $this->key);
        file_put_contents('documentos/encrypted2.pdf', $decrypted_code); 
    }   

    function my_encrypt($data, $key) {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    function my_decrypt($data, $key) {
        $encryption_key = base64_decode($key);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
}