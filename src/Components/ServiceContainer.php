<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Components;

use Closure;
use RuntimeException;
use \Psr\Container\ContainerInterface;

/**
 * Service Container
 *
 * Implements a PSR-11 compliant service container to manage 
 * dependencies within the Essence Core framework. Supports deferred 
 * (lazy) instantiation, singleton and transient services, and callable
 * -based service definitions for flexible dependency management and 
 * service resolution.
 *
 * @package    Artex\Essence\Engine\Components
 * @category   Engine Components
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 */
class ServiceContainer implements ContainerInterface
{
    private static ?ServiceContainer $instance = null;

    /**
     * Registered service definitions.
     *
     * Each entry is an associative array with keys 'concrete' 
     * (the service itself) and 'shared' (a boolean flag).
     *
     * @var array<string, array<string, mixed>>
     */
    private array $services = [];

    /**
     * Cached instances of shared services (singletons).
     *
     * @var array<string, mixed>
     */
    private array $instances = [];

    /**
     * Deferred service definitions.
     *
     * Services listed here will not be registered immediately but will 
     * be initialized only on first access.
     *
     * @var array<string, Closure>
     */
    private array $deferred = [];

    /**
     * Singleton method to retrieve the single instance of ServiceContainer.
     *
     * @return ServiceContainer
     */
    public static function getInstance(): ServiceContainer
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Registers a service with the container.
     *
     * @param string $id  The unique identifier of the service.
     * @param callable|object $concrete A callable (for deferred loading) or 
     *                                  object representing the service.
     * @param bool $shared Whether the service should be treated as a singleton.
     * @return void
     */
    public function set(string $id, callable|object $concrete, bool $shared = true): void
    {
        $this->services[$id] = [
            'concrete' => $concrete,
            'shared'   => $shared,
        ];
    }

    /**
     * Defers the registration of a service until it is first accessed.
     *
     * @param string  $id       The unique identifier of the service.
     * @param Closure $callable A closure that defines the deferred service.
     * @return void
     */
    public function defer(string $id, Closure $callable): void
    {
        $this->deferred[$id] = $callable;
    }

    /**
     * Retrieves a service from the container.
     *
     * Instantiates services only on first access if they are callable, 
     * allowing deferred instantiation. Singleton services are stored 
     * for future access, while transient services return new instances.
     *
     * @param string $id The unique identifier of the service.
     * @return mixed The resolved service instance.
     * @throws RuntimeException If the service is not found in the container.
     */
    public function get(string $id): mixed
    {
        // Check if service is deferred and register it if needed
        if (isset($this->deferred[$id])) {
            $this->set($id, $this->deferred[$id]($this), true);
            unset($this->deferred[$id]);
        }

        // Return the existing instance if it exists
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->services[$id])) {
            throw new RuntimeException("Service '{$id}' not found in the container.");
        }

        $definition = $this->services[$id]['concrete'];
        $instance = is_callable($definition) ? $definition($this) : $definition;

        // Cache the instance if the service is shared (singleton)
        if ($this->services[$id]['shared']) {
            $this->instances[$id] = $instance;
        }

        return $instance;
    }

    /**
     * Checks if a service is registered in the container or is deferred.
     *
     * @param string $id The unique identifier of the service.
     * @return bool True if the service is registered or deferred, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]) || isset($this->deferred[$id]);
    }

    /**
     * Removes a service from the container, including any cached instances.
     *
     * @param string $id The unique identifier of the service.
     * @return void
     */
    public function remove(string $id): void
    {
        unset($this->services[$id], $this->instances[$id], $this->deferred[$id]);
    }

    /**
     * Clears all registered services and instances from the container.
     *
     * This will reset the container, effectively removing all services that
     * have been registered or instantiated.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->services = [];
        $this->instances = [];
        $this->deferred = [];
    }

    /**
     * Registers a service as a shared singleton with deferred instantiation.
     *
     * Singleton services are instantiated only on first access and are cached 
     * for subsequent calls.
     *
     * @param string  $id       The unique identifier of the service.
     * @param Closure $callable A closure that defines the service.
     * @return void
     */
    public function singleton(string $id, Closure $callable): void
    {
        $this->set($id, $callable, true);
    }

    /**
     * Registers a non-shared service with deferred instantiation.
     *
     * A new instance is returned each time the service is requested, offering 
     * transient service management.
     *
     * @param string  $id       The unique identifier of the service.
     * @param Closure $callable A closure that defines the service.
     * @return void
     */
    public function transient(string $id, Closure $callable): void
    {
        $this->set($id, $callable, false);
    }
}