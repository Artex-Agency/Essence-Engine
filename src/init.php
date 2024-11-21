<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Pre-boot initiation
 * 
 * 
 * 
 * 
 * This file is part of the Artex Essence meta-framework.
 * 
 * @package    Essence
 * @category   Initiate
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);


# ---------------------------------------------------------------------
# SHORTCODE CONSTANTS
# ---------------------------------------------------------------------
/** @var string DS Directory Separator, shorthand for PHP `DIRECTORY_SEPARATOR`. */
defined('DS') || define('DS', '/');

/** @var string PS Path Separator, shorthand for PHP `PATH_SEPARATOR`. */
defined('PS') || define('PS', PATH_SEPARATOR);

/** @var string NL Newline character, shorthand for PHP `PHP_EOL`. */
defined('NL') || define('NL', PHP_EOL);

/** @var string BS Backslash character shorthand. */
defined('BS') || define('BS', '\\');

/** @var string string Shortcode for carriage return. */
((defined('CRLF')) OR define('CRLF', (chr(13).chr(10))));


# ---------------------------------------------------------------------
# ESSENCE PATH CONSTANTS
# ---------------------------------------------------------------------
/** @var string ESS_PATH The Essence Engine directory path. */
(defined('ESS_PATH') OR define('ESS_PATH', (rtrim(__DIR__, '/') . DS)));

/** @var string ESS_ROOT The Essence Engine directory root path. */
(defined('ESS_ROOT') OR define('ESS_ROOT', (rtrim(dirname(__DIR__), '/') . DS)));


# ---------------------------------------------------------------------
# ESSENCE PATHS
# ---------------------------------------------------------------------
/** @var string BOOTSTRAP_PATH Directory path to the bootstrap folder. */
defined('BOOTSTRAP_PATH') || define('BOOTSTRAP_PATH', ESS_PATH . 'Bootstrap' . '/');

/** @var string ETC_PATH Directory path to the Essence etcetera folder. */
defined('ETC_PATH')     || define('ETC_PATH', ESS_PATH . 'etc' . '/');

/** @var string ENGINE_PATH Directory path to the Essence Engine folder. */
defined('ENGINE_PATH')  || define('ENGINE_PATH', ESS_PATH . 'Engine' . '/');

/** @var string HELPERS_PATH Directory path to the Essence helpers folder. */
defined('HELPERS_PATH') || define('HELPERS_PATH', ESS_PATH . 'Helpers' . '/');

/** @var string SYSTEM_PATH The system directory path. */
defined('OUTPUT_PATH')  || define('OUTPUT_PATH', ESS_PATH . 'output' . '/');

/** @var string SYSTEM_PATH The system directory path. */
defined('SYSTEM_PATH')  || define('SYSTEM_PATH', ESS_PATH . 'System' . '/');

/** @var string ESS_TEMPLATE_PATH a default template directory path. */
defined('ESS_TEMPLATE_PATH')  || define('ESS_TEMPLATE_PATH', ESS_PATH . 'Templates' . '/');



/** @var string ESS_ERROR_VIEW a default template directory path. */
defined('ESS_ERROR_VIEW')  || define('ESS_ERROR_VIEW', ESS_TEMPLATE_PATH . 'error' . '/');

# ---------------------------------------------------------------------
# EXTEND PATHS
# ---------------------------------------------------------------------
/** @var string TEMP_LOGS_PATH A temporary log file storage. */
defined('TEMP_LOGS_PATH')    || define('TEMP_LOGS_PATH', TEMP_PATH . 'logs' . '/');

/** @var string TEMP_UPLOADS_PATH A temporary upload file storage. */
defined('TEMP_UPLOADS_PATH') || define('TEMP_UPLOADS_PATH', TEMP_PATH . 'uploads' . '/');


# ---------------------------------------------------------------------
# LOAD 
# ---------------------------------------------------------------------

// Temporarily disables all errors.
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Load the base functionality
require(HELPERS_PATH .'content.helper.php');
require(HELPERS_PATH .'essence.helper.php');
require(HELPERS_PATH .'function.helper.php');
require(HELPERS_PATH .'settings.helper.php');

// The Artex Essence project foundation
//require(ETC_PATH .'essence.id.php');

