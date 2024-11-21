<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\System\Config\Exception\Exception;

use ErrorException;

/**
 * ConfigReadException
 *
 * Exception thrown when an error occurs while reading a configuration file.
 *
 * @package    Essence\System\Config\Exception
 * @category   Configuration
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @extends    ErrorException
 * @link       https://artexessence.com/core/ Project Website
 */
class ConfigReadException extends ErrorException
{
    /**
     * ConfigReadException constructor.
     *
     * Constructs a new ConfigReadException using error details from an associative array.
     *
     * @param array $error Associative array containing error details.
     *                     - 'message'   : string  Error message (default: generic message).
     *                     - 'code'      : int     Error code (default: 0).
     *                     - 'type'      : int     Error severity (default: E_USER_WARNING).
     *                     - 'file'      : string  File where the error occurred (default: current file).
     *                     - 'line'      : int     Line number where the error occurred (default: current line).
     *                     - 'exception' : ?\Throwable  Previous exception if nested (default: null).
     */
    public function __construct(array $error)
    {
        $message   = $error['message'] ?? 'Error reading the configuration file';
        $code      = $error['code'] ?? 0;
        $severity  = $error['type'] ?? E_USER_WARNING;
        $filename  = $error['file'] ?? __FILE__;
        $lineno    = $error['line'] ?? __LINE__;
        $previous  = $error['exception'] ?? null;

        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
    }

    /**
     * Create an instance from an error array.
     *
     * @param array $error Associative array containing error details as in constructor.
     * @return static ConfigReadException instance.
     */
    public static function fromError(array $error): self
    {
        return new self($error);
    }
}