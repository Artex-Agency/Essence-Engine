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

namespace Artex\Essence\Engine\System;

use \STDIN;
use \getenv;
use \strpos;
use \is_file;
use \in_array;
use \PHP_SAPI;
use \is_numeric;
use \strtolower;
use \posix_isatty;
use \php_sapi_name;
use \function_exists;
use \file_get_contents;

/**
 * System Environment
 *
 * Manages the application environment mode, configuration, and options for 
 * the Artex Essence framework and application. Supports multiple modes 
 * (production, development, etc.), configurable storage methods (global or 
 * `putenv`), and SAPI-based interface detection.
 * 
 * ### Core Features:
 * - **Environment Modes**: Supports production, development, sandbox, and staging.
 * - **Configuration Loading**: Load and parse environment variables from `.env` files.
 * - **Options Parsing**: Retrieve configuration settings dynamically with a flexible 
 *   merging strategy for default configurations.
 * - **SAPI Detection**: Detects PHP SAPI type (CLI, HTTP, etc.) and adjusts interface mode.
 *
 * ### Example Usage:
 * ```php
 * $env = new Environment('/path/to/.env', [
 *     'default_mode' => 'development',
 *     'vars_use_putenv' => true
 * ]);
 * 
 * $mode = $env->getMode();
 * $debugMode = $env->getConfig('debug', false);
 * ```
 *
 * @package    Artex\Essence\Engine\System
 * @category   System
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Environment
{
    /**
     * Configuration options for the environment class.
     *
     * @var array<string, mixed>
     */
    protected array $config = [
        'vars_use_putenv' => false,
        'vars_use_global' => true,
        'default_mode'    => 'production',
        'staging_mode'    => false,
        'sandbox_mode'    => false,
        'testing_mode'    => false,
    ];

    /**
     * Regex pattern for parsing .env key-value pairs.
     *
     * @var string
     */
    protected string $regex = '/^(?P<key>[A-Za-z._-]+[A-Za-z0-9]*)\s*[:=]\s*(?P<value>[^\r\n]*)$/m';

    /** @var array<string, string> Maps PHP SAPI names to common interface types. */
    protected array $sapis = [
        'cli'            => 'cli',      
        'phpdbg'         => 'cli',      
        'embed'          => 'embedded', 
        'fpm-fcgi'       => 'http',     
        'cgi-fcgi'       => 'http',     
        'apache2handler' => 'http',     
        'litespeed'      => 'http',     
        'srv'            => 'http',     
    ];

    /** @var array<string, mixed> Loaded environment variables */
    private array $variables = [];

    /** @var string Current environment mode */
    protected string $mode = 'production';

    /** @var string Interface type (e.g., HTTP, CLI) */
    protected string $interface = 'http';

    /**
     * Constructor to initialize the environment with configuration and optional .env file.
     *
     * @param string $file   Path to the .env file.
     * @param array  $config Additional configuration options.
     */
    public function __construct(string $file = '', array $config = [])
    {
        $this->config = array_merge($this->config, $config);

        if ($file) {
            $this->load($file);
        }
    }

    /**
     * Loads environment variables from a specified .env file.
     *
     * @param string $file Path to the .env file.
     * @return bool True if the file was successfully loaded, false otherwise.
     */
    public function load(string $file): bool
    {
        if (!is_file($file) || !$data = file_get_contents($file)) {
            return false;
        }
        return $this->parseEnvData($data);
    }

    /**
     * Parses and loads environment variables from provided data.
     *
     * @param string $data Raw content of the .env file.
     * @return bool True if parsing was successful, false otherwise.
     */
    protected function parseEnvData(string $data): bool
    {
        if (!preg_match_all($this->regex, $data, $matches, PREG_SET_ORDER)) {
            return false;
        }
        foreach ($matches as $match) {
            $this->add($match['key'], trim($match['value']));
        }
        return true;
    }

    /**
     * Adds an environment variable to storage.
     *
     * @param string $key   Environment variable key.
     * @param mixed  $value Value to set.
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        $value = $this->sanitizeValue($value);

        if ($this->config['vars_use_global']) {
            $_ENV[$key] = $value;
        }
        if ($this->config['vars_use_putenv']) {
            putenv("$key=$value");
        }
        $this->variables[$key] = $value;
    }

    /**
     * Retrieve an environment variable or default value if not set.
     *
     * @param  string $key     Variable name.
     * @param  mixed  $default Default value.
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->variables[$key] ?? $default;
    }

    /**
     * Returns all loaded environment variables.
     *
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        return $this->variables;
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
     * Sets the environment mode based on configuration and provided mode.
     *
     * @param string $mode Mode to set.
     * @return void
     */
    protected function setMode(string $mode): void
    {
        $normalizedMode = strtolower(trim($mode));
        
        $allowedModes = ['production', 'development'];
        
        foreach (['staging', 'sandbox', 'testing'] as $optionalMode) {
            if ($this->config["{$optionalMode}_mode"] === true) {
                $allowedModes[] = $optionalMode;
            }
        }
        
        $this->mode = in_array($normalizedMode, $allowedModes, true) 
                    ? $normalizedMode 
                    : $this->config['default_mode'];
    }

    /**
     * Gets the current environment mode.
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Parses the environment interface type from SAPI or other indicators.
     *
     * @return void
     */
    public function parseInterface(): void
    {
        $sapi = php_sapi_name() ?: PHP_SAPI;
        $this->interface = $this->sapis[$sapi] ?? 'unknown';

        if ($this->interface === 'cli') {
            $this->interface = $this->detectShellType();
        }
    }

    /**
     * Determines specific CLI interface types (daemon, cron, shell).
     *
     * @return string
     */
    protected function detectShellType(): string
    {
        $environment = getenv('TERM') ?: '';
        $interactive = function_exists('posix_isatty') && posix_isatty(STDIN);

        return match (true) {
            (!$environment && !$interactive) => 'daemon',
            (!$environment && str_contains(getenv('_') ?? '', 'cron')) => 'cron',
            (str_contains($environment, 'xterm')) => 'shell',
            default => 'shell',
        };
    }

    /**
     * Gets the current gateway interface.
     *
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * Checks if the interface is HTTP.
     *
     * @return bool
     */
    public function isHTTP(): bool
    {
        return $this->interface === 'http';
    }

    /**
     * Checks if the interface is CLI.
     *
     * @return bool
     */
    public function isCLI(): bool
    {
        return $this->interface === 'cli';
    }

    /**
     * Determines if the current environment is shell or similar (daemon, cron).
     *
     * @return bool True if the interface is shell-based, false otherwise.
     */
    public function isShell(): bool
    {
        return in_array($this->interface, ['shell', 'daemon', 'cron']);
    }

    /**
     * Retrieve a configuration option or default value.
     *
     * @param string $key     Config key.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
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

    /**
     * Resets all environment variables added by this loader.
     *
     * @return void
     */
    public function reset(): void
    {
        foreach ($this->variables as $key => $value) {
            if ($this->config['vars_use_global']) {
                unset($_ENV[$key]);
            }
            if ($this->config['vars_use_putenv']) {
                putenv($key);
            }
        }
        $this->variables = [];
    }
}