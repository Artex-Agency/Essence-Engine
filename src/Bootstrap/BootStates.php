<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Bootstrap;

use \Artex\Essence\Engine\Bootstrap\Exceptions\BootstrapException;


/**
 * Bootstrap States
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\Bootstrap
 * @category   Bootstrap
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
interface BootStates
{
    /** @var integer LAUNCH The default starting state. */
    const LAUNCH = 0;

    /** @var integer SYSTEM System runtime environment loading and configuration. */
    const SYSTEM = 1;

    /** @var integer ENGINE Framework loading and configuration. */
    const ENGINE = 2;

    /** @var integer LOADER Registration collection and structured loading process. */
    const LOADER = 3;

    /** @var integer HANDLE Begins the bootstrap handling process (Registration is locked). */
    const HANDLE = 4;

    /** @var integer BOOTED Bootstrap process complete, updates system event registry. */
    const BOOTED = 5;

    /** @var integer HALTED The bootstrap process was halted by the user. */
    const HALTED = 6;

    /** @var integer ERRORS The bootstrap process was halted due to errors. */
    const ERRORS = 7;
}