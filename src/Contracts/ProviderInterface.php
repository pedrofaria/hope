<?php
namespace Hope\Contracts;

use Hope\Application;

/**
 * Contract of Provider
 *
 * @package Hope\Contracts
 *
 * @author Pedro Faria <eu@eusouopedro.com>
 */
interface ProviderInterface
{
    /**
     * Callable static method used to bind a container
     *
     * @param Application $app Application object.
     *
     * @return void
     */
    public static function register(Application $app);
}
