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


// STORAGE PATHS
// -------------------------------------------------------------------------
/** @var string CACHE_PATH The cache storage path. */
defined('CACHE_PATH') || define('CACHE_PATH',  STORAGE_PATH . 'cache' . '/');

/** @var string DAT_PATH Data storage path. */
defined('DAT_PATH')   || define('DAT_PATH',    STORAGE_PATH . 'dat'  . '/');

/** @var string LOGS_PATH The storage path for logs. */
defined('LOGS_PATH')  || define('LOGS_PATH',   STORAGE_PATH . 'logs' . '/');

/** @var string FILES_PATH The app uploads path. */
defined('FILES_PATH') || define('FILES_PATH',  STORAGE_PATH . 'files' . '/');

/** @var string STUBS_PATH Stubs storage path. */
defined('STUBS_PATH') || define('STUBS_PATH',  STORAGE_PATH . 'stubs' . '/');

/** @var string TMP_PATH Temp storage path. */
defined('TMP_PATH')   || define('TMP_PATH',    STORAGE_PATH . 'tmp'  . '/');

/** @var string VAR_PATH Variable storage path. */
defined('VAR_PATH')   || define('VAR_PATH',    STORAGE_PATH . 'var'  . '/');
// -------------------------------------------------------------------------

/** @var array ESS_PATHS The Essence Engine directory path. */
(defined('ESS_PATHS') OR 
    define('ESS_PATHS', 
        [
            'BOOTSTRAP'  => (ESS_PATH . 'Bootstrap/'),
            'ENGINE'     => (ESS_PATH . 'Engine/'),
            'FILESYSTEM' => (ESS_PATH . 'Filesystem/'),
            'HELPERS'    => (ESS_PATH . 'Helpers/')
        ]
    )
);
// -------------------------------------------------------------------------
