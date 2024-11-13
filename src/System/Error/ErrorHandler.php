<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Error;

use Throwable;
use Psr\Log\LoggerInterface;

/**
 * Error Handler
 * 
 * The ErrorHandler class is responsible for managing errors and exceptions
 * across the Artex Essence Engine. It integrates with the ErrorLevels class
 * to categorize errors for customized logging and response rendering.
 * 
 * @package   Artex\Essence\Engine\System\Error
 * @category  Error Handling
 * @access    public
 * @version   1.1.0
 * @since     1.0.0
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 */
class ErrorHandler
{
    private LoggerInterface $logger;
    private bool $isProduction;
    private string $templatePath;

    /**
     * ErrorHandler constructor.
     *
     * Initializes the ErrorHandler with a logger, production mode flag, and 
     * path to a custom error template for production environments.
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
    }

    /**
     * Registers this error handler to capture all exceptions and errors.
     */
    public function register(): void
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Main exception handler.
     *
     * Handles uncaught exceptions, logs details, and renders an appropriate 
     * error response based on the application mode (development or production).
     *
     * @param Throwable $exception The uncaught exception.
     */
    public function handleException(Throwable $exception): void
    {
        $errorLevel = $exception instanceof \ErrorException ? $exception->getSeverity() : E_ERROR;

        // Log errors based on severity levels
        if (ErrorLevels::isInGroup($errorLevel, ErrorLevels::FATAL)) {
            $this->logger->critical($exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        } else {
            $this->logger->error($exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        // Render output based on environment mode and error severity
        if ($this->isProduction) {
            $this->renderErrorTemplate($exception);
        } else {
            $this->renderDebugOutput($exception);
        }
    }

    /**
     * Error handler for PHP errors.
     *
     * Converts PHP errors into CustomException instances to standardize 
     * handling and allows them to be managed by the exception handler.
     *
     * @param int    $severity Error severity.
     * @param string $message  Error message.
     * @param string $file     File where the error occurred.
     * @param int    $line     Line number of the error.
     * @throws CustomException
     */
    public function handleError(int $severity, string $message, string $file, int $line): void
    {
        if (ErrorLevels::isInGroup($severity, ErrorLevels::FATAL)) {
            throw new CustomException($message, 0, $severity, $file, $line);
        } else {
            // Handle non-fatal errors here, e.g., log them without stopping execution
            $this->logger->warning($message, ['file' => $file, 'line' => $line]);
        }
    }

    /**
     * Shutdown handler for fatal errors.
     *
     * Captures fatal errors on script shutdown and redirects them to the main
     * exception handler for logging and response.
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && ErrorLevels::isInGroup($error['type'], ErrorLevels::FATAL)) {
            $this->handleException(new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }

    /**
     * Renders a production error template if available.
     *
     * Uses a custom error template in production mode to provide a user-friendly
     * message. Falls back to a generic message if the template is unavailable.
     *
     * @param Throwable $exception The exception causing the error.
     */
    private function renderErrorTemplate(Throwable $exception): void
    {
        if (file_exists($this->templatePath)) {
            include $this->templatePath;
            return;
        }
        echo "<h1>An error occurred</h1><p>Something went wrong. Please try again later.</p>";
    }

    /**
     * Renders detailed debug output for development mode.
     *
     * Outputs error details including the stack trace, useful for debugging
     * in a development environment.
     *
     * @param Throwable $exception The exception causing the error.
     */
    private function renderDebugOutput(Throwable $exception): void
    {
        $levelName = ErrorLevels::getLevelName($exception instanceof \ErrorException ? $exception->getSeverity() : E_ERROR);
        
        echo "<h1>Debug Information</h1>";
        echo "<p><strong>Error Level:</strong> {$levelName}</p>";
        echo "<p><strong>Error:</strong> {$exception->getMessage()}</p>";
        echo "<p><strong>File:</strong> {$exception->getFile()}</p>";
        echo "<p><strong>Line:</strong> {$exception->getLine()}</p>";
        echo "<pre>{$exception->getTraceAsString()}</pre>";
    }
}