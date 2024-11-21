<?php

namespace Essence\System\Error;

/**
 * ErrorMiddleware Interface
 *
 * Defines the contract for middleware that processes error data.
 */
interface ErrorMiddlewareInterface
{
    /**
     * Handle the error context.
     *
     * @param  array $context The error context, including message, severity, etc.
     * @param  callable $next Callback to the next middleware in the chain.
     * @return array Modified error context.
     */
    public function handle(array $context, callable $next): array;
}