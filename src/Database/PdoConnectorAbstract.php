<?php

namespace Hope\Database;

use Hope\Database\Entity;

/**
 * PdoConnectorAbstract
 *
 * @package Hope/Database
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
abstract class PdoConnectorAbstract
{
    private $pdo;
    protected $config;
    private $entity;

    /**
     * Get DSN string
     * @return string DSN string
     */
    abstract protected function getDsn();

    /**
     * Get Database connection object
     * @return \PDO PDO connection
     */
    public function getConnection()
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
        
        return $this->pdo;
    }

    /**
     * Set database configuration
     *
     * @param array $config Configuration data.
     *
     * @return void
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Define default entity
     *
     * @param string $entity Entity.
     *
     * @return void
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Start PDO connection
     *
     * @return void
     */
    private function connect()
    {
        $config = $this->config;
        $dsn = $this->getDsn();
        $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
    }

    /**
     * Check if is connected
     *
     * @return boolean Is connected?
     */
    public function isConnected()
    {
        return !is_null($this->pdo);
    }

    /**
     * Bind params to prepared queries
     *
     * @param \PDOStatement $sth   Statement Handler.
     * @param array         $binds Data to bind.
     *
     * @return void
     */
    protected function bindParams(\PDOStatement $sth, array $binds)
    {
        foreach ($binds as $key => $value) {
            $sth->bindValue($key, $value);
        }
    }

    /**
     * Execute query at PDO
     *
     * @param  string     $sql         SQL.
     * @param  array|null $binds       Data to bind.
     * @param  string     $fetch_class Class for result.
     *
     * @return PDO\Statement
     *
     * @throws \Exception PDO error.
     */
    protected function executePdo(string $sql, array $binds = null, string $fetch_class = null)
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
        
        $sth = $this->getConnection()->prepare($sql);

        if (!is_null($fetch_class)) {
            $sth->setFetchMode(
                \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                $fetch_class
            );
        }

        if (!is_null($binds)) {
            $this->bindParams($sth, $binds);
        }

        $rs = $sth->execute();

        if ($rs === false) {
            throw new \Exception($sth->errorInfo()[2], $sth->errorCode());
        }

        return $sth;
    }

    /**
     * Execute query
     *
     * @param  string     $sql         SQL.
     * @param  array|null $binds       Data to bind.
     * @param  string     $fetch_class Class for result.
     *
     * @return array|Entity Result
     */
    public function query(string $sql, array $binds = null, string $fetch_class = null)
    {
        $fetch_class = (is_null($fetch_class) ? $this->entity : $fetch_class);
        $sth = $this->executePdo($sql, $binds, $fetch_class);
        
        return $sth->fetchAll();
    }

    /**
     * Execute query
     *
     * @param  string     $sql   SQL.
     * @param  array|null $binds Data to bind.
     *
     * @return integer If Insert, last inserted ID, else, total affected rows.
     */
    public function execute(string $sql, array $binds = null)
    {
        $sth = $this->executePdo($sql, $binds);

        if (preg_match('/^INSERT/', $sql)) {
            return $this->pdo->lastInsertId($this->entity::TABLENAME . '_id_seq');
        }
        
        return $sth->rowCount();
    }
}
