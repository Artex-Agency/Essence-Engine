<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Core framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Environment;

use \trim;
use \putenv;
use \is_file;
use \in_array;
use \array_keys;
use \preg_match_all;
use \PREG_SET_ORDER;
use \file_get_contents;

/**
 * Environment Variables Manager
 *
 * Manages and controls environment variables within the Artex Essence Engine.
 * This class allows environment variables to be loaded from a `.env` file, 
 * managed using either PHP’s built-in functions (`putenv`/`getenv`) or directly 
 * through the $_ENV superglobal. It also provides functionality to parse, add, 
 * retrieve, and clear environment variables in a flexible and configurable way.
 *
 * ### Core Features:
 * - **Loading**: Load and parse environment variables from a `.env` file.
 * - **Storage Options**: Configurable methods to either use PHP's environment functions or 
 *   inject variables directly into $_ENV.
 * - **Access and Modification**: Retrieve, add, and remove environment variables.
 * - **Reset and Cleanup**: Clear or reset variables as needed, including resetting at destruct.
 *
 * ### Usage Examples:
 * ```php
 * // Initialize with .env file and set method to 'env' (default)
 * $envVars = new Variables('/path/to/.env', 'env');
 * 
 * // Retrieve a variable with a default fallback
 * $debugMode = $envVars->get('DEBUG_MODE', false);
 * 
 * // Add a custom variable
 * $envVars->addVar('API_KEY', '123456');
 * 
 * // Clear all environment variables
 * $envVars->clear();
 * ```
 *
 * @package    Artex\Essence\Engine\System\Environment
 * @category   System
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Variables
{
    /**
     * Injects environment variables directly into $_ENV if enabled.
     * 
     * @var bool
     */
    protected bool $useENV = true;

    /**
     * Uses PHP’s built-in environment variable functions (putenv/getenv)
     * if enabled.
     * 
     * @var bool
     */
    protected bool $usePHP = false;

    /**
     * Collection of loaded environment variables.
     *
     * @var array|null
     */
    protected ?array $variables = null;

    /**
     * Regex pattern for parsing .env key-value pairs.
     * 
     * Matches key-value pairs, using space, colon, or equals as separators.
     *
     * @var string
     */
    protected string $regex = '/^(?P<key>[a-zA-Z._\-]+[a-zA-Z0-9]{1})\s*[:=]\s*(?P<value>[^\r\n]*)$/m';

    /**
     * Constructor to configure the environment variables and optionally
     * load a specified .env file.
     *
     * @param string $file Path to the optional .env file.
     * @param string $method The variable handling method (`env` or `php`).
     */
    public function __construct(string $file = '', string $method = 'env')
    {
        $method = strtolower(trim($method));
        if ($method === 'php') {
            $this->usePHP(true);
            $this->variables = [];
        } else {
            $this->useENV(true);
            $this->variables = $_ENV;
        }
        
        if ($file) {
            $this->load($file);
        }
    }

    /**
     * Load and parse environment variables from a .env file.
     *
     * @param string $file Path to the .env file.
     * @return bool True on success, false on failure.
     */
    public function load(string $file): bool
    {
        if (!is_file($file) || !$data = file_get_contents($file)) {
            return false;
        }
        return $this->parse($data);
    }

    /**
     * Parse the contents of a .env file and load variables into memory.
     *
     * @param string $data The content of the .env file.
     * @return bool True if successfully parsed, false otherwise.
     */
    private function parse(string $data): bool
    {
        if (!$data || !preg_match_all($this->regex, $data, $matches, PREG_SET_ORDER)) {
            return false;
        }

        foreach ($matches as $match) {
            $this->addVar($match['key'], trim($match['value']));
        }
        return true;
    }

    /**
     * Retrieve an environment variable by key, with optional default.
     *
     * @param string $key The variable key.
     * @param mixed $default A default value if the variable is not found.
     * @return mixed The variable value or default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->usePHP && isset($this->variables[$key])
            ? $this->variables[$key] ?? $default
            : $_ENV[$key] ?? $default;
    }

    /**
     * Retrieve an environment variable as a boolean.
     *
     * @param string $key The variable key.
     * @param bool $default Default boolean value if not found.
     * @return bool
     */
    public function getBool(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default);
        return in_array($value, ['true', '1', 1], true);
    }

    /**
     * Add an environment variable to the store.
     *
     * @param string $key The variable key.
     * @param mixed $value The variable value.
     * @return void
     */
    public function addVar(string $key, mixed $value): void
    {
        if ($key && $value !== '') {
            if ($this->useENV) {
                $_ENV[$key] = $value;
                return;
            }
            $this->variables[$key] = $value;
            putenv("$key=$value");
        }
    }

    /**
     * Remove an environment variable by key.
     *
     * @param string $key The variable key.
     * @return void
     */
    public function remVar(string $key): void
    {
        if ($this->usePHP && isset($this->variables[$key])) {
            putenv($key);
            unset($this->variables[$key]);
            return;
        }
        if (isset($_ENV[$key])) {
            unset($_ENV[$key]);
        }
    }

    /**
     * Clear all stored environment variables.
     *
     * @return void
     */
    public function clear(): void
    {
        if ($this->useENV) {
            $_ENV = [];
            return;
        }
        foreach (array_keys($this->variables) as $key) {
            putenv($key);
        }
        $this->variables = [];
    }

    /**
     * Enable or disable direct injection into the $_ENV superglobal.
     *
     * @param bool $enabled
     * @return void
     */
    public function useENV(bool $enabled = true): void
    {
        $this->useENV = $enabled;
        $this->usePHP = !$enabled;
    }

    /**
     * Enable or disable use of PHP’s putenv and getenv functions.
     *
     * @param bool $enabled
     * @return void
     */
    public function usePHP(bool $enabled = true): void
    {
        $this->usePHP = $enabled;
        $this->useENV = !$enabled;
    }

    /**
     * Reset all environment variables to the initial state.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->clear();
        if ($this->useENV) {
            $_ENV = $this->variables;
        }
    }

    /**
     * Destructor to reset environment variables.
     */
    public function __destruct()
    {
        $this->reset();
    }
}