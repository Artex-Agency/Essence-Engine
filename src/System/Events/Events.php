<?php declare(strict_types=1);
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Part of the Artex Essence meta-framework.
 *  
 * @link      https://artexessence.com/ Project
 * @license   Artex Permissive Software License
 * @copyright 2024 Artex Agency Inc.
 */
namespace Essence\System;

namespace Essence\System\Events;

use \Essence\System\Events\EventServices;
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
class Events extends EventServices
{
    /** @var Events Contains the singleton instance.  */
    private static ?Events $inst = null;

    /** @var LoggerInterface|null */
    protected ?LoggerInterface $logger = null;

    /** @var array<callable> $actions Custom user-defined error handlers. */
    protected array $events  = [];

    /** @var array<string, array<mixed>> $eventQueue */
    protected array $eventQueue = [];

    /**
     * Add a callback to the specified event.
     *
     * @param  string   $eventName The name of the event hook.
     * @param  callable $callback  The callback function to execute when the event is triggered.
     * @return void
     */
    public function bind(string $eventName, callable $callback): void
    {
        $eventName = $this->normalizeEventName($eventName);

        // Initialize the event array if it doesn't exist
        if (!isset($this->events[$eventName])) {
            $this->events[$eventName] = [];
        }

        // Prevent duplicate callbacks
        if (!in_array($callback, $this->events[$eventName], true)) {
            $this->events[$eventName][] = $callback;
        }
    }

    /**
     * Add a callback to the start of the specified event queue.
     *
     * @param  string   $eventName The name of the event hook.
     * @param  callable $callback  The callback function to execute when the event is triggered.
     * @return void
     */
    public function bindFirst(string $eventName, callable $callback): void
    {
        $eventName = $this->normalizeEventName($eventName);

        if (!isset($this->events[$eventName])) {
            $this->events[$eventName] = [];
        }

        // Prevent duplicate callbacks
        if (!in_array($callback, $this->events[$eventName], true)) {
            array_unshift($this->events[$eventName], $callback);
        }
    }

    /**
     * Trigger an event, executing all bound callbacks in sequence.
     *
     * Logs each triggered event for debugging purposes.
     *
     * @param  string $eventName The name of the event to trigger.
     * @param  mixed  $value     Optional modifiable value passed to each callback.
     * @param  mixed  ...$params Additional parameters to pass to each callback.
     * @return mixed Returns the modified value after all callbacks have been executed.
     */
    public function trigger(string $eventName, mixed $value = null, ...$params): mixed
    {
        $eventName = $this->normalizeEventName($eventName);

        // Log the triggering of the event
        if (isset($this->logger)) {
            $this->logger->info("Event triggered: {$eventName}", [
                'params' => $params,
                'initial_value' => $value,
            ]);
        }

        // Rest of the trigger logic
        if (!isset($this->events[$eventName])) {
            return $value; // No actions bound to this event
        }

        foreach ($this->events[$eventName] as $callback) {
            $value = invoke_callback($callback, array_merge([$value], $params));
        }

        return $value;
    }

    /**
     * Set the logger instance for this event manager.
     *
     * @param  Psr\Log\LoggerInterface $logger The logger to use.
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->setLogger($logger);
        $this->logger = $logger;
    }

    /**
     * Queue an event for deferred processing.
     *
     * @param  string $eventName The name of the event to queue.
     * @param  mixed  $value     Optional modifiable value passed to each callback.
     * @param  mixed  ...$params Additional parameters to pass to each callback.
     * @return void
     */
    public function queueEvent(string $eventName, mixed $value = null, ...$params): void
    {
        $eventName = $this->normalizeEventName($eventName);
        $this->eventQueue[] = compact('eventName', 'value', 'params');
    }

    /**
     * Process all queued events.
     *
     * Executes the callbacks for each queued event.
     *
     * @return void
     */
    public function processQueue(): void
    {
        foreach ($this->eventQueue as $queuedEvent) {
            $this->trigger($queuedEvent['eventName'], $queuedEvent['value'], ...$queuedEvent['params']);
        }

        // Clear the queue after processing
        $this->eventQueue = [];
    }

    /**
     * Remove all callbacks bound to an event.
     *
     * @param  string $eventName The name of the event to clear.
     * @return void
     */
    public function clear(string $eventName): void
    {
        $eventName = $this->normalizeEventName($eventName);
        unset($this->events[$eventName]);
    }

    /**
     * Check if an event has any callbacks bound to it.
     *
     * @param  string $eventName The name of the event.
     * @return bool True if callbacks are bound to the event, false otherwise.
     */
    public function hasListeners(string $eventName): bool
    {
        $eventName = $this->normalizeEventName($eventName);
        return !empty($this->events[$eventName]);
    }

    /**
     * Normalize the event name for consistent storage and matching.
     *
     * @param  string $name The raw event name.
     * @return string The normalized event name.
     */
    protected function normalizeEventName(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9-._]/', '', strtolower($name));
    }

    /**
     * Get all registered events and their callbacks.
     *
     * @return array<string, callable[]> The list of all registered events and their callbacks.
     */
    public function getEvents(): array
    {
        return $this->events;
    }


    protected function eventService_update()
    {

    }

    private function __construct(){}

    /**
     * Invoke
     * @return Events
     */
    public static function invoke(): Events
    { 
        return self::$inst ??= new Events(); 
    }
}