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

namespace Essence\System\Debug;

use \Essence\System\Debug\DebugService;
use \Essence\System\Debug\DebugTimeline;
use \Essence\System\Debug\DebugInterface;

use \Essence\System\Logger\LoggerInterface;
//use \Essence\System\Debug\DebugTimeline;
//use \Essence\System\Debug\DebugTimeline;
//use \Essence\System\Debug\DebugTimeline;

/**
 * Debug
 * 
 * @package    Essence\System\Debug
 * @category   Debug
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Debug extends DebugService implements DebugInterface
{



    protected array $logs = [];

    protected array $errors = [];


    /**
     * Undocumented function
     *
     * @param boolean $display
     * @param integer $reporting
     * @param LoggerInterface|null $logger
     */
    public function __construct(bool $display=false, int $reporting=0, ?LoggerInterface $logger=null)
    {
        $this->timer = new DebugTimeline();

    }

    public function start()
    {

    }

    /**
     * Logs an error with specified details.
     *
     * @param string $type    The type of error (e.g., 'Error', 'Warning').
     * @param string $text    The error message.
     * @param string $file    The file where the error occurred.
     * @param int    $line    The line number where the error occurred.
     * @param array  $context Additional context data for debugging.
     *
     * @return void
     */
    public function logError(string $type = 'Error', string $text = '', string $file = '', int $line = 0, array $context = []): void
    {
        $this->errors[] = [
            'type'    => $type,
            'message' => $text,
            'file'    => $file ?: 'unknown file',
            'line'    => $line ?: 0,
            'context' => $context
        ];
    }

    public function log()
    {

    }

    public function enable()
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function disable()
    {
        restore_error_handler();
        restore_exception_handler();
    }



    /**
     * Get last error (if exists)
     * 
     * @return boolean Returns true if fatal error was triggered, returns false otherwise.
     */
    public function getLast()
    {
        // Get last error
        $err  = error_get_last();
        $code = (int)$err['type'];
        $text = $err['message'];
        $file = $err['file'];
        $line = (int)$err['line'];
        unset($err);

        if (!ErrorLevel::isFatal($code)) {
            unset($code, $text, $file, $line);
            return false;
        }

        return $this->handleError($code, $text, $file, $line);
    }

    /**
     * Sets the display setting for errors.
     *
     * @param bool $display Whether to display errors.
     * @return void
     */
    public function setDisplay(bool $display): void
    {
        if($this->getDisplay() !== $display){
            if($display === true){  // Display enabled
                $this->enable();
            }
            if($display === false){ // Display disabled
                $this->disable();
            }
        }
        parent::setDisplay($display);
    }

    /**
     * Sets the error reporting setting.
     *
     * @param int $reporting The error reporting level.
     * @return void
     */
    public function setReporting(int $reporting): void
    {
        parent::setReporting($reporting);
    }
}