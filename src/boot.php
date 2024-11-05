<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * Engine Boot Loader
 * 
 * Description
 *
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @package    Artex\Essence\Engine
 * @category   Startup
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
declare(strict_types=1);

use \Artex\Essence\Engine\System\Environment\Variables;


// Check Engine Root Path
(defined('ENGINE_ROOT') OR 
    /** @var string ENGINE_ROOT The engine directory root path. */
    define('ENGINE_ROOT', rtrim(dirname(__DIR__), '/') . '/')
);

// Check Engine Path
(defined('ENGINE_PATH') OR 
    /** @var string ENGINE_PATH The engine directory path. */
    define('ENGINE_PATH', rtrim(__DIR__, '/') . '/')
);

// Load constants if not already loaded.
(defined('ENGINE_NAMESPACE') OR 
    require(ENGINE_PATH . 'Bootstrap/constants.php')
);

// Load autoloader if not already loaded.
(class_exists('\Artex\Essence\Engine\Autoload') OR 
    require(ENGINE_PATH . 'Autoload.php')
);

// Activate autoloader
$Autoload = new \Artex\Essence\Engine\Autoload(
    ENGINE_NAMESPACE, 
    ENGINE_PATH
);

// Set Aliases
class_alias('\Artex\Essence\Engine\Engine', 'Engine');
class_alias('\Artex\Essence\Engine\System\Runtime', 'Runtime');
class_alias('\Artex\Essence\Engine\Bootstrap\Bootstrap', 'Bootstrap');
class_alias('\Artex\Essence\Engine\Components\ServiceContainer', 'ServiceContainer');
class_alias('\Artex\Essence\Engine\Bootstrap\BootstrapInterface', 'BootstrapInterface');

// Initialize the ServiceContainer singleton instance
$container = ServiceContainer::getInstance();

// Add autoload to the services.
$container->set('autoload', $Autoload);




echo '<h1>Essence Boot</h1>';
echo '<p>&rarr; ' . ENGINE_NAME . '</p>';
echo '<p>&rarr; ' . ENGINE_PACKAGE . '</p>';
echo '<p>&rarr; ' . ENGINE_VERSION . '</p>';
echo '<p>&rarr; ' . ENGINE_WEBSITE . '</p>';
echo '<p>&rarr; ' . ENGINE_NAMESPACE . '</p>';


$Runtime = new Runtime((ROOT_PATH . '.env'));

exit(0);





$container->set('runtime', 
    new Runtime
    (
        new Variables( // Load environment variables
            (ROOT_PATH . '.env'),
            'env'
        )
    )
);




// Add engine to the services.
$container->set('engine', new Engine(
    $container
));
