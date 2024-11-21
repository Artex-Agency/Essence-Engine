<?php
declare(strict_types=1);

namespace Essence\System\Error;

use DateTime;
use DateTimeInterface;

/**
 * Error
 * 
 * Represents the core mechanism for monitoring and recording errors. Tracks 
 * whether errors have occurred, checks if any are fatal, and maintains an 
 * error log with detailed information.
 * 
 * Implements the `ErrorMonitorInterface` for streamlined integration with other 
 * system components.
 * 
 * @package    Essence\System\Error
 * @category   Error Management
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Error implements ErrorMonitorInterface
{
    /** @var bool Whether any error has been recorded. */
    private bool $hasErrors = false;

    /** @var bool Whether any recorded error is fatal. */
    private bool $isFatal = false;

    /** @var array Array of recorded error details. */
    private array $errorLog = [];

    /**
     * Records an error with its message and severity.
     * 
     * Updates the internal state to reflect the presence of errors and checks 
     * if the severity marks the error as fatal.
     *
     * @param string $message  The error message.
     * @param int    $severity The severity level of the error (e.g., E_ERROR, E_WARNING).
     * 
     * @return void
     */
    public function recordError(string $message, int $severity): void
    {
        $this->hasErrors = true;
        $this->isFatal = $this->isFatal || ErrorLevels::inGroup($severity, ErrorLevels::FATAL);

        $this->errorLog[] = [
            'message'   => $message,
            'severity'  => $severity,
            'levelName' => ErrorLevels::getLevelName($severity),
            'timestamp' => (new DateTime())->format(DateTimeInterface::RFC3339),
        ];
    }

    /**
     * Checks if any errors have been recorded.
     * 
     * @return bool True if errors have been recorded; otherwise, false.
     */
    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    /**
     * Checks if any recorded error is fatal.
     * 
     * @return bool True if a fatal error has been recorded; otherwise, false.
     */
    public function isFatalError(): bool
    {
        return $this->isFatal;
    }

    /**
     * Retrieves the log of all recorded errors.
     * 
     * @return array An array of error details, including message, severity, level name, and timestamp.
     */
    public function getErrorDetails(): array
    {
        return $this->errorLog;
    }

    /**
     * Clears all recorded errors and resets the state.
     * 
     * Resets the error log, `hasErrors`, and `isFatal` properties.
     * 
     * @return void
     */
    public function clearErrors(): void
    {
        $this->errorLog = [];
        $this->hasErrors = false;
        $this->isFatal = false;
    }
}