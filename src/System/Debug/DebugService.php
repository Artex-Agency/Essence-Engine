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

use \Essence\System\Logger\LoggerInterface;

/**
 * Debug Service
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
 */
abstract class DebugService
{
    /** @var boolean $enabled Debug enable setting. */
    protected bool $enabled = false;

    /** @var boolean $display Toggle debug bar displasy. */
    protected bool $display = false;

    /** @var LoggerInterface|null Optional logger instance for error logging. */
    protected ?LoggerInterface $logger = null;


    protected ?DebugTimeline $timer = null;






    /**
     * Enable/Disable debugging.
     *
     * @param bool $enabled True to enable debug, false to disable.
     * @return void
     */
    public function setEnable(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * Checks if debugging is enabled
     * 
     * @return bool True if debugging is enabled, false otherwise.
     */
    public function isEnabled(): bool
    {
        return $this->display;
    }


}