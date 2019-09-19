<?php

namespace Core;

use PDO;
use PDOException;

class DataBase
{
    private $pdo;
    private $sSQL;
    private $host = DB_HOST;
    private $usr = DB_USER;
    private $pwd = DB_PASS;
    private $dbname = DB_NAME;
    private $port = DB_PORT;
    private $driver = DB_DRIVER;
    private $isConnected = false;
    private $parametros;

    public function __construct()
    {
        $this->Connect();
        $this->parametros = array();
    }

    private function Connect()
    {
        $dsn = $this->driver . ':dbname=' . $this->dbname . ';host=' . $this->host . ';port=' . $this->port . '';
        $pwd = $this->pwd;
        $usr = $this->usr;

        /**
         *    El array $options es muy importante para tener un PDO bien configurado
         *
         *    1. PDO::ATTR_PERSISTENT => false: sirve para usar conexiones persistentes
         *      se puede establecer a true si se quiere usar este tipo de conexión. Ver: https://es.stackoverflow.com/a/50097/29967
         *      En la práctica, el uso de conexiones persistentes fue problemático en algunos casos
         *    2. PDO::ATTR_EMULATE_PREPARES => false: Se usa para desactivar emulación de consultas preparadas
         *      forzando el uso real de consultas preparadas.
         *      Es muy importante establecerlo a false para prevenir Inyección SQL. Ver: https://es.stackoverflow.com/a/53280/29967
         *    3. PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION También muy importante para un correcto manejo de las excepciones.
         *      Si no se usa bien, cuando hay algún error este se podría escribir en el log revelando datos como la contraseña !!!
         *    4. PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'": establece el juego de caracteres a utf8,
         *      evitando caracteres extraños en pantalla. Ver: https://es.stackoverflow.com/a/59510/29967
         */

        $options = array(
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
        );

        try {
            $this->pdo = new PDO($dsn, $usr, $pwd, $options);
            $this->isConnected = true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $DbLog = new DbLog();
            $DbLog->write($this->error);
        }
    }

    public function closeConnection()
    {
        $this->pdo = null;
    }

    public function column($sql, $params = null)
    {
        $this->Init($sql, $params);
        $Columns = $this->sSQL->fetchAll(PDO::FETCH_NUM);

        $column = null;

        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }

        return $column;

    }

    private function Init($sql, $parametros = "")
    {
        if (!$this->isConnected) {
            $this->Connect();
        }
        try {
            $this->sSQL = $this->pdo->prepare($sql);
            $this->bindMas($parametros);
            if (!empty($this->parametros)) {
                foreach ($this->parametros as $param => $value) {
                    if (is_int($value[1])) {
                        $type = PDO::PARAM_INT;
                    } else if (is_bool($value[1])) {
                        $type = PDO::PARAM_BOOL;
                    } else if (is_null($value[1])) {
                        $type = PDO::PARAM_NULL;
                    } else {
                        $type = PDO::PARAM_STR;
                    }
                    $this->sSQL->bindValue($value[0], $value[1], $type);
                }
            }
            $this->sSQL->execute();
        } catch (PDOException $e) {
            $DbLog = new DbLog();
            $DbLog->write($e->getMessage() . " :query: " . $sql);
            die();
        }
        $this->parametros = array();
    }

    public function bindMas($parray)
    {
        if (empty($this->parametros) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }

    public function bind($parametro, $valor)
    {
        $this->parametros[sizeof($this->parametros)] = [$parametro, $valor];
    }

    public function row($sql, $params = null, $fetchmode = PDO::FETCH_ASSOC)
    {
        $this->Init($sql, $params);
        $result = $this->sSQL->fetch($fetchmode);
        $this->sSQL->closeCursor();
        return $result;
    }

    public function rowCount($sql, $params = null, $fetchmode = PDO::FETCH_OBJ)
    {
        $this->Init($sql, $params);
        $result = $this->sSQL->rowCount($fetchmode);
        $this->sSQL->closeCursor();
        return $result;
    }

    public function single($sql, $params = null)
    {
        $this->Init($sql, $params);
        $result = $this->sSQL->fetchColumn();
        $this->sSQL->closeCursor();
        return $result;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function executeTransaction()
    {
        return $this->pdo->commit();
    }

    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    public function execute($sql)
    {
        if ($this->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function query($sql, $params = null, $fetchmode = PDO::FETCH_ASSOC)
    {
        $sql = trim(str_replace("\r", " ", $sql));
        $this->Init($sql, $params);
        $rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $sql));
        $statement = strtolower($rawStatement[0]);
        if ($statement === 'select' || $statement === 'show') {
            return $this->sSQL->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sSQL->rowCount();
        } else {
            return NULL;
        }
    }

}