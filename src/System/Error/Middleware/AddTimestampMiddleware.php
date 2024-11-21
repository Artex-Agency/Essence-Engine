<?php

namespace Essence\System\Error\Middleware;

use Essence\System\Error\ErrorMiddlewareInterface;

/**
 * Example Middleware: Add Timestamp
 *
 * Adds a timestamp to the error context.
 */
class AddTimestampMiddleware implements ErrorMiddlewareInterface
{
    public function handle(array $context, callable $next): array
    {
        $context['timestamp'] = date('Y-m-d H:i:s');
        return $next($context);
    }
}