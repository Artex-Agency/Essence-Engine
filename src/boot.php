<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * Load
 * 
 * Description
 *
 * This file is part of the Artex Essence Engine and meta-framework,
 * designed to serve as the foundational layer for high-performance,
 * flexible PHP application frameworks.
 *
 * @package    Artex\Essence\Engine
 * @category   Boot
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
declare(strict_types=1);

use \Artex\Essence\Engine\Engine;
use \Artex\Essence\Engine\Autoload;
use \Artex\Essence\Engine\Bootstrap\Bootstrap;
use \Artex\Essence\Engine\Components\ServiceContainer;
use \Artex\Essence\Engine\Bootstrap\BootstrapInterface;

// Load autoloader if not already loaded.
(class_exists('Autoload') OR 
    require(ENGINE_PATH . 'Bootstrap/constants.php')
);

// Load autoloader if not already loaded.
(class_exists('Autoload') OR 
    require(ENGINE_PATH . 'Autoload.php')
);

// Activate autoloader
$Autoload = new Autoload('Artex\Essence\Engine', ENGINE_PATH);

// Set Aliases
class_alias('Engine', 'Engine');
class_alias('Bootstrap', 'Bootstrap');
class_alias('ServiceContainer', 'Container');
class_alias('BootstrapInterface'. 'BootstrapInterface');

// Initialize the ServiceContainer singleton instance
$container = ServiceContainer::getInstance();

// Add autoload to the services.
$container->set('autoload', $Autoload);

// Add bootstrap to the services.
$container->set('bootstrap', new Bootstrap());

// Add engine to the services.
$container->set('engine', new Engine());
