<?php

namespace Essence\System\Events;

/**
 * Event Dispatcher Interface
 *
 * Defines the contract for an event dispatcher.
 */
interface EventDispatcherInterface
{
    /**
     * Dispatch an event with optional context.
     *
     * @param string $eventName The name of the event.
     * @param array  $context   The event context data.
     * @return void
     */
    public function dispatch(string $eventName, array $context = []): void;

    /**
     * Register a listener for a specific event.
     *
     * @param string   $eventName The name of the event to listen to.
     * @param callable $listener  The listener callback.
     * @return void
     */
    public function addListener(string $eventName, callable $listener): void;
}