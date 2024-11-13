<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Registry;

use \Artex\Essence\Engine\Bootstrap\BootStates;
use \Artex\Essence\Engine\Components\ServiceContainer;
use \Artex\Essence\Engine\Bootstrap\Exceptions\BootstrapException;

/**
 * Base Registry
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\Registry
 * @category   Registry
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
abstract class BaseRegistry
{    
    /** @var ServiceContainer $container Service Container. */
    protected static ?ServiceContainer $container = null;

    /**
     * Register the service container
     *
     * @param ServiceContainer|null $container
     * @return void
     */
    public static function setContainer(?ServiceContainer $container): void
    {
        if(!self::$container){
            self::$container = $container;
        }
    }

    /**
     * Gets the Services Container
     *
     * @return ServiceContainer|null
     */
    public static function getContainer(): ?ServiceContainer
    {
        return self::$container;
    }

    /**
     * Registers a service with the container.
     *
     * @param string $id
     * @param callable|object $concrete A class or callable object.
     * @param boolean $shared
     * @return void
     */
    public static function set(string $id, callable|object $concrete, bool $shared = true): void
    {
        self::$container && 
        self::$container->set($id, $concrete, $shared);
    }

    /**
     * Retrieves a service from the container.
     *
     * @param string $id The unique identifier of the service.
     * @return mixed The resolved service instance.
     */
    public static function get(string $id): mixed
    {
        return ((self::$container) ? self::$container->get($id) : null);
    }

    /**
     * Checks if a service is registered in the container or is deferred.
     *
     * @param string $id The unique identifier of the service.
     * @return bool True if the service is registered or deferred, false otherwise.
     */
    public static function has(string $id): bool
    {
        return ((self::$container) ? self::$container->has($id) : false);
    }

    /**
     * Removes a service from the containers.
     *
     * @param string $id The unique identifier of the service.
     * @return void
     */
    public static function remove(string $id): void
    {
        self::$container && 
        self::$container->remove($id);
    }

    /**
     * Defers the registration of a service
     *
     * @param string  $id The unique identifier of the service.
     * @param Closure $callable A closure that defines the deferred service.
     * @return void
     */
    public static function defer(string $id, Closure $callable): void
    {
        self::$container && 
        self::$container->defer($id, $callable);
    }

    /**
     * Registers a service as a shared singleton
     *
     * @param string $id The unique identifier of the service.
     * @param Closure $callable  A closure that defines the deferred service.
     * @return void
     */
    public static function singleton(string $id, Closure $callable): void
    {
        self::$container && 
        self::$container->singleton($id, $callable);
    }
}