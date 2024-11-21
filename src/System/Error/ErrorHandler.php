<?php
declare(strict_types=1);

namespace Essence\System\Error;

use \Throwable;
use \ErrorException;
use \Essence\Utils\Interpolate;
use \Essence\System\Debug\DebugBar;
use \Essence\System\Error\ErrorConfig;
use \Essence\System\Error\ErrorLevels;
use \Essence\System\Logger\LogFactory;
use \Essence\System\Error\ErrorRenderer;
use \Essence\System\Debug\DebugInterface;
use \Essence\System\Events\EventDispatcher;
use \Essence\System\Logger\LoggerInterface;
/**
 * ErrorHandler
 *
 * Handles PHP errors and exceptions, provides integration with debug bar,
 * and supports multiple error display modes.
 *
 * @package    Essence\System\Error
 * @category   Error Management
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/ Project Website
 */
class ErrorHandler
{
    /** @var ErrorConfig Configuration for error handling. */
    private ErrorConfig $config;

    /** @var ErrorRenderer Responsible for rendering errors. */
    private ErrorRenderer $renderer;

    /** @var DebugBar|null Optional debug bar integration. */
    private ?DebugBar $debugBar;

    /** @var bool Prevent recursive error handling. */
    private bool $handlingError = false;

    private ?LoggerInterface $logger;

/** @var Interpolate The interpolation engine for rendering templates. */
private Interpolate $interpolator;

private ?EventDispatcher $dispatcher=null;

    /**
     * Constructor.
     *
     * @param ErrorConfig        $config   The error handling configuration.
     * @param ErrorRenderer      $renderer The renderer for displaying errors.
     * @param DebugInterface|null $debugBar Optional debug bar integration.
     */
    public function __construct(
        ErrorConfig $config, 
        ErrorRenderer $renderer,  
        ?LoggerInterface $logger = null, 
        ?DebugBar $debugBar = null,
        ?EventDispatcher $dispatcher=null)
    {
        $this->config = $config;
        $this->renderer = $renderer;
        $this->logger = $logger;
        $this->debugBar = $debugBar;
        $this->dispatcher = $dispatcher;
        $this->interpolator = new Interpolate();
    }

    /**
     * Register the error and exception handlers.
     *
     * @return void
     */
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Handles PHP errors.
     *
     * @param int    $code
     * @param string $message
     * @param string $file
     * @param int    $line
     * @return void
     */
    public function handleError(int $code, string $message, string $file = '', int $line = 0): bool
    {
        if ($this->handlingError) {
            return false; // Prevent recursion
        }
    
        if (!(error_reporting() & $code)) {
            return false; // Suppressed error
        }
    
        $this->handlingError = true;
    
        try {
            // Only convert fatal errors into exceptions
            if (in_array($code, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
                throw new \ErrorException($message, 0, $code, $file, $line);
            }
    
            // Otherwise, log and render non-fatal errors
            $this->logger?->error("Non-fatal error: $message in $file on line $line");
            $templateData = [
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'timestamp' => date('Y-m-d H:i:s'),
            ];
            echo $this->renderer->renderTemplate($templateData);
        } catch (\Throwable $e) {
            error_log("Error rendering non-fatal error: {$e->getMessage()}");
        } finally {
            $this->handlingError = false; // Reset lock
        }
    
        return true;
    }

    /**
     * Handles uncaught exceptions.
     *
     * @param Throwable $exception
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        if ($this->handlingError) {
            return; // Prevent recursive handling
        }
    
        $this->handlingError = true;
    
        try {
            // Prepare the template data
            $templateData = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'timestamp' => date('Y-m-d H:i:s'),
                'code' => $this->extractCodeSnippet($exception->getFile(), $exception->getLine()),
                'trace' => $exception->getTraceAsString(),
            ];
    
            // Add tokens for interpolation
            $this->interpolator->addStaticTokens($templateData);
    
            // Render the exception using the renderer
            echo $this->renderer->render($this->renderer->getDefaultTemplate(), $templateData);
    
            // Optionally dispatch the error event
            if (isset($this->dispatcher)) {
                $this->dispatcher->dispatch('error.occurred', $templateData);
            }
        } catch (\Throwable $renderingError) {
            // Log or display fallback error details
            error_log("Error during rendering: {$renderingError->getMessage()}");
            echo "An error occurred. Unable to display details.";
        } finally {
            $this->handlingError = false; // Reset the handling lock
        }
    }

    /**
     * Handle script shutdown and check for fatal errors.
     *
     * @return void
     */
    public function handleShutdown(): void
    {
        $lastError = error_get_last();

        if ($lastError && ErrorLevels::inGroup($lastError['type'], ErrorLevels::FATAL)) {
            $this->handleError(
                $lastError['type'],
                $lastError['message'],
                $lastError['file'],
                $lastError['line']
            );
        }
    }

    /**
     * Extract a snippet of code surrounding an error line.
     *
     * @param string $file    The file path.
     * @param int    $line    The line number of the error.
     * @param int    $padding Number of lines of context to include.
     * @return string Code snippet with the error highlighted.
     */
    private function extractCodeSnippet(string $file, int $line, int $padding = 5): string
    {
        if (!file_exists($file)) {
            return 'Code file not found.';
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $start = max($line - $padding - 1, 0);
        $end = min($line + $padding - 1, count($lines) - 1);

        $snippet = '';
        for ($i = $start; $i <= $end; $i++) {
            $lineNumber = str_pad((string)($i + 1), 4, ' ', STR_PAD_LEFT);
            $highlight = $i + 1 === $line ? 'background: #ffcccb;' : '';
            $snippet .= sprintf(
                '<span style="display: block; %s">%s %s</span>',
                $highlight,
                $lineNumber,
                htmlspecialchars($lines[$i], ENT_QUOTES, 'UTF-8')
            );
        }
        return $snippet;
    }
}