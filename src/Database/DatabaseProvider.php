<?php
namespace Hope\Database;

use Hope\Application;
use Hope\Contracts\ProviderInterface;
use Hope\Database\Connector;
use Hope\Database\ConnectorInterface;
use Hope\Exceptions\InvalidDatabaseConnectorException;

/**
 * DatabaseProvider
 *
 * @package Hope/Database
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
class DatabaseProvider implements ProviderInterface
{
    /**
     * Registering Database Class
     *
     * @param Application $app Hope Application.
     *
     * @return void
     * @throws InvalidDatabaseConnectorException Invalid connection type.
     */
    public static function register(Application $app)
    {
        $config = $app->config('database');

        switch ($config['connection']) {
            case 'postgres':
                $class = PostgresConnector::class;
                break;
            
            default:
                throw new InvalidDatabaseConnectorException;
        }

        $app->bind(ConnectorInterface::class, function () use ($config, $class) {
            $db = new $class();
            $db->setConfig($config);
            return $db;
        });
    }
}
