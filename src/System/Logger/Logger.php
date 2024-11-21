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

namespace Essence\System\Logger;

use \Essence\System\Logger\LoggerService;

/**
 * Logger Class
 *
 * This class extends the LoggerService abstract class and provides a concrete 
 * implementation of the `log` method. It allows logging messages with different 
 * severity levels, along with optional context data. Log entries are stored in 
 * an internal array and can be retrieved via `getLog()` from the parent class.
 * 
 * The logger also respects the log level threshold, meaning only messages with 
 * a severity level equal to or greater than the set threshold will be recorded.
 * 
 * @package    Essence\System\Logger
 * @category   Logging
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Logger extends LoggerService
{
    /**
     * Constructs a new Logger instance with the specified log level threshold.
     *
     * The threshold defines the minimum log level that will be recorded.
     * Any log messages below this level will be ignored.
     *
     * @param int $threshold The minimum log level to record (optional, defaults to 0).
     */
    public function __construct(int $threshold = 0)
    {
        parent::setThreshold($threshold);
    }

    /**
     * Logs a message with the specified severity level and optional context data.
     *
     * This method creates a new log entry with the provided log level, message, 
     * and context. Log entries are only recorded if their log level is equal to 
     * or above the defined threshold.
     *
     * @param int                $level   The log level (e.g., DEBUG, INFO, ERROR).
     * @param string|\Stringable $message The log message.
     * @param array              $context Additional context data to include (optional).
     * 
     * @return void
     */
    public function log(int $level, string|\Stringable $message, array $context = []): void
    {
        $level = min(max($level, 0), 600);
        if ($level < $this->threshold) {
            return;
        }

        // Create log entry with the level, timestamp, message, and context
        self::$logs[] = [
            'code'      => $level,
            'level'     => self::LOG_LABELS[$level] ?? 'Unknown',
            'timestamp' => time(),
            'message'   => $message,
            'context'   => $context
        ];
    }
}