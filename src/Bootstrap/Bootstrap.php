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

use \Artex\Essence\Engine\Registry;
use \Artex\Essence\Engine\System\Runtime;
use \Artex\Essence\Engine\System\Server\OS;
use \Artex\Essence\Engine\Bootstrap\BootStates;
use \Artex\Essence\Engine\System\Environment\Env;
use \Artex\Essence\Engine\System\Server\Software;
use \Artex\Essence\Engine\System\Environment\Gateway;
use \Artex\Essence\Engine\Components\ServiceContainer;

use \Artex\Essence\Engine\System\Environment\Environment;

use \Artex\Essence\Engine\Bootstrap\Exceptions\BootstrapException;

/**
 * Bootstrap manager for initializing application components.
 *
 * The Bootstrap class handles the registration and execution of 
 * bootstrapper classes, supporting prioritized loading, grouped 
 * bootstrapping, deferred bootstrapping, and error handling to 
 * ensure a resilient initialization process.
 * 
 * @package    Artex\Essence\Engine\Bootstrap
 * @category   Bootstrap
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Bootstrap
{ 
    /** @var array $registry An array of registered application bootsteap loaders. */
    protected array $registry = [];


    /**
     * Constructor
     */
    public function __construct()
    {
        // Start system runtime
        Registry::set('runtime', 
            new Runtime(
                defined('APP_ENV_VARS') ? APP_ENV_VARS : (ROOT_PATH . '.env')
            )
        );

        pp(
            [
                'OS Type' => php_uname('s'),
                'Version Info' => php_uname('v'),
                'Release Version' => php_uname('r'),
                'Host Name' => php_uname('n'),
                'CPU Type' => php_uname('m'),
            ]
        );


/*
        $OS = new OS();
        $Software = new Software();
        pp([
            'OS TYPE'  => $OS->getType(),
            'OS Dist'  => $OS->getDistro(),
            'SOFTWARE' => $Software->get(),
        ]);
*/  
        // Set runtime
        //$this->Runtime = (($Runtime) ? $Runtime : null);

    }



    protected function execute(): bool
    {

    }


    protected function loadServices(): bool
    {


    
    }

    protected function loadRuntime(): bool
    {

    }


}