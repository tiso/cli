<?php


namespace Helper;


use Exception;
use Migration\DataBase;
use Neoan3\Apps\Db;
use Neoan3\Apps\DbException;

class SqlHelper
{
    private array $usedCredentials;
    private DataBase $db;
    function __construct($credentials, DataBase $db)
    {
        $this->db = $db;
        $credentials['dev_errors'] = true;
        $this->db->connect($credentials);
        $this->usedCredentials = $credentials;
    }
    function databaseTables()
    {
        try{
            $tables = [];
            $call = $this->db->query(">SHOW tables");
            foreach ($call as $table){
                if(isset($table['Tables_in_'.$this->usedCredentials['name']])){
                    $tables[] = $table['Tables_in_'.$this->usedCredentials['name']];
                }
            }
            return $tables;
        } catch (Exception $e){
            $this->error($e);
        }
    }
    function describeTable($table)
    {
        try{
            return $this->db->query(">DESCRIBE `$table`");
        } catch (Exception $e){
            $this->error($e);
        }

    }
    private function error($error)
    {
        echo "SQL issue:\n";
        echo $error->getMessage();
    }
}
