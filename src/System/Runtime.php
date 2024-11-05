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

namespace Artex\Essence\Engine\System;

use \Artex\Essence\Engine\System\Environment\Variables;

/**
 * Runtime
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\System
 * @category   System
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
class Runtime
{


    protected array $classes = [
        'variables' => \Artex\Essence\Engine\System\Environment\Variables::class
    ]

    protected ?Variables $Variables = null;

    protected bool $configured = false;

    protected bool $errored  = false;

    protected bool $loaded  = false;
    protected bool $booted  = false;
    protected bool $output  = false;
    protected bool $crashed = false;
    protected bool $exited  = false;

    public function __construct(string $envFile='')
    {
        echo '<h2>ESSENCE ENGINE: RUNTIME</h2>';

        // Load environment variables
        $this->$Variables = new $this->classes['variables']();

        echo '<pre>';
        print_r($_ENV);
        echo '</pre>';

    }


 // Runtime Process
 // #######################################

    public function configure()
    {

    }


 // Runtime Process
 // #######################################

    public function terminate()
    {

    }


 // Runtime Protocols
 // #######################################

    public function do_load()
    {
        $this->loaded = true;
    }

    public function do_boot()
    {
        $this->booted = true;
    }

    public function do_output()
    {
        $this->output = true;
    }

    public function do_crash()
    {
        $this->crashed = true;
    }

    public function do_exit()
    {
        $this->exited = true;
    }



 // Runtime Events
 // #######################################

    public function onLoad()
    {
        echo '<h3>ESSENCE STATE: Loading</h3>';
    }

    public function onBoot()
    {
        echo '<h3>ESSENCE STATE: Booted</h3>';
    }

    public function onError()
    {
        echo '<h3>ESSENCE STATE: Errored</h3>';
    }

    public function onShutdown()
    {
        // Termintate 
        $this->terminate();
        echo '<h3>ESSENCE STATE: Shutdown</h3>';
    }


    /**
     * Destructor
     */
    public function __destruct()
    {
        // Destruct
    }
}