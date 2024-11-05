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

namespace Artex\Essence\Engine\System\Environment;

use \trim;

use \putenv;
use \is_file;
use \array_keys;
use \preg_match_all;
use \PREG_SET_ORDER;
use \file_get_contents;
use \Artex\Essence\Engine\System\Environment\Variables;

/**
 * Environment Variables
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\System\Environment
 * @category   System
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
class Maintenance
{
    /**
     * Mode enabled state.
     *
     * @var boolean
     */
    protected bool $enabled = false;

    /**
     * Driver object
     *
     * @var ?array
     */
    protected ?object $driver = null;

    /**
     * Drivers map
     *
     * @var array
     */
    protected array $drivers = [
        'default'     => 'class',
        'testing'     => 'class',
        'development' => 'class',
        'production'  => 'class'
    ];

    /**
     * Environment Mode Constructor
     * 
     * Sets the environment modes
     */
    public function __construct(
        bool   $enabled = false,
        string $driver = 'testing',
        array  $drivers = []
    ){
        // Set defaults
        $this->reset();

        // Preserve system default driver
        if(isset($drivers['default'])){
            unset($drivers['default']);
        }

        // Merge drivers
        $this->drivers = array_merge($this->drivers, ($drivers ?? []));

        // Set mode
        $this->enabled = $enabled;



        // Set driver
        $this->setDriver($driver);


    }


    protected function setDriver(string $driver)
    {
        // Format string
        $driver = strtolower(trim($driver));

        // Set driver
        $this->driver = (
            (isset($this->drivers[$driver]))
             ? $this->drivers[$driver]
             : $this->drivers['default']
        );
    }

    /**
     * Checks if mode enabled.
     *
     * @return boolean True if mode is enabled; otherwise false.
     */
    public function isEnabled():bool
    {
        return $this->enabled;
    }

    /**
     * Checks if mode enabled.
     *
     * @return boolean True if mode is enabled; otherwise false.
     */
    public function reset():void
    {
        $this->enabled = false;
    }

    /**
     * Destructor on close
     * 
     */
    public function __destruct()
    {
        $this->reset();
    }
}