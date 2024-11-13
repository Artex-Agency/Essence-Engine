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

use \Artex\Essence\Engine\System\Logger\LoggerService;

/**
 * Null Logger Class
 *
 * This class implements a null logger, meaning all log messages passed 
 * to this logger are discarded without any action. It is useful in 
 * scenarios where logging needs to be disabled or temporarily ignored 
 * without removing the logging functionality from the codebase.
 *
 * The `NullLog` class extends the `LoggerService` but overrides the 
 * `log` method to effectively do nothing. This allows for seamless 
 * disabling of logging by using this class instead of other logger 
 * implementations.
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
class NullLog extends LoggerService
{
    /**
     * Constructs a new NullLog instance with the specified threshold.
     *
     * This constructor sets the threshold to the maximum log level,
     * ensuring that no messages are logged.
     *
     * @param int $threshold The minimum log level to ignore (optional, defaults to MAX_LEVEL).
     */
    public function __construct(int $threshold = self::MAX_LEVEL)
    {
        parent::setThreshold($threshold);
    }

    /**
     * Log a message with the specified level and optional context data.
     *
     * This method overrides the log method of the parent class and does nothing,
     * effectively discarding all log messages.
     *
     * @param int                $level   The log level (e.g., DEBUG, ERROR).
     * @param string|\Stringable $message The message to log.
     * @param array              $context Additional context data (optional).
     * 
     * @return void
     */
    public function log(int $level, string|\Stringable $message, array $context = []): void
    {
        // Do nothing (null logger)
    }
}