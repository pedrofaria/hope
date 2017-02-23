<?php
namespace Hope\Database;

use Hope\Database\ConnectorInterface;
use Hope\Database\PdoConnectorAbstract;

/**
 * PostgresConnector
 *
 * @package Hope\Database
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class PostgresConnector extends PdoConnectorAbstract implements ConnectorInterface
{
    /**
     * Get DSN string
     * @return string DSN string
     */
    protected function getDsn()
    {
        $config = $this->config;
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        return $dsn;
    }
}
