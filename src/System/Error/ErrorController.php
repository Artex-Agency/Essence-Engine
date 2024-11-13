<?php declare(strict_types=1);

namespace Artex\Essence\Engine\System\Error;

use Throwable;
use Psr\Log\LoggerInterface;

/**
 * ErrorController
 * 
 * Manages global error handling settings, controls display behavior, and 
 * coordinates the ErrorHandler and associated components across the framework.
 * It provides a centralized configuration management and dispatches events or 
 * callbacks as needed, based on the environment.
 * 
 * @package   Artex\Essence\Engine\System\Error
 * @category  Error Management
 * @access    public
 * @version   1.0.0
 * @since     1.0.0
 * @link      https://artexessence.com/engine/ Project Website
 * @license   Artex Permissive Software License (APSL)
 */
class ErrorController
{
    /**
     * @var LoggerInterface $logger Logger instance for error logging.
     */
    private LoggerInterface $logger;

    /**
     * @var bool $isProduction Determines if the application is in production mode.
     */
    private bool $isProduction;

    /**
     * @var string $templatePath Path to custom error template for rendering.
     */
    private string $templatePath;

    /**
     * @var ErrorHandler $errorHandler Instance of the ErrorHandler class.
     */
    private ErrorHandler $errorHandler;

    /**
     * @var array $callbacks Array of callbacks to execute on error, if applicable.
     */
    private array $callbacks = [];

    /**
     * ErrorController constructor.
     *
     * Initializes the error controller with necessary dependencies and settings.
     * 
     * @param LoggerInterface $logger       The PSR-3 compatible logger instance.
     * @param bool            $isProduction Whether the app is in production mode.
     * @param string          $templatePath Path to the error template file.
     */
    public function __construct(LoggerInterface $logger, bool $isProduction, string $templatePath)
    {
        $this->logger = $logger;
        $this->isProduction = $isProduction;
        $this->templatePath = $templatePath;
        $this->errorHandler = new ErrorHandler($this->logger, $this->isProduction, $this->templatePath);
    }

    /**
     * Registers the error handler and sets up environment-specific callbacks.
     */
    public function initialize(): void
    {
        $this->errorHandler->register();
        
        // Initialize callbacks or event dispatching based on environment
        if (!$this->isProduction) {
            $this->registerDevelopmentCallbacks();
        } else {
            $this->registerProductionCallbacks();
        }
    }

    /**
     * Registers callbacks specific to development environment.
     */
    private function registerDevelopmentCallbacks(): void
    {
        $this->callbacks[] = function (Throwable $exception) {
            // Example callback for development, such as logging detailed info
            $this->logger->debug("Detailed error info for development", [
                'exception' => $exception,
                'trace' => $exception->getTraceAsString()
            ]);
        };
    }

    /**
     * Registers callbacks specific to production environment.
     */
    private function registerProductionCallbacks(): void
    {
        $this->callbacks[] = function (Throwable $exception) {
            // Example callback for production, such as notifying administrators
            $this->logger->alert("Production error occurred", [
                'exception' => $exception,
                'trace' => $exception->getTraceAsString()
            ]);
        };
    }

    /**
     * Executes registered callbacks when an error occurs.
     *
     * @param Throwable $exception The exception triggering the error.
     */
    public function dispatchCallbacks(Throwable $exception): void
    {
        foreach ($this->callbacks as $callback) {
            if (is_callable($callback)) {
                $callback($exception);
            }
        }
    }

    /**
     * Gets the error handler instance.
     *
     * Allows access to the ErrorHandler for additional configurations if needed.
     * 
     * @return ErrorHandler The current ErrorHandler instance.
     */
    public function getErrorHandler(): ErrorHandler
    {
        return $this->errorHandler;
    }

    /**
     * Adds a custom callback to the list of error callbacks.
     *
     * @param callable $callback Callback function to execute on error.
     */
    public function addCallback(callable $callback): void
    {
        $this->callbacks[] = $callback;
    }

    /**
     * Sets a new error template path dynamically.
     * 
     * @param string $templatePath The path to the new error template.
     */
    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
        $this->errorHandler = new ErrorHandler($this->logger, $this->isProduction, $templatePath);
    }
}