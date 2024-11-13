<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
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
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine;

use \rtrim;
use \define;
use \defined;
use \dirname;
use \PHP_EOL;
use \require;
use \class_alias;
use \PATH_SEPARATOR;
use \Artex\Essence\Engine\Essence;

use \Artex\Essence\Engine\Services\Cache\CacheFactory;
use \Artex\Essence\Engine\Services\Cache\Adapters\FileCache;

// ESSENCE PATHS
// -------------------------------------------------------------------------
/** @var string ESS_ROOT The Essence Engine directory root path. */
(defined('ESS_PATH') OR define('ESS_PATH', (rtrim(__DIR__, '/') . '/')));

/** @var string ESS_PATH The Essence Engine directory path. */
(defined('ESS_ROOT') OR define('ESS_ROOT', (rtrim(dirname(__DIR__), '/') . '/')));

// STORAGE PATHS
// -------------------------------------------------------------------------
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


// LOAD HELPERS
// -------------------------------------------------------------------------
// Load framework helpers
require(ESS_PATH.'Helpers.php');

//$key_name = 'jg_cache_test_';
$jg_key = ('jg_cache_test_' . (string)(random_int(1, 9999)) . '_' . (string)(random_int(1, 999)) . '_' . (string)(random_int(1, 6)) . '_' .(string)(random_int(1, 9)));

echo '<p>'.$jg_key.'</p>';
if($cache = new FileCache(['cache_dir' => (ROOT_PATH . 'storage/cache/')])){

    

pp($cache);
if($cache->set($jg_key, ['name' => 'James Gober', 'test' => 'tbd', 'time' => time()], 0))
{
    echo '<p>Cache Saved</p>';
}
}






/*
// Get the requested URL without the sub-folder
$requestUri = ($_SERVER['REQUEST_URI'] ?? '');
$scriptName = ($_SERVER['SCRIPT_NAME'] ?? '');

echo '<p>REQ: ' . $requestUri . '</p>';

// Remove the sub-folder from the request URI
if (strpos($requestUri, $scriptName) === 0) {
    $requestUri = substr($requestUri, strlen($scriptName));
}$type='rui';

// Extract the query string from REQUEST_URI
$urlParts = explode('?', $requestUri, 2);
$requestUrl = $urlParts[0];
$requestQuery = isset($urlParts[1]) ? $urlParts[1] : '';$type='rqy';

//$requestQuery ='';

// If REQUEST_URI doesn't contain the query string, check QUERY_STRING
if ((isset($_SERVER['QUERY_STRING'])) && (empty($requestQuery))) { $type='rqy';
    $requestQuery = $_SERVER['QUERY_STRING'];
}

//$requestQuery ='';

// If QUERY_STRING is empty, check PATH_INFO
if ((isset($_SERVER['PATH_INFO'])) && (empty($requestQuery))) {  $type='pin';
    $requestQuery = $_SERVER['PATH_INFO'];
}

// Parse the query string into an associative array
parse_str($requestQuery, $queryParams);

echo "<p>$".$type."</p>";

echo '<pre>';
print_r($queryParams);
echo '</pre>';

*/




// LOADING MASTER CLASS
// -------------------------------------------------------------------------
class_alias('\Artex\Essence\Engine\Essence', 'Essence');

global $Essence;
$Essence = Essence::invoke();
