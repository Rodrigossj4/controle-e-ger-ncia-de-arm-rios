<?php

namespace Marinha\Mvc\Infra\Repository;
use Marinha\Mvc\Models\Documentos;
use Marinha\Mvc\Models\Paginas;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class DocumentoRepository
{
    private $pdo;
   
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        
    }

    public function listaDocumentos(): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM documento;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();
       
            $documentosDataList = $stmt->fetchAll();
            $documentosList = array();
            foreach ($documentosDataList as $documentosData) {                   
                array_push($documentosList, array(
                    'id' => $documentosData['id'],
                    'docid' => $documentosData['docid'],
                    'nip' => $documentosData['nip'],
                    'semestre' => $documentosData['semestre'],
                    'ano' => $documentosData['ano'],
                    'tipodocumento' => $documentosData['tipodocumento'],
                    'folderid' => $documentosData['folderid'],
                    'armario' => $documentosData['armario']
                ));
            };

            return $documentosList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }
    
    public function cadastrarDocumentos(array $documento): bool
    {       
        try{
  
            $sqlQuery = 'INSERT INTO documento(docid, nip, semestre, ano, tipodocumento, folderid, armario) values(?, ?, ?, ?, ?, ?, ?);';
            $stmt = $this->pdo->prepare($sqlQuery);
            
            foreach($documento as $dc){
                $documentoData = new Documentos(
                    null,
                    $dc['docid'],
                    $dc['nip'],
                    $dc['semestre'],
                    $dc['ano'],
                    $dc['tipodoc'],
                    $dc['folderid'],
                    $dc['armario']
                );
            }
           
            $stmt->bindValue(1, $documentoData->docid());
            $stmt->bindValue(2, $documentoData->nip());
            $stmt->bindValue(3, $documentoData->semestre());
            $stmt->bindValue(4, $documentoData->ano());
            $stmt->bindValue(5, $documentoData->tipodocumento());
            $stmt->bindValue(6, $documentoData->folderid());
            $stmt->bindValue(7, $documentoData->armario());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function alterarDocumentos(array $documento): bool
    {       
        try{
  
            $sqlQuery = 'UPDATE documento SET docid = ?, nip = ?, semestre = ? , ano = ? , tipodocumento = ?, folderid = ?, armario = ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($documento as $dc){
                $documentoData = new Documentos(
                    $dc['id'],
                    $dc['docid'],
                    $dc['nip'],
                    $dc['semestre'],
                    $dc['ano'],
                    $dc['tipodocumento'],
                    $dc['folderid'],
                    $dc['armario']
                );
            }
            
            $stmt->bindValue(1, $documentoData->docid());
            $stmt->bindValue(2, $documentoData->nip());
            $stmt->bindValue(3, $documentoData->semestre());
            $stmt->bindValue(4, $documentoData->ano());
            $stmt->bindValue(5, $documentoData->tipodocumento());
            $stmt->bindValue(6, $documentoData->folderid());
            $stmt->bindValue(7, $documentoData->armario());
            $stmt->bindValue(8, $documentoData->id());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    
    public function excluirDocumentos(int $id): bool
    {
        try{
            $sqlQuery = 'delete FROM documento where id  = ?;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();                   

            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }  
    }

    public function listarPaginas(int $id): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM documentoPagina where documentoid = ?;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();
       
            $paginasDataList = $stmt->fetchAll();
            $paginasList = array();
            foreach ($paginasDataList as $paginasData) {                   
                array_push($paginasList, array(
                    'id' => $paginasData['id'],
                    'documentoid' => $paginasData['documentoid'],
                    'volume' => $paginasData['volume'],
                    'numpagina' => $paginasData['numpagina'],
                    'arquivo' => $paginasData['arquivo'],
                    'codex' => $paginasData['codex'],
                    'filme' => $paginasData['filme'],
                    'fotograma' => $paginasData['fotograma'],
                    'imgencontrada' => $paginasData['imgencontrada']
                ));
            };

            return $paginasList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }
    
    public function alterarPagina(array $pagina): bool
    {       
        try{
  
            $sqlQuery = 'UPDATE documentoPagina SET documentoid = ?, volume = ?, numpagina = ? , arquivo = ? , codexp = ?, filme = ?, fotograma = ?, imgencontrada = ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($pagina as $pg){
                $paginaData = new Paginas(
                    $pg['documentoid'],
                    $pg['volume'],
                    $pg['numpagina'],
                    $pg['codexp'],
                    $pg['arquivo'],
                    $pg['filme'],
                    $pg['fotograma'],
                    $pg['imgencontrada'],
                    $pg['id']
                );
            }
            
            $stmt->bindValue(1, $paginaData->documentoid());
            $stmt->bindValue(2, $paginaData->volume());
            $stmt->bindValue(3, $paginaData->numpagina());
            $stmt->bindValue(4, $paginaData->arquivo());
            $stmt->bindValue(5, $paginaData->codexp());
            $stmt->bindValue(6, $paginaData->filme());
            $stmt->bindValue(7, $paginaData->fotograma());  
            $stmt->bindValue(8, $paginaData->imgencontrada());
            $stmt->bindValue(9, $paginaData->id());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function cadastrarPagina(array $pagina): bool
    {       
        try{
  
            $sqlQuery = 'INSERT INTO documentoPagina(docid, volume, numpagina, codexp, arquivo, filme, fotograma, imgencontrada) values(?, ?, ?, ?, ?, ?, ?, ?);';
            $stmt = $this->pdo->prepare($sqlQuery);
            
            foreach($pagina as $pg){
                $paginaData = new Paginas(
                    null,
                    $pg['documentoid'],
                    $pg['volume'],
                    $pg['numpagina'],
                    $pg['codexp'],
                    $pg['arquivo'],
                    $pg['filme'],
                    $pg['fotograma'],
                    $pg['imgencontrada']
                    
                );
            }
           
            $stmt->bindValue(1, $paginaData->documentoid());
            $stmt->bindValue(2, $$paginaData->volume());
            $stmt->bindValue(3, $paginaData->numpagina());
            $stmt->bindValue(4, $paginaData->codexp());
            $stmt->bindValue(5, $paginaData->arquivo());
            $stmt->bindValue(6, $paginaData->filme());
            $stmt->bindValue(7, $paginaData->fotograma());
            $stmt->bindValue(8, $paginaData->imgencontrada());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function excluirPagina(int $id): bool
    {
        try{
            $sqlQuery = 'delete FROM documentoPagina where id  = ?;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();                   

            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }  
    }
}