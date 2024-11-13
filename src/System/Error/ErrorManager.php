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
use Artex\Essence\Engine\System\Error\ErrorLevels;
use Artex\Essence\Engine\System\Error\Parsers\ErrorParserFactory;
use Artex\Essence\Engine\System\Error\Parsers\ErrorParserInterface;

/**
 * Error Manager
 *
 * Manages application errors, including logging, configurable 
 * output formats (CLI, HTML, JSON), and custom handling callbacks. 
 * The manager supports both production and development modes, 
 * displaying or suppressing errors as needed.
 *
 * @package    Artex\Essence\Engine\System\Error
 * @category   Error Management
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @see        ErrorLevels
 */
class ErrorManager
{
    /** @var array Configuration settings for error handling and output. */
    private array $config;

    /** @var LoggerInterface|null Optional logger instance for error logging. */
    private ?LoggerInterface $logger;

    /** @var array<callable> Custom user-defined error handlers. */
    private array $customHandlers = [];

    /**
     * ErrorManager constructor.
     *
     * @param array $config Configuration options for error handling and output.
     *                      - display_errors: bool       Show errors if true (default: true).
     *                      - error_level: int           Error level filter (default: ErrorLevels::RUNTIME).
     *                      - log_errors: bool           Log errors if true (default: true).
     *                      - output_format: string      Output format (html, cli, json).
     * @param LoggerInterface|null $logger Optional PSR-3 logger instance for error logging.
     */
    public function __construct(array $config = [], ?LoggerInterface $logger = null)
    {
        $this->config = array_merge([
            'errors_enabed'  => true,
            'display_errors' => true,
            'error_level'    => ErrorLevels::RUNTIME,
            'log_errors'     => true,
            'output_format'  => 'html', // Options: 'cli', 'html', 'json'
        ], $config);
        
        $this->logger = $logger;
    }


    public function disable()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * Primary error handler to manage, log, and display errors.
     *
     * @param Throwable $exception The exception or error instance to handle.
     * @return void
     */
    public function handle(Throwable $exception): void
    {
        // Log the error if logging is enabled and a logger is available
        if ($this->config['log_errors'] && $this->logger) {
            $this->logError($exception);
        }

        // Execute custom handlers for additional processing
        foreach ($this->customHandlers as $handler) {
            $handler($exception);
        }

        // Display error if enabled
        if ($this->config['display_errors']) {
            $this->displayError($exception);
        }
    }

    /**
     * Adds a custom handler for additional error processing.
     *
     * @param callable $handler Callback function to handle the error.
     * @return void
     */
    public function addHandler(callable $handler): void
    {
        $this->customHandlers[] = $handler;
    }

    /**
     * Formats and outputs an error based on the configured output format.
     *
     * @param Throwable $exception The error or exception to display.
     * @return void
     */
    private function displayError(Throwable $exception): void
    {
        // Create the appropriate parser based on the output format
        $parser = ErrorParserFactory::createParser($this->config['output_format']);
        
        // Output the parsed error message
        echo $parser->parse($exception);
    }

    /**
     * Logs an error using the configured logger.
     *
     * @param Throwable $exception The error or exception to log.
     * @return void
     */
    private function logError(Throwable $exception): void
    {
        $this->logger?->error($exception->getMessage(), [
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    /**
     * Checks if the specified error level is enabled in the configuration.
     *
     * @param int $level Error level to check.
     * @return bool True if the level is enabled; otherwise, false.
     */
    public function isLevelEnabled(int $level): bool
    {
        return (bool)($level & $this->config['error_level']);
    }

    /**
     * Retrieves the current error display setting.
     *
     * @return bool True if errors are displayed, false otherwise.
     */
    public function isDisplayingErrors(): bool
    {
        return $this->config['display_errors'];
    }

    /**
     * Sets the error display setting.
     *
     * @param bool $display True to display errors, false to suppress.
     * @return void
     */
    public function setDisplayErrors(bool $display): void
    {
        $this->config['display_errors'] = $display;
    }
}