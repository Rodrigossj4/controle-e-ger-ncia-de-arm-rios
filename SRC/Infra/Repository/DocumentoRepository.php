<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\Documentos;
use Marinha\Mvc\Models\Paginas;
use Marinha\Mvc\Models\MetaTags;
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
            $sqlQuery = "select d.\"IdDocumento\", d.\"DocId\", d.\"Nip\", d.\"Semestre\", d.\"Ano\", d.\"IdTipoDoc\", d.\"IdArmario\", 
                         td.\"DescTipoDoc\", arm.\"NomeExterno\" as nomeArmario
                            from {$this->schema}\"Documentos\" d
                            inner join {$this->schema}\"TipoDocumento\" td
                            on td.\"IdTipoDoc\" = d.\"IdTipoDoc\"
                            inner join {$this->schema}\"Armarios\" arm
                            on arm.\"IdArmario\" = d.\"IdArmario\"
                            order by d.\"IdDocumento\" desc Limit 20  FOR UPDATE;";


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
                    'tipodocumento' => $documentosData['IdTipoDoc'],
                    'armario' => $documentosData['IdArmario'],
                    'desctipo' => $documentosData['DescTipoDoc'],
                    'nomeArmario' => $documentosData['nomearmario']
                ));
            };

            return $documentosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function exibirDocumento(int $id): array
    {
        try {
            $sqlQuery = "select d.\"IdDocumento\", d.\"DocId\", d.\"Nip\", d.\"Semestre\", d.\"Ano\", d.\"IdTipoDoc\", d.\"FolderId\", d.\"IdArmario\", 
             td.\"DescTipoDoc\", arm.\"NomeExterno\" as nomeArmario
                            from {$this->schema}\"Documentos\" d
                            inner join {$this->schema}\"TipoDocumento\" td
                            on td.\"IdTipoDoc\" = d.\"IdTipoDoc\"
                            inner join {$this->schema}\"Armarios\" arm
                            on arm.\"IdArmario\" = d.\"IdArmario\"
                            where d.\"IdDocumento\" = ? Limit 1 FOR UPDATE;";

            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
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
                    'idPasta' => $documentosData['FolderId'],
                    'tipodocumento' => $documentosData['IdTipoDoc'],
                    'armario' => $documentosData['IdArmario'],
                    'desctipo' => $documentosData['DescTipoDoc'],
                    'nomeArmario' => $documentosData['nomearmario']
                ));
            };
            return $documentosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function BuscarDocumentos($documentosListParam): array
    {
        try {

            $predicado = $this->MontaPredicado($documentosListParam);

            $sqlQuery = "select d.\"IdDocumento\", d.\"DocId\", d.\"Nip\", d.\"Semestre\", d.\"Ano\", d.\"IdTipoDoc\", d.\"IdArmario\", 
                         td.\"DescTipoDoc\", arm.\"NomeExterno\" as nomeArmario
                            from {$this->schema}\"Documentos\" d
                            inner join {$this->schema}\"TipoDocumento\" td
                            on td.\"IdTipoDoc\" = d.\"IdTipoDoc\"
                            inner join {$this->schema}\"Armarios\" arm
                            on arm.\"IdArmario\" = d.\"IdArmario\"
                            where {$predicado}
                            order by d.\"IdDocumento\" desc Limit 100 FOR UPDATE;";

            //var_dump($sqlQuery);
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $documentosDataList = $stmt->fetchAll();
            $documentosList = array();
            foreach ($documentosDataList as $documentosData) {

                $totalPagina = $this->TotalPaginasDocumento($documentosData['DocId']);

                if($totalPagina == 0){
                    //Remove a refencia desse documento pois não possui nenhum arquivo
                    $sqlQuery = "delete FROM {$this->schema}\"Documentos\" where \"IdDocumento\"  = ?;";
                    $stmt = $this->pdo->prepare($sqlQuery);
                    $stmt->bindValue(1, $documentosData['DocId']);
                    $stmt->execute();
                }

                array_push($documentosList, array(
                    'id' => $documentosData['IdDocumento'],
                    'docid' => $documentosData['DocId'],
                    'nip' => $documentosData['Nip'],
                    'semestre' => $documentosData['Semestre'],
                    'ano' => $documentosData['Ano'],
                    'tipodocumento' => $documentosData['IdTipoDoc'],
                    'armario' => $documentosData['IdArmario'],
                    'desctipo' => $documentosData['DescTipoDoc'],
                    'nomeArmario' => $documentosData['nomearmario'],
                    'quantidadepaginas' => $totalPagina, //$this->TotalPaginasDocumento($documentosData['DocId'])
                ));
            };
            //var_dump($documentosList);
            return $documentosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function MontaPredicado($documentosListParam): string
    {
        $sentenca = "";
        $i = 0;
        foreach ($documentosListParam as $prop => $val) {
            $i++;

            if ((strlen((string)$val["armario"]) != 0) && ($val["armario"] != 0)) {
                $sentenca .= "d.\"IdArmario\" = {$val["armario"]} ";
            }

            if ((strlen((string)$val["tipodoc"]) != 0) and (($val["tipodoc"]) != 0)) {
                if (strlen($sentenca) != 0)
                    $sentenca .= " and ";

                $sentenca .= "d.\"IdTipoDoc\" = {$val["tipodoc"]} ";
            }

            if (strlen((string)$val["nip"]) != 0) {
                $nip = str_replace('.', '', $val["nip"]);
                if (strlen($sentenca) != 0)
                    $sentenca .= " and ";

                $sentenca .= "d.\"Nip\" = '{$nip}' ";
            }

            if ((strlen((string)$val["semestre"]) != 0) && ($val["semestre"] != 0)) {
                if (strlen($sentenca) != 0)
                    $sentenca .= " and ";

                $sentenca .= "d.\"Semestre\" = {$val["semestre"]} ";
            }

            if (strlen((string)$val["ano"]) != 0) {
                if (strlen($sentenca) != 0)
                    $sentenca .= " and ";

                $sentenca .= "d.\"Ano\" = {$val["ano"]} ";
            }
        }
        //var_dump("val: " . $sentenca);
        return $sentenca;
    }

    public function BuscarDocumentosPorTipo(int $idTipoDocumento): array
    {
        try {
            $sqlQuery = "SELECT \"IdDocumento\" FROM {$this->schema}\"Documentos\" where \"IdTipoDoc\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idTipoDocumento);
            $stmt->execute();

            $documentosDataList = $stmt->fetchAll();

            return $documentosDataList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function retornarCaminhoDocumento(string $id, int $pagina): string
    {
        try {
            $sqlQuery = "select \"Arquivo\" from {$this->schema}\"DocumentoPagina\" where \"IdDocPag\" = ? ";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $pagina);
            $stmt->execute();

            $documentosDataList = $stmt->fetchAll();
            $documentosList = array();
            foreach ($documentosDataList as $documentosData) {
                array_push($documentosList, array(
                    'arquivo' => $documentosData['Arquivo']
                ));
            };

            return $documentosList == null ? "" : $documentosList[0]['arquivo'];
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarDocumentos(array $documento): int
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"Documentos\"(\"DocId\", \"Nip\", \"Semestre\", \"Ano\", \"IdTipoDoc\", \"FolderId\", \"IdArmario\") values(?, ?, ?, ?, ?, ?, ?) RETURNING \"IdDocumento\";";
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

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function updateDocIdDocumento($idDocumento)
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"Documentos\" SET \"DocId\"= ? WHERE \"IdDocumento\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            $stmt->bindValue(1, $idDocumento);
            $stmt->bindValue(2, $idDocumento);

            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function retornaCaminhoDocumentoServ(int $iddoc): string
    {
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"DocumentoPagina\" where \"DocId\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $iddoc);
            $stmt->execute();

            $paginasDataList = $stmt->fetchAll();

            //var_dump(pathinfo($paginasDataList[0]["Arquivo"], PATHINFO_DIRNAME));

            return pathinfo($paginasDataList[0]["Arquivo"], PATHINFO_DIRNAME);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function alterarDocumentos(array $documento): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"Documentos\" SET \"DocId\"= ?, \"Nip\" = ?, \"Semestre\" = ? , \"Ano\" = ? , \"IdTipoDoc\" = ?, \"FolderId\" = ?, \"IdArmario\" = ? WHERE \"IdDocumento\" = ?";
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


    public function excluirDocumentos(string $id): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"Documentos\" where \"IdDocumento\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    //d
    public function listarPaginas(int $id): array
    {
        //var_dump("e " . $id);
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"DocumentoPagina\" where \"DocId\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $paginasDataList = $stmt->fetchAll();

            if(count($paginasDataList) == 0){
                //Remove a refencia desse documento pois não possui nenhum arquivo
                $sqlQuery = "delete FROM {$this->schema}\"Documentos\" where \"IdDocumento\"  = ?;";
                $stmt = $this->pdo->prepare($sqlQuery);
                $stmt->bindValue(1, $id);
                $stmt->execute();
            }

            $paginasList = array();
            foreach ($paginasDataList as $paginasData) {
                array_push($paginasList, array(
                    'id' => $paginasData['IdDocPag'],
                    'documentoid' => $paginasData['DocId'],
                    'volume' => $paginasData['Volume'],
                    'numpagina' => $paginasData['Numpg'],
                    'arquivo' => $paginasData['Arquivo'],
                    'codex' => $paginasData['CodExp'],
                    'filme' => $paginasData['Filme'],
                    'fotograma' => $paginasData['Fotograma'],
                    'imgencontrada' => $paginasData['IMGEncontrada'],
                    'idarmario' => $paginasData['IdArmario'],
                    'flgassinado' => $paginasData['FLGAssinado'],
                    'flgcriptografado' => $paginasData['FLGCriptografado']
                ));
            };

            //var_dump($paginasList);
            return $paginasList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function TotalPaginasDocumento(int $id): int
    {
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"DocumentoPagina\" where \"DocId\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $paginasDataList = $stmt->fetchAll();


            return COUNT($paginasDataList);
        } catch (Exception $e) {
            echo $e;
            return 0;
        }
    }
    public function carminhoArquivos(int $id): array
    {
        try {
            $sqlQuery = "SELECT \"DocId\", \"Arquivo\" FROM {$this->schema}\"DocumentoPagina\" where \"IdDocPag\" = ? Limit 1 FOR UPDATE;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $paginasDataList = $stmt->fetchAll();

            //var_dump($paginasDataList);
            return $paginasDataList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
    public function alterarPagina(array $pagina): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"DocumentoPagina\" SET \"DocId\" = ?, \"Volume\" = ?, \"Numpg\" = ? , \"Arquivo\" = ? , \"CodExp\" = ?, \"Filme\" = ?, \"Fotograma\" = ?, \"IMGEncontrada\" = ? WHERE \"IdDocPag\" = ?";
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
                    $pg['id'],
                    1,
                    false,
                    false
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

    public function cadastrarPagina(array $pagina): int
    {
        try {
            //var_dump($pagina);
            $sqlQuery = "INSERT INTO {$this->schema}\"DocumentoPagina\"(\"DocId\", \"Volume\", \"Numpg\", \"CodExp\", \"Arquivo\", \"Filme\", \"Fotograma\", \"IMGEncontrada\", \"IdArmario\", \"IdDocumento\") values(?, ?, ?, ?, ?, ?, ?, ?, ?,?) RETURNING \"IdDocPag\";";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($pagina as $pg) {

                $paginaData = new Paginas(
                    null,
                    $pg['documentoid'],
                    $pg['volume'],
                    $pg['numpagina'],
                    $pg['arquivo'],
                    $pg['codexp'],
                    $pg['filme'],
                    $pg['fotograma'],
                    $pg['imgencontrada'],
                    $pg['armario'],
                    false,
                    false
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
            $stmt->bindValue(9, $paginaData->idarmario());
            $stmt->bindValue(10, $paginaData->documentoid());
            $stmt->execute();

            return $stmt->fetchColumn();;
        } catch (Exception $e) {
            echo $e;
            return 0;
        }
    }

    private function retornaIdDocumentId(int $documentId): int
    {

        try {
            $sqlQuery = "SELECT \"IdDocumento\" FROM {$this->schema}\"Documentos\" where \"DocId\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $documentId);
            $stmt->execute();


            $documentosDataList = $stmt->fetchAll();


            return $documentosDataList[0]['IdDocumento'];
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
    public function excluirPagina(int $id): bool
    {
        try {
            //var_dump($this->retornarCaminhoDocumento("", $id));
            unlink($this->retornarCaminhoDocumento("", $id));
            $this->excluirMetadados($id);
            $sqlQuery = "delete FROM {$this->schema}\"DocumentoPagina\" where \"IdDocPag\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function excluirMetadados(int $id): bool
    {
        try {
            // $file = $this->retornarCaminhoDocumento("", $id);
            // if (file_exists($file)) {
                //unlink($this->retornarCaminhoDocumento("", $id));
            // }
            $sqlQuery = "delete FROM {$this->schema}\"Metadados\" where \"IdPagina\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function AlterarDocumentoDaPagina(int $idDocumentoNovo, int $idPagina, $novoCaminho)
    {
        $sqlQuery = "UPDATE {$this->schema}\"DocumentoPagina\" SET \"DocId\" = ?, \"Arquivo\" = ?, \"IdDocumento\" = ? WHERE \"IdDocPag\" = ?";
        $stmt = $this->pdo->prepare($sqlQuery);

        $stmt->bindValue(1, $idDocumentoNovo);
        $stmt->bindValue(2, $novoCaminho);
        $stmt->bindValue(3, $idDocumentoNovo);
        $stmt->bindValue(4, $idPagina);
        $stmt->execute();
    }

    public function cadastrarMetaTags(array $tags, int $docId, int $docPagina)
    {
        try {
            $sqlQuery = "INSERT INTO {$this->schema}\"Metadados\"(
                \"Assunto\", \"Autor\", \"DataDigitalizacao\", \"IdentDocDigital\", \"RespDigitalizacao\", \"Titulo\", \"TipoDocumento\", \"Hash\", \"Classe\", \"DataProdDoc\", \"DestinacaoDoc\", \"Genero\", \"PrazoGuarda\", \"Observacoes\", \"IdDocumento\", \"IdPagina\") values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($tags as $tg) {

                $paginaData = new MetaTags(
                    null,
                    $tg['assunto'],
                    $tg['Autor'],
                    $tg['DataDigitalizacao'],
                    $tg['IdentDocDigital'],
                    $tg['RespDigitalizacao'],
                    $tg['Titulo'],
                    $tg['TipoDocumento'],
                    $tg['Hash'],
                    $tg['Classe'],
                    $tg['DataProdDoc'],
                    $tg['DestinacaoDoc'],
                    $tg['Genero'],
                    $tg['PrazoGuarda'],
                    $tg['Observacoes'],
                    $tg['docId'],
                    $tg['idPagina'],
                );
            }

            $stmt->bindValue(1, $paginaData->getAssunto());
            $stmt->bindValue(2, $paginaData->getAutor());
            $stmt->bindValue(3, $paginaData->getDataDigitalizacao()->format('Y-m-d H:i:s'));
            $stmt->bindValue(4, $paginaData->getIdentDocDigital());
            $stmt->bindValue(5, $paginaData->getRespDigitalizacao());
            $stmt->bindValue(6, $paginaData->getTitulo());
            $stmt->bindValue(7, $paginaData->getTipoDocumento());
            $stmt->bindValue(8, $paginaData->getHash());
            $stmt->bindValue(9, $paginaData->getClasse());
            $stmt->bindValue(10, $paginaData->getDataProdDoc()->format('Y-m-d H:i:s'));
            $stmt->bindValue(11, $paginaData->getDestinacaoDoc());
            $stmt->bindValue(12, $paginaData->getGenero());
            $stmt->bindValue(13, $paginaData->getPrazoGuarda());
            $stmt->bindValue(14, $paginaData->getObservacoes());
            $stmt->bindValue(15, $paginaData->getIdDocumento());
            $stmt->bindValue(16, $paginaData->getIdPagina());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function AlterarDocumentoDaMetaTags(int $idDocumento, int $idPagina)
    {
        $sqlQuery = "UPDATE {$this->schema}\"Metadados\" SET \"IdDocumento\" = ? WHERE \"IdPagina\" = ?";
        $stmt = $this->pdo->prepare($sqlQuery);

        $stmt->bindValue(1, $idDocumento);
        $stmt->bindValue(2, $idPagina);
        $stmt->execute();
    }

    public function verificaHash(string $hash): int
    {
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"Metadados\" where \"Hash\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $hash);
            $stmt->execute();
            $hash = $stmt->fetchAll();
            return COUNT($hash);
        } catch (Exception $e) {
            echo $e;
            return 0;
        }
    }
}
