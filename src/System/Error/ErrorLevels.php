<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\System\Error;

/**
 * ErrorLevels
 * 
 * Provides categorized PHP error levels for streamlined error handling.
 * Groups different types of errors under constants for easy reference.
 * 
 * @package    Essence\System\Error
 * @category   Error Management
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
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
    public static function inGroup(int $errorLevel, int $group): bool
    {
        return (bool)($errorLevel & $group);
    }

    /**
     * Check if error code is fatal
     *
     * @param  integer $errno The error level code.
     * @return boolean Returns true if code is fatal; otherwise returns false.
     */
    public function isFatal(int $errno): bool
    {
        return (($errno & self::FATAL_LEVEL) ? true : false);
    }

    /**
     * Check if error code is a warning
     *
     * @param  integer $errno The error level code.
     * @return boolean Returns true if code is warning; otherwise returns false.
     */
    public function isWarning(int $errno): bool
    {
        return (($errno & self::WARNING_LEVEL) ? true : false);
    }

    /**
     * Check if error code is a notice
     *
     * @param  integer $errno The error level code.
     * @return boolean Returns true if code is notice; otherwise returns false.
     */
    public function isNotice(int $errno): bool
    {
        return (($errno & self::NOTICE_LEVEL) ? true : false);
    }

    /**
     * Check if error code is a strict notice
     *
     * @param  integer $errno The error level code.
     * @return boolean Returns true if code is strict; otherwise returns false.
     */
    public function isStrict(int $errno): bool
    {
        return (($errno & self::STRICT_LEVEL) ? true : false);
    }

    /**
     * Check if error code is deprecated
     *
     * @param  integer $errno The error level code.
     * @return boolean Returns true if code is deprecated; otherwise returns false.
     */
    public function isDeprecated(int $errno): bool
    {
        return (($errno & self::DEPRECATED_LEVEL) ? true : false);
    }

    /**
     * Check if error is a user level error
     *
     * @param  integer $errno The error level code.
     * @return boolean Returns true if user error; otherwise returns false.
     */
    public function isUserError(int $errno):bool
    {
        return (($errno & self::USER_LEVEL) ? true : false);
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