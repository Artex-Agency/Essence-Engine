<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\System\Events;

use \Essence\System\Logger\LoggerInterface;

/**
 * Event Services
 *
 * Description
 * 
 * @package    Essence\System\Events
 * @category   Events
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
abstract class EventServices
{
    /** @var array<callable> $runtimeEvents  user-defined error handlers. */
    protected array $runtimeEvents = [];

    /** @var array<callable> $systemEvents Custom user-defined error handlers. */
    protected array $systemEvents = [];

    /** @var array<callable> $errorHandlers Custom user-defined error handlers. */
    protected array $errorHandlers = [];

    /** @var LoggerInterface|null Optional logger instance for error logging. */
    protected ?LoggerInterface $logger = null;

    /**
     * Executes a callable with provided parameters.
     *
     * A versatile handler for executing callables (*functions, closures, methods*) 
     * with support for both instance and static methods. This function is optimized 
     * for direct execution and avoids the overhead associated with `call_user_func`.
     * 
     * @param callable $callback The callable to be executed. This can be a function name, 
     *                           a closure, or an array representing a method.
     * @param array    $params   An optional array of parameters to pass to the callable. Defaults to an empty array.
     *
     * @return mixed Returns the result of the executed callable.
     */
    public function executeCallable(callable $callback, array $params = []): mixed
    {
        if (is_array($callback)) {
            [$class, $method] = $callback;
            return is_object($class)
                ? $class->$method(...$params) // Instance method
                : $class::$method(...$params); // Static method
        }
        return $callback(...$params);
    }

/* ************************ 

|  EVENTS   |
=============
-  SERVER

-  SYSTEM
_____________

-  SERVICE
 
-  CUSTOM
============= 

************************ */


    /**
     * Set Logger
     *
     * @param LoggerInterface|null $logger The logger instance.
     * @return void
     */
    public function setLogger(?LoggerInterface $logger = null): void
    {
        $this->logger = $logger;
    }

    /**
     * Get Logger
     *
     * @return LoggerInterface|null $logger The logger instance.
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Update services when language or locale changes.
     *
     * @return void
     */
    abstract protected function eventService_update(): void;

}