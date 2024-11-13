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

namespace Artex\Essence\Engine;

use \Artex\Essence\Engine\Autoload;
use \Artex\Essence\Engine\System\Runtime;
use \Artex\Essence\Engine\Bootstrap\Bootstrap;
use \Artex\Essence\Engine\Components\ServiceContainer;

class Engine
{

    const PACKAGE = 'Artex Essence Engine';
    const VERSION = '1.0.0-Dev.1';
    const WEBSITE = 'https://artexessence.com/engine/';

    /**
     * Autoloader
     *  
     * @var Autoload $Autoload
     */
    private ?Autoload $autoload = null;

    /**
     * Services Container
     * 
     * @var ServiceContainer $container 
     */
    private ?ServiceContainer $container = null;

    /**
     * System Runtime
     * 
     * @var Runtime $runtime 
     */
    private ?Runtime $runtime = null;



    public function __construct(Autoload $Autoload)
    {
        // Immediately store the Autoload.
        $this->Autoload = $Autoload;

        // Initialize the ServiceContainer singleton instance
        $this->container = ServiceContainer::getInstance();

        // Register autoload with container
        $this->container->set('autoload', $this->Autoload);


        // Add bootstrap to the services.
        //$this->container->set('bootstrap', new Bootstrap());


        // Set Runtime Variable
        //$this->Runtime = $this->container->get('runtime');


        echo '<p>Artex Essence:: Engine</p>';

    }



# ===================================================
#
#
# --------------------------------------------------
#   ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
#   ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
#   ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
#   |_____|_____|_____|_____|__|╲__|_____|_____|
#   ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
# ===================================================
## SYSTEM CONFIGURATION


}