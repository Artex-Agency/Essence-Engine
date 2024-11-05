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

use \Artex\Essence\Engine\Autoload;
//use \Essence\Bootstrap\Bootstrap; 
//use \Essence\Container\ServiceContainer;

    // Load autoloader if not already loaded.
    (class_exists('Autoload') OR 
        require(ENGINE_PATH.'Autoload.php')
    );
