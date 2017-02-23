<?php
namespace Hope\Database;

/**
 * ConnectorInterface
 *
 * @package Hope/Database
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
interface ConnectorInterface
{
    /**
     * Get Database connection object
     *
     * @return \PDO PDO connection
     */
    public function getConnection();

    /**
     * Set database configuration
     *
     * @param array $config Configuration data.
     *
     * @return void
     */
    public function setConfig(array $config);

    /**
     * Check if is connected
     *
     * @return boolean Is connected?
     */
    public function isConnected();
}
