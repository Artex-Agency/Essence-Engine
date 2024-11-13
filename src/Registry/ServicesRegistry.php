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

use RuntimeException;

/**
 * Services Container Facade
 *
 * Connects the global application Registry with the core Container 
 * via extension with a static facade wrapper for the ServiceContainer
 * component.
 *
 * @package    Artex\Essence\Engine\Components
 * @category   Registry
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 */
class ServicesRegistry implements RegistryExtension
{
    /** {@inheritdoc} */
    private static bool $enabled = true;

    /**
     * The service container instance.
     *
     * @var ServiceContainer|null
     */
    protected static ?ServiceContainer $container = null;

    /**
     * Set the ServiceContainer instance for the facade.
     *
     * @param ServiceContainer $container
     * @return void
     */
    public static function setContainer(ServiceContainer $container): void
    {
        if(self::checkExtesion()){
            self::$container = $container;
        }
    }

    /**
     * Get the ServiceContainer instance.
     *
     * @return ServiceContainer
     * @throws RuntimeException If no container has been set.
     */
    protected static function getContainer(): ?ServiceContainer
    {
        // Check extension
        if(!self::checkExtesion()){
            return false;
        }

        if (self::$container === null) {
            throw new RuntimeException("Service container has not been set on the facade.");
        }

        return self::$container;
    }

    /**
     * Magic method to dynamically forward calls to the container.
     *
     * @param string $method The method being called.
     * @param array  $args   The arguments for the method.
     * @return mixed
     * @throws RuntimeException If the container method does not exist.
     */
    public static function __callStatic(string $method, array $args)
    {
        // Check extension
        if(!self::checkExtesion()){
            return false;
        }

        $container = self::getContainer();
        if (!method_exists($container, $method)) {
            throw new RuntimeException("Method {$method} does not exist on the service container.");
        }

        return $container->$method(...$args);
    }

    /**
     * {@inheritdoc}
     */
    private static function checkExtesion():bool
    {
        if (!self::$enabled){
            throw new RuntimeException("Service Regisry has been disabled.");
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function disable():void
    {
        self::resetExtesion()
        self::$enabled = false;
    }

    /**
     * {@inheritdoc}
     */
    protected static function resetExtesion():void
    {
        self::$container = null;
    }

}