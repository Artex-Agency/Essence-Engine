<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\System;

use \trim;
use \getenv;
use \putenv;
use \strpos;
use \is_file;
use \in_array;
use \PHP_SAPI;
use \is_numeric;
use \strtolower;
use \array_merge;

/**
 * Environment
 * 
 * Manages and retrieves environment-specific configurations and settings.
 * Loads environment variables from files and provides access to them 
 * throughout the application.
 *
 * ### Example Usage:
 * ```php
 * $env = new Environment('/path/to/.env');
 * 
 * $mode = $env->detect('production');
 * 
 * $value = $env->get('key_name', 'default value');
 * ```
 *
 * @package    Essence\System
 * @category   Environment
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Environment
{
    /**
     * Holds all environment variables after loading.
     *
     * @var array
     */
    private array $variables = [];

    /**
     * Regex pattern for parsing .env key-value pairs.
     *
     * @var string
     */
    private string $regex = '/^(?P<key>[A-Za-z._-]+[A-Za-z0-9]*)\s*[:=]\s*(?P<value>[^\r\n]*)$/m';

    /**
     * Constructor to initialize the environment.
     *
     * Optionally loads variables from an .env file or another configuration file.
     *
     * @param string|null $envFile Path to an environment file (e.g., .env).
     */
    public function __construct(?string $envFile = null)
    {
        if ($envFile) {
            $this->loadFromFile($envFile);
        }
    }

    /**
     * Load environment variables from a specified file.
     *
     * Supports .env files or .ini format. Variables are set in $variables and
     * also in PHP's environment for global access.
     *
     * @param string $filePath Path to the environment file.
     * @return bool True if loading was successful, false otherwise.
     */
    public function loadFromFile(string $filePath): bool
    {
        if (!is_file($filePath) || !$data = file_get_contents($filePath)) {
            return false;
        }

        if (!preg_match_all($this->regex, $data, $matches, PREG_SET_ORDER)) {
            return false;
        }
        foreach ($matches as $match) {
            $this->set($match['key'], trim($match['value']));
        }
        return true;
    }

    /**
     * Sanitizes and converts values to appropriate types.
     *
     * @param string $value Raw value.
     * @return mixed
     */
    protected function sanitizeValue(string $value): mixed
    {
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float)$value : (int)$value;
        }

        $lowerValue = strtolower($value);
        return match ($lowerValue) {
            'true'  => true,
            'false' => false,
            'null'  => null,
            default => $value,
        };
    }

    /**
     * Set an environment variable.
     *
     * Sets the variable in both $variables and the PHP environment.
     *
     * @param string $key The variable name.
     * @param mixed $value The variable value.
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $value = $this->sanitizeValue($value);
        $this->variables[$key] = $value;
        putenv("{$key}={$value}");
    }

    /**
     * Retrieve an environment variable by name.
     *
     * Supports a default value if the variable does not exist.
     *
     * @param string $key The variable name.
     * @param mixed $default Default value if variable is not found.
     * @return mixed The variable's value or the default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->variables[$key] ?? getenv($key) ?: $default;
    }

    /**
     * Check if a specific environment variable exists.
     *
     * @param string $key The variable name to check.
     * @return bool True if the variable exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->variables[$key]) || getenv($key) !== false;
    }

    /**
     * Detect the current environment based on a specified variable.
     *
     * Typically checks for an `APP_ENV` variable to determine the environment.
     *
     * @param string $default The default environment if `APP_ENV` is not set.
     * @return string The current environment (e.g., 'production', 'development').
     */
    public function detect(string $default = 'production'): string
    {
        return $this->get('ENVIRONMENT', $default);
    }

    /**
     * Load environment variables from an array.
     *
     * Useful for setting variables programmatically or in tests.
     *
     * @param array $variables Associative array of environment variables.
     * @return void
     */
    public function loadFromArray(array $variables): void
    {
        foreach ($variables as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Retrieves all loaded environment variables.
     * 
     * @return array An associative array of all loaded environment variables.
     */
    public function getAll(): array
    {
        return $this->variables;
    }

    /**
     * Set Global
     * Adds the variable array to the $_ENV superglobal.
     * 
     * @return void
     */
    public function setGlobal(): void
    {
        $_ENV = array_merge($_ENV, $this->variables);
    }

    /**
     * Clear all loaded environment variables.
     * 
     * Clears all variables from $variables and optionally from PHP's environment.
     *
     * @param bool $clearGlobal Whether to clear from the global environment.
     * @return void
     */
    public function clear(bool $clearGlobal = false): void
    {
        foreach ($this->variables as $key => $value) {
            if ($clearGlobal) {
                putenv($key);
            }
            unset($this->variables[$key]);
        }
    }

    /**
     * Invoke magic method to get an environment variable by key.
     *
     * @param string $key     Variable name.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public function __invoke(string $key, mixed $default = null): mixed
    {
        return $this->get($key, $default);
    }
}