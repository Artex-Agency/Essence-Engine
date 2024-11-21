<?php
namespace Essence\System\Error;

use Essence\System\Events\EventDispatcherInterface;

class Error implements ErrorMonitorInterface
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function recordError(string $message, int $severity): void
    {
        $context = [
            'message' => $message,
            'severity' => $severity,
            'timestamp' => time(),
        ];

        $context = $this->processMiddleware($context);

        $this->hasErrors = true;
        $this->isFatal = $this->isFatal || ($severity & ErrorLevels::FATAL);
        $this->errorLog[] = $context;

        // Dispatch error event
        $this->dispatcher->dispatch('error.recorded', $context);
    }
}