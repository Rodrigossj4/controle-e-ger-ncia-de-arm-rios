<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\Documentos;
use Marinha\Mvc\Models\Paginas;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class DocumentoRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listaDocumentos(): array
    {
        try {
            $sqlQuery = "select d.IdDocumento, d.DocId, d.Nip, d.Semestre, d.Ano, d.IdTipoDoc, d.IdArmario, 
                            dp.Numpg, dp.IdDocPag as idpagina, dp.Arquivo, td.DescTipoDoc, arm.NomeExterno as nomeArmario
                            from {$this->schema}\"Documentos\" d
                            inner join {$this->schema}\"DocumentoPagina\" dp
                            on dp.DocId = d.IdDocumento
                            inner join {$this->schema}\"TipoDocumento\" td
                            on td.IdTipoDoc = d.IdTipoDoc
                            inner join {$this->schema}\"Armarios\" arm
                            on arm.IdArmario = d.IdArmario;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $documentosDataList = $stmt->fetchAll();
            $documentosList = array();
            foreach ($documentosDataList as $documentosData) {
                array_push($documentosList, array(
                    'id' => $documentosData['IdDocumento'],
                    'docid' => $documentosData['DocId'],
                    'nip' => $documentosData['Nip'],
                    'semestre' => $documentosData['Semestre'],
                    'ano' => $documentosData['Ano'],
                    'tipodocumento' => $documentosData['DescTipoDoc'],
                    'armario' => $documentosData['armario'],
                    'numpagina' => $documentosData['numpagina'],
                    'idpagina' => $documentosData['idpagina'],
                    'arquivo' => $documentosData['arquivo'],
                    'desctipo' => $documentosData['desctipo'],
                    'nomeArmario' => $documentosData['nomearmario']
                ));
            };

            return $documentosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function retornarCaminhoDocumento(string $id): string
    {
        try {

            $sqlQuery = "select arquivo from {$this->schema}\"DocumentoPagina\" where IdDocPagina = ?";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $documentosDataList = $stmt->fetchAll();
            $documentosList = array();
            foreach ($documentosDataList as $documentosData) {
                array_push($documentosList, array(
                    'arquivo' => $documentosData['Arquivo']
                ));
            };

            return $documentosData['arquivo'];
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarDocumentos(array $documento): bool
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"Documentos\"(DocId, Nip, Semestre, Ano, IdTipoDoc, FolderId, Armario) values(?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);
            //var_dump($documento);
            foreach ($documento as $dc) {
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
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function alterarDocumentos(array $documento): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"Documentos\" SET DocId= ?, Nip = ?, Semestre = ? , Ano = ? , IdTipoDoc = ?, FolderId = ?, IdArmario = ? WHERE IdDocumento = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($documento as $dc) {
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
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }


    public function excluirDocumentos(int $id): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"Documentos\" where IdDocumento  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function listarPaginas(int $id): array
    {
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"DocumentoPagina\" where DocId = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $paginasDataList = $stmt->fetchAll();
            $paginasList = array();
            foreach ($paginasDataList as $paginasData) {
                array_push($paginasList, array(
                    'id' => $paginasData['IdDocumento'],
                    'documentoid' => $paginasData['DocId'],
                    'volume' => $paginasData['Volume'],
                    'numpagina' => $paginasData['Numpg'],
                    'arquivo' => $paginasData['Arquivo'],
                    'codex' => $paginasData['CodEx'],
                    'filme' => $paginasData['Filme'],
                    'fotograma' => $paginasData['Fotograma'],
                    'imgencontrada' => $paginasData['IMGEncontrada']
                ));
            };

            return $paginasList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function alterarPagina(array $pagina): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"DocumentoPagina\" SET DocId = ?, Volume = ?, Numpg = ? , Arquivo = ? , CodExp = ?, Filme = ?, Fotograma = ?, IMGEncontrada = ? WHERE IdDocPag = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($pagina as $pg) {
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
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function cadastrarPagina(array $pagina): bool
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"DocumentoPagina\"(documentoid, volume, numpagina, codexp, arquivo, filme, fotogramna, imgencontrada) values(?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($pagina as $pg) {
                $paginaData = new Paginas(
                    null,
                    $this->retornaIdDocumentId($pg['documentoid']),
                    $pg['volume'],
                    $pg['numpagina'],
                    $pg['arquivo'],
                    $pg['codexp'],
                    $pg['filme'],
                    $pg['fotograma'],
                    $pg['imgencontrada']
                );
            }

            $stmt->bindValue(1, $paginaData->documentoid());
            $stmt->bindValue(2, $paginaData->volume());
            $stmt->bindValue(3, $paginaData->numpagina());
            $stmt->bindValue(4, $paginaData->codexp());
            $stmt->bindValue(5, $paginaData->arquivo());
            $stmt->bindValue(6, $paginaData->filme());
            $stmt->bindValue(7, $paginaData->fotograma());
            $stmt->bindValue(8, $paginaData->imgencontrada());

            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    private function retornaIdDocumentId(int $documentId): int
    {

        try {
            $sqlQuery = "SELECT id FROM {$this->schema}\"Documentos\" where docid = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $documentId);
            $stmt->execute();


            $documentosDataList = $stmt->fetchAll();


            return $documentosDataList[0]['id'];
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
    public function excluirPagina(int $id): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"DocumentoPagina\" where id  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
