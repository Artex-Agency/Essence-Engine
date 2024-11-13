<?php declare(strict_types=1);

namespace Artex\Essence\Engine\System\Core;

use Exception;

/**
 * GlobalStateManager
 * 
 * Manages and tracks global system states, enabling components across the 
 * framework to respond dynamically to state changes. Provides centralized 
 * access to state information for optimized and context-aware decision-making.
 * 
 * @package   Artex\Essence\Engine\System\Core
 * @category  Global State Management
 * @access    public
 * @version   1.0.0
 * @since     1.0.0
 */
class GlobalStateManager
{
    /**
     * @var array<string, bool> $states Stores the current states of the system.
     */
    private static array $states = [
        'error' => false,
        'debug' => false,
        'maintenance' => false,
        'performance' => true,
    ];

    /**
     * @var array<string, callable[]> $listeners Maps states to an array of listeners.
     */
    private static array $listeners = [];

    /**
     * Checks if a specific state is active.
     *
     * @param string $state The state to check (e.g., 'error', 'debug').
     * @return bool True if the state is active; otherwise, false.
     * @throws Exception If the state does not exist.
     */
    public static function isStateActive(string $state): bool
    {
        if (!array_key_exists($state, self::$states)) {
            throw new Exception("State '{$state}' is not defined.");
        }
        
        return self::$states[$state];
    }

    /**
     * Activates a specific state.
     *
     * @param string $state The state to activate.
     * @throws Exception If the state does not exist.
     */
    public static function activateState(string $state): void
    {
        self::setState($state, true);
    }

    /**
     * Deactivates a specific state.
     *
     * @param string $state The state to deactivate.
     * @throws Exception If the state does not exist.
     */
    public static function deactivateState(string $state): void
    {
        self::setState($state, false);
    }

    /**
     * Sets a specific state and triggers listeners if the state has changed.
     *
     * @param string $state The state to set.
     * @param bool   $value The new state value (true or false).
     * @throws Exception If the state does not exist.
     */
    public static function setState(string $state, bool $value): void
    {
        if (!array_key_exists($state, self::$states)) {
            throw new Exception("State '{$state}' is not defined.");
        }
        
        if (self::$states[$state] !== $value) {
            self::$states[$state] = $value;
            self::triggerListeners($state);
        }
    }

    /**
     * Adds a listener for a specific state.
     *
     * Registers a callback to be executed when the specified state changes.
     *
     * @param string   $state    The state to listen for.
     * @param callable $listener The callback to execute on state change.
     */
    public static function addListener(string $state, callable $listener): void
    {
        if (!isset(self::$listeners[$state])) {
            self::$listeners[$state] = [];
        }
        
        self::$listeners[$state][] = $listener;
    }

    /**
     * Triggers all listeners for a specific state.
     *
     * @param string $state The state whose listeners should be triggered.
     */
    private static function triggerListeners(string $state): void
    {
        if (!empty(self::$listeners[$state])) {
            foreach (self::$listeners[$state] as $listener) {
                $listener();
            }
        }
    }

    /**
     * Adds a new state to the GlobalStateManager.
     *
     * @param string $state The name of the new state.
     * @param bool   $initialValue The initial value of the state.
     */
    public static function addState(string $state, bool $initialValue = false): void
    {
        if (!array_key_exists($state, self::$states)) {
            self::$states[$state] = $initialValue;
        }
    }
}