<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Helpers
 * 
 * Core utility functions to simplify operations across the application.
 * Designed to enhance debugging, error handling, and general utility.
 *
 * This file is part of the Artex Essence meta-framework.
 *
 * @package    Essence
 * @category   Essence
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);
/*
use \date;
use \trim;
use \getenv;
use \substr;
use \explode;
use \ini_set;
use \is_file;
use \in_array;
use \Throwable;
use \filter_var;
use \strtolower;
use \strtoupper;
use \unserialize;
use \preg_replace;
use \array_key_exists;
use \file_put_contents;
use \date_default_timezone_get;
use \date_default_timezone_set;
*/


function get_environment_config_path(string $environment): string|false
{
    $path = (CONFIG_PATH . 'environment' . DS . $environment . DS);
    if((!is_dir($path)) && ($environment !== 'production')){
        return get_environment_config_path('production');
    }
    return ((is_dir($path)) ? $path : false);
}


function get_environment_config(string $environment): array|false
{
    if(!$path = get_environment_config_path($environment)){
        return false;
    }
    return [
        'errors'  => ($path . 'errors.cfg.php'),
        'logging' => ($path . 'logging.cfg.php')
    ];
}






/**
 * Array Get
 * Retrieve a deeply nested array item with "dot" notation.
 *
 * @param array $array The array to search.
 * @param string $key The dot-notated key.
 * @param mixed $default Default value if key does not exist.
 * @return mixed
 */
function array_get(array $array, string $key, $default = null): mixed
{
    $keys = explode('.', $key);

    foreach ($keys as $innerKey) {
        if (array_key_exists($innerKey, $array)) {
            $array = $array[$innerKey];
        } else {
            return $default;
        }
    }
    
    return $array;
}







/**
 * Write a log entry to the specified log file.
 *
 * @param string $message The message to log.
 * @param string $level Log level (e.g., 'info', 'error').
 * @param string|null $file Optional log file path. Defaults to `storage/logs/app.log`.
 * @return void
 */
function log_message(string $message, string $level = 'info', ?string $file = null): void
{
    $file = $file ?? __DIR__ . '/../storage/logs/app.log';
    $date = date('Y-m-d H:i:s');
    $formattedMessage = "[{$date}] [{$level}] - {$message}" . PHP_EOL;

    file_put_contents($file, $formattedMessage, FILE_APPEND | LOCK_EX);
}




/**
 * Get an environment variable, with an optional default if not set.
 *
 * @param string $key The environment variable key.
 * @param mixed $default Default value if the environment variable is not set.
 * @return mixed
 */
function env(string $key, $default = null)
{
    $value = getenv($key);
    
    if ($value === false) {
        return $default;
    }

    // Handle boolean-like values
    $value = strtolower($value);
    if ($value === 'true') return true;
    if ($value === 'false') return false;

    return $value;
}

/**
 * Check if the current environment is "production".
 *
 * @return bool
 */
function is_production(): bool
{
    return env('APP_ENV', 'production') === 'production';
}


/*

#### ENGINE DIRECTIVES ################################################ */

/**
 * Essence Directive Presets
 *
 * @param string $name the name of the preset.
 * @return array The preset array
 */
function engine_load_directive(string $name):array
{
    $name = strtolower(trim($name));
    $name = (CFG_ENGINE_PATH . "$name.cfg.php");
    if(!is_file($name)){
        return [];
    }
    return require($name);
}




/*

#### SYSTEM CONFIG #################################################### */


/**
 * Sets the default timezone.
 * 
 * @param  string $timezone The desired timezone using the valid 
 * @return bool Returns true on success, false on failure.
 * 
 * @see http://www.php.net/manual/en/timezones.php timezone formats 
 */
function setTimeZone(string $timezone): bool
{
    /**
     * Sets the default timezone.
     *
     * @link http://www.php.net/manual/en/function.date-default-timezone-set.php
     */
    return date_default_timezone_set($timezone);
}

/**
 * Gets the default timezone.
 * 
 * @return string Returns the system default timezone.
 * 
 * @see http://www.php.net/manual/en/timezones.php timezone formats 
 */
function getTimeZone(): string
{
    /**
     * Gets the default timezone.
     * 
     * @link http://www.php.net/manual/en/function.date-default-timezone-get.php
     */
    return date_default_timezone_get();
}





/*

#### DEBUG OUTPUT ##################################################### */


/**
 * Pretty Print
 * Outputs variable(s) in a formatted, styled HTML structure.
 *
 * @param mixed ...$vars Variables to dump.
 * @return void
 */
function pp(...$vars): void
{
    echo '<!--- ESSENCE ENGINE --->
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background-color: #f5f5f5; color: #333; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
        .ess-debug-box { background-color: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 18px; margin: 18px; box-shadow: 0 3px 6px -3px rgba(0, 0, 0, 0.18); }
        .ess-debug-code { background-color: #f7f7f9; padding: 10px; border-radius: 5px; }
        .ess-debug-head { width: 100%; height: auto; padding: 9px; display: flex; flex-direction: row; justify-content: flex-start; align-items: center; text-align: center; }
        .ess-debug-title { font-family: "Oswald", sans-serif; font-size: 18px; font-weight: 400; text-transform: uppercase; color: #cfd5e6; letter-spacing: 1px; }
        .ess-debug-title > span{ font-weight: 500; }
        .ess-debug-logo{ width: 23px; height: 23px; margin: 0; margin-right: 15px; fill: #eaedff; }
    </style>';
    
    foreach ($vars as $var) {
        echo '<div class="ess-debug-box">';
        echo '<div class="ess-debug-head">';
            create_artex_logo('ess-debug-logo');
        echo '<div class="ess-debug-title"><span>Debug</span> Output:</div>';
        echo '</div>';
        echo '<pre class="ess-debug-code">';
        print_r($var);
        echo '</pre>';
        echo '</div>';
    }
}

/**
 * Dump and Die
 * Outputs variable(s) and stops execution.
 *
 * @param mixed ...$vars Variables to dump.
 * @param boolean $pretty True enables pretty print.
 * @return void
 */
function dd(...$vars): void
{
    pp($vars);
    exit(1);
}






/*

#### ARTEX EXTRAS ##################################################### */

/**
 * Artex Agency Logo (tm)
 * 
 * Prints the official Artex Agency brand logo in SVG format.
 * 
 * @param  string  $id The unique HTML ID attribute.
 * @return void
 */
function create_artex_logo(string $class = 'artex-agency-logo'): void
{
    echo '<svg class="'.$class.'" viewBox="0 0 936 873.37">' . NL;
    echo '<path d="M288.9,454.51l2.21-3.94c28.55-50.78,32.08-102.13,26.36-167.21-3.81-43.39-2.32-88.57,20.7-129.52,50.49-89.77,165-122.75,255.42-73.52,92.93,50.56,126.27,167.18,74.59,259.09-23.25,41.36-61,67.3-101.13,85.42-56,25.3-101.91,58.69-130.75,110h0c-28.9,51.42-30.08,107.15-25.93,169.3,2.91,43.48,2.72,88.6-20.12,129.63C340,924,225.18,957.35,134.42,908,41.49,857.41,8.15,740.79,59.83,648.88c23-41,61-65.55,100-85C213.08,537.3,260.36,505.26,288.9,454.51Z" transform="translate(-35.5 -57.47)"/>' . NL;
    echo '<circle cx="746.5" cy="683.87" r="189.5"/>' . NL;
    echo '</svg>' . NL;
}