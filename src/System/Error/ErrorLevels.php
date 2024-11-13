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

/**
 * ErrorLevels
 * 
 * Provides categorized PHP error levels for streamlined error handling.
 * Groups different types of errors under constants for easy reference.
 * 
 * @package    Artex\Essence\Engine\System\Error
 * @category   Error Management
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 */
class ErrorLevels
{
    /**
     * Runtime Errors
     * 
     * Errors that occur at runtime and may be handled by the application.
     * 
     * @var int
     */
    public const RUNTIME = (
        E_ERROR | E_WARNING | E_NOTICE | E_STRICT | E_RECOVERABLE_ERROR | E_DEPRECATED
    );

    /**
     * Compile-Time Errors
     * 
     * Errors that occur during the script compilation phase.
     * 
     * @var int
     */
    public const COMPILE = (
        E_PARSE | E_COMPILE_ERROR | E_COMPILE_WARNING
    );

    /**
     * Core Errors
     * 
     * Critical errors within the PHP core. Typically unrecoverable.
     * 
     * @var int
     */
    public const CORE = (
        E_CORE_ERROR | E_CORE_WARNING
    );

    /**
     * Custom User-Triggered Errors
     * 
     * Errors triggered by the application using `trigger_error`.
     * 
     * @var int
     */
    public const CUSTOM = (
        E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_USER_DEPRECATED
    );

    /**
     * Fatal Errors
     * 
     * Errors considered fatal, meaning the script cannot continue.
     * 
     * @var int
     */
    public const FATAL = (
        E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR
    );

    /**
     * Warnings
     * 
     * Non-fatal errors, typically indicating a potential issue.
     * 
     * @var int
     */
    public const WARNING = (
        E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING | E_STRICT
    );

    /**
     * Notices
     * 
     * Less severe errors, often informational or indicative of minor issues.
     * 
     * @var int
     */
    public const NOTICE = (
        E_NOTICE | E_USER_NOTICE
    );

    /**
     * Deprecated Warnings
     * 
     * Warnings about deprecated features or functions.
     * 
     * @var int
     */
    public const DEPRECATED = (
        E_DEPRECATED | E_USER_DEPRECATED
    );

    /**
     * Check if a given error level matches a defined group.
     * 
     * @param int $errorLevel The error level to check.
     * @param int $group The error group constant to check against.
     * @return bool True if the error level matches the group; otherwise, false.
     */
    public static function isInGroup(int $errorLevel, int $group): bool
    {
        return (bool)($errorLevel & $group);
    }

    /**
     * Get a readable name for an error level.
     * 
     * Converts an error level constant to a human-readable name.
     * 
     * @param int $errorLevel
     * @return string The name of the error level.
     */
    public static function getLevelName(int $errorLevel): string
    {
        return match ($errorLevel) {
            E_ERROR             => 'E_ERROR',
            E_WARNING           => 'E_WARNING',
            E_NOTICE            => 'E_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_PARSE             => 'E_PARSE',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
            default             => 'UNKNOWN'
        };
    }
}