<?php

namespace Marinha\Mvc\Infra\Repository;

use Exception;
use PDO;
class Conexao
{
    public static function createConnection(): PDO
    {
        try{

            $host = "localhost";
            $dbname = "sispad";
            $dbuser = "postgres";
            $userpass = "123456";
            $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;";
            
            $connection  = new PDO($dsn, $dbuser, $userpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);            
            
        }catch(Exception $e){
        echo $e;
        }             
        
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $connection;
    }
}