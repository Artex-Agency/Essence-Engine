<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Core framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Logger;

/**
 * Logger Interface
 * 
 * Defines the structure for a logger that records messages at various log levels. 
 * This interface provides the foundation for logging events, errors, warnings, 
 * and other messages with optional context data.
 * 
 * Implementing classes should handle how and where these logs are stored, and 
 * ensure that the log levels are respected.
 * 
 * @package    Artex\Essence\Engine\System\Logger
 * @category   Logging
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
interface LoggerInterface
{
    /**
     * Logs a message with the specified log level and optional context data.
     *
     * @param int               $level   The severity of the log (e.g., DEBUG, INFO, ERROR).
     * @param string|\Stringable $message The message to log.
     * @param array             $context An optional array of additional context data (default empty).
     * 
     * @return void
     */
    public function log(int $level, string|\Stringable $message, array $context = []): void;
}