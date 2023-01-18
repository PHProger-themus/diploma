<?php

namespace system\core;

use app\attributes\ToDatabase;
use PDO;
use ReflectionClass;
use system\classes\ArrayHolder;

abstract class DB extends Model
{

    protected string $query = '';
    protected ArrayHolder $dbi;
    protected PDO $db;

    public function __construct(ArrayHolder $data = null, $database = 'db')
    {
        if ($database !== false) {
            if (Cfg::$get->db['active']) {
                $this->setDatabase($database);
            }
            parent::__construct($data);
        }
    }

    protected function setDatabase(string $database = null): void
    {
        $this->dbi = ArrayHolder::new(Cfg::$get->db['databases'][$database]);
        $this->connect();
    }

    /**
     * Выполняет подключение к серверу базы данных.
     */
    protected function connect(): void
    {
        $dsn = "mysql:host={$this->dbi->host};dbname={$this->dbi->database};charset=utf8";
        $this->db = new PDO($dsn, $this->dbi->username, $this->dbi->password);
    }

    protected function debugOrNothing(string $query)
    {
        if (Cfg::$get->db_debug && (!isset(Cfg::$get->console) || !Cfg::$get->console)) {
            db_debug($query);
            echo $this->db->errorInfo()[2];
        }
    }

    protected function execute(string $query = null)
    {
        if (is_null($query)) {
            $query = $this->query;
        }
        $sth = $this->db->prepare($query);
        $sth->execute();
        $this->debugOrNothing($query);
		return $sth;
    }

}