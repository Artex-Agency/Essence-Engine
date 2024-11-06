<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * Helpers
 * 
 * Core utility functions to simplify operations across the application.
 * Designed to enhance debugging, error handling, and general utility.
 *
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @package    Artex\Essence\Engine
 * @category   Base
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
 * Pretty Print
 * Outputs variable(s) in a formatted, styled HTML structure.
 *
 * @param mixed ...$vars Variables to dump.
 * @return void
 */
function pp(...$vars): void
{
    echo '<style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; color: #333; }
        .debug-box { background-color: #fff; border: 1px solid #ddd; border-radius: 5px; padding: 20px; margin: 20px; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
        .debug-title { font-weight: bold; color: #d9534f; }
        .debug-code { background-color: #f7f7f9; padding: 10px; border-radius: 5px; }
    </style>';
    
    foreach ($vars as $var) {
        echo '<div class="debug-box">';
        echo '<div class="debug-title">Debug Output:</div>';
        echo '<pre class="debug-code">';
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
function dd(...$vars, bool $pretty=false): void
{
    if(!$pretty){
        foreach ($vars as $var) {
            var_dump($var);
        }   
        exit(1); 
    }
    pp($vars);
    exit(1);
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
 * Essence Directive Presets
 *
 * @param string $name the name of the preset.
 * @return array The preset array
 */
function engine_load_directive(string $name):array
{
    $name = strtolower(trim($name));
    $name = (CFG_ENGINE_PATH . "$name.cfg.php");
    echo '<pre>';
    print_r($name);
    echo '</pre>';
    exit;
    if(!is_file(CONFIG_PATH . "/engine/presets/$name.presets.php")){
        $name = 'default';
    }
    return require(CONFIG_PATH . "/engine/presets/$name.presets.php");
}


/**
 * Sanitize and set PHP ini configurations for memory-related limits.
 *
 * This function validates and formats the value for `memory_limit`, 
 * `upload_max_filesize`, and `post_max_size` to ensure compatibility 
 * with PHP's ini settings.
 *
 * @param string $key   The ini configuration key to set.
 * @param string $value The value to sanitize and set for the ini configuration.
 * @return bool         True if successfully set, false otherwise.
 */
function set_php_ini_mem(string $key, string $value): bool
{
    // Define allowed configuration keys
    $allowedKeys = ['memory_limit', 'upload_max_filesize', 'post_max_size'];

    // Only proceed if the key is allowed
    if (!in_array($key, $allowedKeys)) {
        return false;
    }

    // Extract numeric value
    $numericValue = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Extract and normalize unit
    $unit = strtoupper(preg_replace('/[0-9.]/', '', $value)) ?: 'M';

    // Validate unit and convert if necessary
    if (!in_array($unit, ['M', 'K', 'G'])) {
        $unit = 'M'; // Default to MB if an invalid unit is provided
    }

    // Rebuild value with validated unit
    $formattedValue = $numericValue . $unit;

    // Set the ini configuration value
    return ini_set($key, $formattedValue) !== false;
}

/**
 * Sanitize and set PHP ini configurations for numeric limits.
 *
 * This function validates and formats the value for numeric ini 
 * settings like `max_execution_time`, `max_input_time`, and 
 * `max_input_vars` to ensure compatibility with PHP's ini settings.
 * 
 * ```
 * max_input_time: limits the amount of time, in seconds, that PHP 
 *                 will spend parsing input data... (e.g., POST, GET, 
 *                 and COOKIE data). This setting helps prevent PHP 
 *                 from hanging while processing large amounts of 
 *                 input data, such as when users submit large forms 
 *                 or upload many files.
 *
 * max_input_vars: limits the maximum number of input variables allowed 
 *                 per request (across GET, POST, and COOKIE data). 
 *                 This setting is a security measure to prevent PHP 
 *                 from being overwhelmed by excessive input variables, 
 *                 which could lead to memory exhaustion or 
 *                 denial-of-service attacks.
 * 
 * ```
 * @param string $key   The ini configuration key to set.
 * @param mixed $value  The value to sanitize and set for the ini config.
 * @return bool         True if successfully set, false otherwise.
 */
function set_php_ini_num(string $key, mixed $value): bool
{
    // Define allowed configuration keys
    $allowedKeys = ['max_execution_time', 'max_input_time', 'max_input_vars'];

    // Only proceed if the key is allowed
    if (!in_array($key, $allowedKeys)) {
        return false;
    }

    // Ensure the value is numeric and is an integer
    $numericValue = filter_var($value, FILTER_VALIDATE_INT);
    if ($numericValue === false) {
        return false;
    }

    // Set the ini configuration value
    return ini_set($key, (string) $numericValue) !== false;
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