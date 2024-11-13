<?php declare(strict_types=1);

namespace Artex\Essence\Engine\System\Error;

/**
 * ErrorAwareSystem
 * 
 * A centralized class responsible for managing the system's global error state.
 * It ensures that key dependencies are in sync prior to rendering errors, including
 * session, cookie, and header handling.
 * 
 * @package   Artex\Essence\Engine\System\Error
 * @category  System Awareness
 * @access    public
 * @version   1.0.0
 * @since     1.0.0
 */
class ErrorAwareSystem
{
    /**
     * @var bool $hasError Indicates if the system is currently in an error state.
     */
    private static bool $hasError = false;

    /**
     * Triggers the global error state.
     *
     * Marks the system as in an error state and initiates pre-render tasks.
     */
    public static function triggerError(): void
    {
        self::$hasError = true;
        self::beforeRender();
    }

    /**
     * Checks if the system is in an error state.
     * 
     * @return bool True if the system has an error; otherwise, false.
     */
    public static function hasError(): bool
    {
        return self::$hasError;
    }

    /**
     * Executes tasks necessary before error rendering.
     *
     * Ensures headers, sessions, and cookies are appropriately handled.
     */
    private static function beforeRender(): void
    {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }
        
        session_write_close();
        // Additional pre-render tasks here if needed
    }
}