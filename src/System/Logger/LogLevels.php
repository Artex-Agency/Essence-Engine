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
 * Log Level interface
 * 
 * Defines constant values for various log levels used throughout the 
 * application. Each log level represents the severity of the log message, 
 * ranging from debugging information to critical system failures.
 * 
 * The class also provides a mapping of log levels to human-readable labels, 
 * allowing for easy reference and logging consistency.
 * 
 * ### Logger Levels
 * 
 * 100  DEBUG	  Fine-grained debugging information.
 * 200  INFO	  Informational messages about normal application flow.
 * 250  NOTICE    Normal but significant events or important notices.
 * 300  WARNING   Indications of possible issues, but not yet errors.
 * 400  ERROR	  Runtime errors that allow the application to continue.
 * 500  CRITICAL  Critical conditions requiring immediate attention.
 * 550  ALERT	  Immediate action required (e.g., service outage).
 * 600  EMERGENCY System is unusable or in a catastrophic state.
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
interface LogLevels
{
    /** @var int EMERGENCY The emergency log level, indicating system-wide failures. */
    const EMERGENCY = 600;

    /** @var int ALERT The alert log level, requiring immediate attention. */
    const ALERT     = 550;

    /** @var int CRITICAL The critical log level, indicating serious errors. */
    const CRITICAL  = 500;

    /** @var int ERROR The error log level, for runtime errors that allow the application to continue running. */
    const ERROR     = 400;

    /** @var int WARNING The warning log level, for potentially harmful situations. */
    const WARNING   = 300;

    /** @var int NOTICE The notice log level, for normal but significant events. */
    const NOTICE    = 250;

    /** @var int INFO The informational log level, for general operational events. */
    const INFO      = 200;

    /** @var int DEBUG The debug log level, for detailed diagnostic information. */
    const DEBUG     = 100;

    /**
     * Log Labels
     *
     * Maps log levels to their corresponding string labels. This is useful 
     * for rendering or displaying log messages in a human-readable format.
     *
     * @var array<int, string>
     */
    const LOG_LABELS = [
        600 => 'EMERGENCY',
        550 => 'ALERT',
        500 => 'CRITICAL',
        400 => 'ERROR',
        300 => 'WARNING',
        250 => 'NOTICE',
        200 => 'INFO',
        100 => 'DEBUG'
    ];
}