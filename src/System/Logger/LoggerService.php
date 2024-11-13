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

use \Artex\Essence\Engine\System\Logger\LogLevels;
use \Artex\Essence\Engine\System\Logger\LoggerInterface;

/**
 * Logger Service
 * 
 * This abstract class defines the structure and methods for a logging service 
 * that records messages at various log levels. It includes methods for logging 
 * emergency, alert, critical, error, warning, notice, info, and debug messages.
 * Implementing classes should define how and where logs are stored while respecting 
 * the defined log levels.
 *
 * This class also manages the log level threshold, allowing developers to control 
 * the minimum severity that will be logged, and provides a container for storing log records.
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
abstract class LoggerService implements LoggerInterface, LogLevels
{
    /** 
     * @var int $threshold The minimum log level to be recorded. 
     */
    protected int $threshold = 0;

    /** 
     * @var array $logs Container for system log records. 
     */
    protected static array $logs = [];

    /**
     * Sets the log level threshold.
     *
     * This method sets the minimum log level that will be recorded by the logger.
     * Logs below this threshold will be ignored.
     *
     * @param int $threshold The minimum log level to record (e.g., DEBUG, INFO, ERROR).
     * 
     * @return void
     */
    public function setThreshold(int $threshold): void
    {
        $this->threshold = min(max($threshold, 0), 600);
    }

    /**
     * Retrieves all log records.
     * 
     * @return array Returns the entire log array.
     */
    final public function getLog(): array
    {
        return self::$logs ?? [];
    }

    /**
     * Logs a message at the EMERGENCY level.
     * 
     * Use this method to log system-critical errors where the system is unusable.
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Logs a message at the ALERT level.
     * 
     * Use this method for events that require immediate action, such as severe service outages.
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * Logs a message at the CRITICAL level.
     * 
     * Use this method for critical conditions that affect essential services.
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Logs a message at the ERROR level.
     * 
     * Use this method for runtime errors that do not require immediate action but should be logged and monitored.
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Logs a message at the WARNING level.
     * 
     * Use this method to log warnings about exceptional occurrences that are not errors (e.g., use of deprecated APIs).
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Logs a message at the NOTICE level.
     * 
     * Use this method for normal but significant events, such as configuration changes.
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Logs a message at the INFO level.
     * 
     * Use this method to log significant but normal events (e.g., user logins or SQL queries).
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Logs a message at the DEBUG level.
     * 
     * Use this method to log detailed debug information for diagnostic purposes.
     *
     * @param string|\Stringable $message The log message.
     * @param array              $context Optional context data to include with the log.
     * 
     * @return void
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log(self::DEBUG, $message, $context);
    }

    /**
     * Abstract log method.
     * 
     * This method must be implemented by concrete classes and defines how the log entries 
     * will be recorded based on the log level, message, and context.
     *
     * @param int               $level   The severity of the log message (e.g., DEBUG, ERROR).
     * @param string|\Stringable $message The message to log.
     * @param array             $context Additional context data for the log.
     * 
     * @return void
     */
    abstract public function log(int $level, string|\Stringable $message, array $context = []): void;
}