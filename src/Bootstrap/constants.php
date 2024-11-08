<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * Constants
 *
 * Core constants used throughout the Artex Essence Engine.
 *
 * This file is part of the Artex Essence Engine and meta-framework.
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

// Primary Constants

/** 
 * The framework engine name. 
 * @var string ENGINE_NAME 
 */
defined('ENGINE_NAME') || define('ENGINE_NAME', 'Essence Engine');

/** 
 * The framework engine package name. 
 * @var string ENGINE_PACKAGE
 */
defined('ENGINE_PACKAGE') || define('ENGINE_PACKAGE', 'Artex Essence Engine');

/** 
 * The framework engine website. 
 * @var string ENGINE_WEBSITE
 */
defined('ENGINE_WEBSITE') || define('ENGINE_WEBSITE', 'https://artexessence.com/engine/');

/** 
 * The framework engine namespace. 
 * @var string ENGINE_NAMESPACE
 */
defined('ENGINE_NAMESPACE') || define('ENGINE_NAMESPACE', '\Artex\Essence\Engine');

/** 
 * The framework engine base version. 
 * @var string ENGINE_BASE_VERSION 
 */
defined('ENGINE_BASE_VERSION') || define('ENGINE_BASE_VERSION', '1.0.0');

/** 
 * The framework engine version stability. 
 * @var string ENGINE_STABILITY 
 */
defined('ENGINE_STABILITY') || define('ENGINE_STABILITY', 'dev');

// Get current version data
$version_file = ENGINE_ROOT . 'VERSIONS';
if(!is_file($version_file) || !$version_data = file_get_contents($version_file)){
   $version_data = ENGINE_BASE_VERSION;
}

/** 
 * The framework engine version. 
 * @var string ENGINE_VERSION 
 */
defined('ENGINE_VERSION') || define('ENGINE_VERSION', $version_data);

// Clean up
unset($version_file, $version_data);


// Shortcuts for PHP constants

/** 
 * Directory Separator, shorthand for PHP `DIRECTORY_SEPARATOR`. 
 * @var string DS 
 */
defined('DS') || define('DS', '/');

/** 
 * Path Separator, shorthand for PHP `PATH_SEPARATOR`. 
 * @var string PS 
 */
defined('PS') || define('PS', PATH_SEPARATOR);

/** 
 * Newline character, shorthand for PHP `PHP_EOL`. 
 * @var string NL 
 */
defined('NL') || define('NL', PHP_EOL);

/** 
 * Backslash character shorthand.
 * @var string BS 
 */
defined('BS') || define('BS', '\\');


// Compatibility

/** 
 * The minimum PHP version required to run this software.
 * @var string ESS_PHP_MIN 
 */
define('ESS_PHP_MIN', '8.1');

/** 
 * The maximum supported PHP version. 
 * Typically left blank to accommodate future versions.
 * @var string ESS_PHP_MAX 
 */
define('ESS_PHP_MAX', '');

/** 
 * The recommended PHP version for best performance and compatibility.
 * @var string ESS_PHP_BEST 
 */
define('ESS_PHP_BEST', '8.2');



// CONFIG PATHS
// ==========================================================================

/** @var string CFG_ENGINE_PATH The engine directives configuration path. */
defined('CFG_ENGINE_PATH') || define('CFG_ENGINE_PATH', CONFIG_PATH . 'engine'  . '/');

// ==========================================================================