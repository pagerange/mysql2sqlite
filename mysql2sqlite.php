<?php

/**
 * MySQL2SQLite 
 * 
 * This is a simple utility to migrate data from 
 * MySQL tables to SQLite tables.
 * 
 * @author steve@pagerange.com
 * @created 2024-11-24
 * 
 */

/* DO NOT EDIT BELOW THIS LINE!
--------------------------------------------------------------------------- */

$config =  require __DIR__ . '/config.php';

class Mysql2Sqlite
{

    /**
     * Configuration options
     *
     * @var array
     */
    private $c = [];

    public function __construct(array $config) 
    {
        $this->c = $config;
    }

    /**
     * getMySQLConnection
     * 
     *
     * @return \PDO Object for MySQL connection
     */
    function getMysqlConnection():\PDO
    {
        $dbh = new \PDO('mysql:host=localhost;dbname=' . 
            $this->c['MYSQL_DBNAME'], 
            $this->c['MYSQL_DBUSER'], $this->c['MYSQL_DBPASS']);
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $dbh;
    }

    /**
     * getSQLiteConnection
     *
     * @return \PDO Object of SQLite connection
     */
    function getSqliteConnection():\PDO
    {
        $dbh = new \PDO('sqlite:' . $this->c['SQLITE_DBFILE']);
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $dbh;
    }

    /**
     * Get a list of all tables in a MySQL database that will be
     * migrated automatically ... the table list is left empty
     * in the config file
     *
     * @return array
     */
    function getTableList():array
    {
        $dbh = $this->getMysqlConnection();
        $query = "SHOW TABLES";
        $stmt = $dbh->query($query);
        $results = $stmt->fetchAll();
        $tables = array_map(function($row) {
            return array_pop($row);
        }, $results);
        return $tables;
    }

    /**
     * Get all data for one MySQL table
     *
     * @param string $table
     * @return array
     */
    function getTableData(string $table):array
    {
        $dbh = $this->getMysqlConnection();
        $query = "SELECT * FROM {$table}";
        $stmt = $dbh->query($query);
        return $stmt->fetchAll() ?? [];
    }

    /**
     * Get list of field names to use in insert clause
     *
     * @param array $row
     * @return string
     */
    function getFieldString(array $row):string
    {
        $key_array = array_keys($row);
        return implode(',', $key_array);
    }

    /**
     * Get list of field names to use in insert clause
     *
     * @param array $row
     * @return string
     */
    function getParamsArray(array $row):array
    {
        $params = [];
        foreach($row as $key => $value) {
            $new_key = ':' . $key;
            $params[$new_key] = $value;
        }
        return $params;
    }

    /**
     * Get list of field names to use in insert clause
     *
     * @param array $row
     * @return string
     */
    function getParamsString(array $row):string
    {
        $key_array = array_keys($row);
        $params = array_map(function($val) { 
            return ':' . $val; }, $key_array);
        return implode(',',$params);
    }

    /**
     * Migrate all the data from a table using the configuration options
     * provided.
     *
     * @param [type] $table
     * @return void
     */
    function migrateTableData($table):void
    {
        
        $data = $this->getTableData($table);
        if(empty($data)) {
            // we only migrate tables that have rows of data
            return;
        }
        $field_string = $this->getFieldString($data[0]);
        $params_string = $this->getParamsString($data[0]);
        // Dynamically bind all data to be inserted to
        // named parameters
        $query = "INSERT INTO {$table}
                ({$field_string})
                VALUES
                ({$params_string})";
        $dbh = $this->getSqliteConnection();
        foreach($data as $row) {
            $stmt = $dbh->prepare($query);
            // key/value pairs of params and field values
            $params = (array) $this->getParamsArray($row);
            $stmt->execute($params);
        }
    }



    /**
     * Run the application
     *
     * @return string
     */
    function run():string
    {
        $str = "";
        $str .= "\n";
        if(!empty($this->c['MYSQL_TABLES'])) {
            $tables = $this->c['MYSQL_TABLES'];
        } else {
            $tables = $this->getTableList();
        }
        foreach($tables as $table) {
            $this->migrateTableData($table);
            $str .= "Migrated $table!\n";
        }
        $str .= "\n";
        $str .= "DONE!\n";
        return $str;
    }

}

// Run the application

$mysql2sqlite = new Mysql2Sqlite($config);

try {

    echo $mysql2sqlite->run();

} catch(Exception $e) {
    echo "\n\nException thrown on line " . $e->getLine() . ' in ' . 
        basename($e->getFile()) . ":\n";
    echo $e->getMessage();
    echo "\n\n";
    die;
}
