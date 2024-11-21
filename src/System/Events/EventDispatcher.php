<?php

namespace Essence\System\Events;
class EventDispatcher implements EventDispatcherInterface
{
    /** @var array<string, callable[]> */
    private array $listeners = [];

    /**
     * Bind a callback to an event.
     *
     * @param string   $eventName The event name.
     * @param callable $callback  The callback to bind.
     */
    public function bind(string $eventName, callable $callback): void
    {
        $eventName = strtolower($eventName);
        $this->listeners[$eventName][] = $callback;
    }

    public function trigger(string $eventName, ...$params): void
    {
        foreach ($this->listeners[$eventName] ?? [] as $listener) {
            call_user_func($listener, ...$params);
        }
    }
    public function addListener(string $eventName, callable $listener): void
    {
        // Delegate to bind if they are functionally equivalent
        $this->bind($eventName, $listener);
    }

    /**
     * Unbind a callback from an event.
     *
     * @param string   $eventName The event name.
     * @param callable $callback  The callback to unbind.
     */
    public function unbind(string $eventName, callable $callback): void
    {
        $eventName = strtolower($eventName);
        if (isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = array_filter(
                $this->listeners[$eventName],
                fn($listener) => $listener !== $callback
            );
        }
    }

    /**
     * Dispatch an event, invoking all bound callbacks.
     *
     * @param string $eventName The event name.
     * @param mixed  ...$params Parameters to pass to the callbacks.
     */
    public function dispatch(string $eventName, ...$params): void
    {
        $eventName = strtolower($eventName);
        if (!isset($this->listeners[$eventName])) {
            return;
        }
        foreach ($this->listeners[$eventName] as $callback) {
            $callback(...$params);
        }
    }
}