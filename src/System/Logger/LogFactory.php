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

use \Essence\System\Logger\Logger;
use \Essence\System\Logger\NullLog;
use \Essence\System\Logger\LoggerService;

/**
 * LogFactory Class
 *
 * The `LogFactory` class is responsible for creating instances of the 
 * appropriate logger based on whether logging is enabled or disabled. 
 * If logging is enabled, it returns an instance of the `Logger` class. 
 * Otherwise, it returns a `NullLog` instance, which discards all logs.
 *
 * This factory pattern provides a simple way to manage logging configuration 
 * throughout the application, ensuring that the correct logger is used based 
 * on runtime conditions.
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
class LogFactory
{
    /**
     * Create a logger instance.
     * 
     * This method instantiates a logger based on the given parameters. 
     * If logging is enabled, a `Logger` instance is returned with the 
     * specified logging level threshold. If logging is disabled, a 
     * `NullLog` instance is returned, effectively disabling logging.
     *
     * @param bool   $enabled   Whether logging is enabled.
     * @param int    $threshold The minimum logging level threshold (optional, defaults to 0).
     * 
     * @return LoggerService|null Returns a `Logger` instance if logging is enabled, 
     *                            or a `NullLog` if logging is disabled.
     */
    public static function create(bool $enabled, int $threshold = 0): ?LoggerService
    {
        return $enabled ? new Logger($threshold) : new NullLog($threshold);
    }
}