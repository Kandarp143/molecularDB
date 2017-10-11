<?php
require_once 'config.php';

class Database
{

    private static $cont = null;

//    public function __construct()
//    {
//        exit('Init function is not allowed');
//    }

    public static function disconnect()
    {
        self::$cont = null;
    }

    public function beginTransaction()
    {
        /* Begin a transaction, turning off autocommit */
        self::connect();
        //echo '<br/>Begin Transaction<br/>';
        self::$cont->beginTransaction();
    }

    /*
         * Begin Trancation
         */

    public static function connect()
    {
        // One connection through whole application
        if (null == self::$cont) {
            try {
                self::$cont =
                    new PDO("mysql:host=" . dbHost . ";" . "dbname=" . dbName, dbUsername, dbUserPassword);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return self::$cont;
    }

    /*
  * Commit Trancation
  */

    public function commitTransaction()
    {
        if (self::$cont == null) {
            throw  new  Exception('First start transaction');
        } else {
            try {
                //echo '<br/>Commit Transaction<br/>';
                /* Commit the changes */
                self::$cont->commit();
                /* Database connection is now back in autocommit mode */
            } catch (Exception $e) {
                die('Commit Error ' . $e->getMessage());
            }
        }


    }

    /**
     * Insert single record to Database
     *
     * @param $query The query string
     * @param $data Array of data to be insert
     * @return last inserted id
     */
    public function insert($query, $data)
    {
        try {
            $cont = $this->execute($query, $data);
            $result = $cont->lastInsertId();
            //echo '<br/>Data Inserted witb Id' . $result . '<br/>';
            return $result;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Prepare Query and Execute
     *
     * @param $query The query string
     * @param $data Array of data to be insert
     * @return retrun connection
     */
    private function execute($query, $params)

    {

        // Connect to the database
        $cont = $this->connect();
        // Setting it to error mode
        $cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $executor = $cont->prepare($query);
        $executor->execute($params);
        return $cont;

    }

    /**
     * Select single value from Database
     *
     * @param $query The query string
     * @param $params parameters
     * @return return single value
     */
    public function selectValue($column, $table, $prop, $value)
    {
        try {
            $query = "SELECT $column FROM $table WHERE $prop =$value";
            $result = $this->executeSelect($query, null)->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Prepare Query and Execute
     *
     * @param $query The query string
     * @param $data Array of data to be insert
     * @return retrun executor
     */
    private function executeSelect($query, $params)

    {
        // Connect to the database
        $cont = $this->connect();
        // Setting it to error mode
        $cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $executor = $cont->prepare($query);
        $executor->execute($params);
        return $executor;

    }

    /**
     * Select flag weather record exsist or not from Database
     *
     * @param $query The query string
     * @param $params parameters
     * @return return true / false
     */
    public function selectFlag($query, $params)
    {
        try {

            $result = $this->selectRecords($query, $params);

            if (!empty($result)) {
                return 'true';
            } else {
                return 'false';
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Select record/s from Database
     *
     * @param $query The query string
     * @param $params parameters
     * @return return resulf
     */
    public function selectRecords($query, $params)
    {
        try {
            $result = $this->executeSelect($query, $params)->fetchAll();
            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Delete from database
     *
     * @param $query The query string
     * @param $params parameters
     * @return return true / false
     */
    public function delete($query, $params)
    {
        try {
            $this->execute($query, $params);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Update record in database
     *
     * @param $query The query string
     * @param $params parameters
     * @param $data newdata
     * @return return true / false
     */
    public function update($query, $paramAnddata)
    {
        try {
            $this->execute($query, $paramAnddata);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}


?>