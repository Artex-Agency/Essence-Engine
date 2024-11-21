<?php declare(strict_types=1);
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Part of the Artex Essence meta-framework.
 *  
 * @link      https://artexessence.com/ Project
 * @license   Artex Permissive Software License
 * @copyright 2024 Artex Agency Inc.
 */
namespace Essence\System;

use RuntimeException;

/**
 * Environment
 *
 * Provides a static utility for detecting runtime interfaces 
 * (HTTP, CLI), server details, and managing environment-specific 
 * configurations.It can also load environment variables for global 
 * application use.
 *
 * @package    Essence\System
 * @category   Environment
 * @version    1.1.0
 * @since      1.0.0
 */
class Environment
{
    /** @var array<string, mixed> Holds loaded environment variables. */
    private static array $variables = [];

    /** @var string Detected environment mode. */
    private static  ?string $mode = null;

    /** @var string|null Detected runtime interface type (e.g., 'http', 'cli'). */
    private static ?string $interface = null;

    /** @var string|null Cached server software type. */
    private static ?string $serverSoftware = null;

    /** @var array<string, string> Maps PHP SAPI names to runtime interfaces. */
    private static array $sapis = [
        'cli'            => 'cli',
        'phpdbg'         => 'cli',
        'fpm-fcgi'       => 'http',
        'cgi-fcgi'       => 'http',
        'apache2handler' => 'http',
        'litespeed'      => 'http',
    ];

    /** @var array<string, string> Common server software identifiers. */
    private static array $servers = [
        'apache'    => 'Apache',
        'nginx'     => 'Nginx',
        'iis'       => 'IIS',
        'litespeed' => 'LiteSpeed',
        'caddy'     => 'Caddy',
    ];

    /** @var bool Guard to prevent re-initialization. */
    private static bool $initialized = false;

    /**
     * Initializes the static environment class.
     *
     * Detects the runtime interface type.
     */
    public static function init(): void
    {
        if (!self::$initialized) {
            self::$interface = self::detectInterface();
            self::$initialized = true;
        }
        return;
    }

    /**
     * Detects the runtime interface type (HTTP, CLI, etc.).
     *
     * @return string The detected interface type.
     */
    private static function detectInterface(): string
    {
        $sapi = \strtolower(\php_sapi_name());
        return self::$sapis[$sapi] ?? 'unknown';
    }

    /**
     * Detects and returns the server software type.
     *
     * @return string The detected server software type.
     */
    public static function getServerSoftware(): string
    {
        if (self::$serverSoftware === null) {
            $serverSoftware = \strtolower($_SERVER['SERVER_SOFTWARE'] ?? '');
            foreach (self::$servers as $key => $name) {
                if (\strpos($serverSoftware, $key) !== false) {
                    self::$serverSoftware = $name;
                    break;
                }
            }
            self::$serverSoftware = self::$serverSoftware ?? 'Unknown';
        }
        return self::$serverSoftware;
    }

    /**
     * Load environment variables from a file.
     *
     * @param string $filePath Path to the environment file.
     * @throws RuntimeException If the file is missing or malformed.
     * @return void
     */
    public static function loadFromFile(string $filePath): void
    {
        if (!\is_file($filePath) || !$data = \file_get_contents($filePath)) {
            throw new RuntimeException("Environment file not found: {$filePath}");
        }

        $regex = '/^(?P<key>[A-Za-z._-]+[A-Za-z0-9]*)\s*[:=]\s*(?P<value>[^\r\n]*)$/m';
        if (!\preg_match_all($regex, $data, $matches, \PREG_SET_ORDER)) {
            throw new RuntimeException("Malformed environment file: {$filePath}");
        }

        foreach ($matches as $match) {
            $value = self::resolveNestedVariables(\trim($match['value']));
            self::set($match['key'], $value);
        }
    }

    /**
     * Detect the current application environment.
     *
     * @param string $default The default environment (e.g., 'production').
     * @return string The detected environment.
     */
    public static function detect(): string
    {
        if(self::$mode){
            return self::$mode;
        }
        self::$mode = (strtolower(self::get('ENVIRONMENT', 'production')));

        // Map known environments
        $knownEnvs = ['production', 'development', 'staging', 'testing'];
        if (in_array(self::$mode, $knownEnvs, true)) {
            return self::$mode;
        }
        self::$mode = (self::$mode ? self::$mode : 'production');

        // Use default as a fallback
        return self::$mode;
    }

    /**
     * Retrieve an environment variable by name.
     *
     * @param string $key     The variable name.
     * @param mixed  $default Default value if variable is not found.
     * @return mixed The variable's value or the default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$variables[$key] ?? \getenv($key) ?: $default;
    }

    /**
     * Sets an environment variable.
     *
     * @param string $key   The variable name.
     * @param mixed  $value The variable value.
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        $value = self::sanitizeValue($value);
        self::$variables[$key] = $value;
        \putenv("{$key}={$value}");
    }

    /**
     * Resolves nested variables in the format ${VAR}.
     *
     * @param string $value The raw value with potential nested variables.
     * @return string The resolved value.
     */
    private static function resolveNestedVariables(string $value): string
    {
        return \preg_replace_callback('/\${(\w+)}/', function ($matches) {
            return self::get($matches[1], '');
        }, $value);
    }

    /**
     * Sanitizes a raw value into an appropriate data type.
     *
     * @param string $value The raw value.
     * @return mixed The sanitized value.
     */
    private static function sanitizeValue(string $value): mixed
    {
        if (\is_numeric($value)) {
            return \strpos($value, '.') !== false ? (float)$value : (int)$value;
        }

        $lowerValue = \strtolower($value);
        return match ($lowerValue) {
            'true'  => true,
            'false' => false,
            'null'  => null,
            default => $value,
        };
    }

    /**
     * Checks if a specific environment variable exists.
     *
     * @param string $key The variable name to check.
     * @return bool True if the variable exists, false otherwise.
     */
    public static function has(string $key): bool
    {
        return isset(self::$variables[$key]) || \getenv($key) !== false;
    }

    /**
     * Retrieves all loaded environment variables.
     *
     * @return array<string, mixed> An associative array of all environment variables.
     */
    public static function getAll(): array
    {
        return self::$variables;
    }

    /**
     * Clears all loaded environment variables.
     *
     * @param bool $clearGlobal Whether to clear global PHP environment variables.
     * @return void
     */
    public static function clear(bool $clearGlobal = false): void
    {
        foreach (self::$variables as $key => $value) {
            if ($clearGlobal) {
                \putenv($key);
            }
            unset(self::$variables[$key]);
        }
    }

    /**
     * Gets the current runtime interface.
     *
     * @return string The runtime interface type (e.g., 'http', 'cli').
     */
    public static function getInterface(): string
    {
        return self::$interface ?? 'unknown';
    }
}

class_alias('\Essence\System\Environment', 'Environment');
Environment::init();