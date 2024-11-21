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

namespace Essence\System\Config;

use \ltrim;
use \rtrim;
use \is_dir;
use \strtok;
use \is_file;
use \basename;
use \is_array;
use \strtolower;
use Essence\System\Config\ConfigParserFactory;

/**
 * Config
 * 
 * Manages application configuration data with efficient loading,
 * organization, and retrieval of settings. Supports grouped 
 * configurations and unique key generation per file.
 * 
 * @package    Essence\System\Config
 * @category   Configuration
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Config
{
    /**
     * Default configuration directory path.
     *
     * @var string|null
     */
    private ?string $configPath = null;

    /**
     * Primary configuration storage.
     *
     * Stores all configuration key-value pairs, including unique keys
     * generated from grouped configurations.
     *
     * @var array
     */
    private array $config = [];

    /**
     * Grouped configuration storage.
     *
     * Stores configurations by groups, allowing access by original
     * source filename or unique key.
     *
     * @var array
     */
    private array $groups = [];

    /**
     * Keys that should remain as arrays, even if flattening is enabled.
     *
     * @var array<string>
     */
    private array $keepNested = [];

    /**
     * Constructor
     *
     * @param string|null $configPath Default path for config files (optional).
     */
    public function __construct(?string $configPath = null)
    {
        $this->setConfigPath($configPath);
    }

    /**
     * Set the configuration path.
     *
     * @param string|null $filePath Absolute or relative path to the config directory.
     * @return void
     */
    public function setConfigPath(?string $filePath = null): void
    {
        $this->configPath = is_dir($filePath) ? rtrim($filePath, '/') . '/' : null;
    }

    /**
     * Resolves the full file path by combining the config path with the specified file.
     *
     * @param string|null $filePath The specified file path or filename (optional).
     * @return string|null Full resolved file path, or null if unresolved.
     */
    private function resolvePath(?string $filePath): ?string
    {
        return $filePath && is_file($filePath) ? $filePath : ($this->configPath ? rtrim($this->configPath, '/') . '/' . ltrim($filePath, '/') : null);
    }

    /**
     * Parses a configuration file.
     *
     * @param string|null $filePath Absolute or relative path to the file.
     * @return array|false Parsed configuration data or false if the file does not exist.
     */
    public function parse(?string $filePath = null): array|false
    {
        $filePath = $this->resolvePath($filePath);
        if (!is_file($filePath)) {
            return false;
        }
        $parser = ConfigParserFactory::createParser($filePath);
        return $parser ? ($parser->parse($filePath) ?? []) : false;
    }

    /**
     * Loads configuration data from a file.
     *
     * @param string|null $filePath Path to the configuration file.
     * @param bool $group Whether to group keys by file base name.
     * @param bool $flatten Whether to flatten nested keys to dot notation.
     * @param array<string> $keepNested Array of keys that should remain nested.
     * @return bool True if the configuration was successfully loaded.
     */
    public function load(?string $filePath = null, bool $group = false, bool $flatten = true, array $keepNested = []): bool
    {
        $this->keepNested = $keepNested;
        return $group ? $this->loadGroup($filePath, null, $flatten) : $this->merge($this->parse($filePath) ?: [], $flatten);
    }

    /**
     * Loads configuration data as a named group.
     *
     * @param string|null $filePath Path to the configuration file.
     * @param string|null $group Optional group name. If null, a name will be generated.
     * @return bool True if the configuration was successfully loaded.
     */
    public function loadGroup(?string $filePath = null, ?string $group = null): bool
    {
        $data = $this->parse($filePath);
        if (!$data) {
            return false;
        }
        $group = strtolower($group ?: $this->generateGroupName($filePath));
        return $group ? $this->makeGroup($group, $data) : false;
    }

    /**
     * Generate a group name from the file path.
     *
     * @param string $filePath The file path from which to generate the group name.
     * @return string|false Generated group name or false if invalid.
     */
    private function generateGroupName(string $filePath): string|false
    {
        $fileName = strtolower(basename($filePath));
        $name = strtok($fileName, '.');
        return preg_replace('/[^\w]/', '_', $name) ?: false;
    }

    /**
     * Creates a configuration group, optionally flattening nested keys.
     *
     * @param string $name Group name.
     * @param array $data Configuration data to be grouped.
     * @param bool $flatten Whether to flatten nested keys to dot notation.
     * @return bool True if the group was successfully created.
     */
    private function makeGroup(string $name, array $data, bool $flatten = true): bool
    {
        foreach ($data as $key => $value) {
            $fullKey = "{$name}.{$key}";
            if ($flatten && !in_array($fullKey, $this->keepNested, true)) {
                $this->flattenAndStore($fullKey, $value);
            } else {
                $this->config[$fullKey] = $value;
            }
            $this->groups[$name][$key] = $fullKey;
        }
        return true;
    }

    /**
     * Flattens nested arrays into dot notation and stores them in the config array.
     *
     * @param string $prefix Prefix for the flattened keys.
     * @param mixed $data Nested data to be flattened.
     * @return void
     */
    private function flattenAndStore(string $prefix, mixed $data): void
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->flattenAndStore("{$prefix}.{$key}", $value);
            }
        } else {
            $this->config[$prefix] = $data;
        }
    }

    /**
     * Add a value to the configuration array.
     *
     * @param string $key Dot notation key (e.g., 'errors.display').
     * @param mixed $value Value to set in the configuration.
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    /**
     * Get a value from the configuration array.
     *
     * @param string $key Dot notation key (e.g., 'errors.display').
     * @param mixed $default Default value if key is not found.
     * @return mixed|null The value if found, or the default if the key does not exist.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Checks if a specific configuration setting exists.
     *
     * @param string $key The configuration key to check.
     * @return bool True if the setting exists; otherwise, false.
     */
    final public function has(string $key): bool
    {
        return isset($this->config[$key]);
    }

    /**
     * Retrieves all configuration settings.
     *
     * @return array The array containing all configuration settings.
     */
    final public function getAll(): array
    {
        return $this->config;
    }

    /**
     * Deletes a configuration entry.
     *
     * @param string $key The key to delete.
     * @return void
     */
    public function delete(string $key): void
    {
        unset($this->config[$key]);
    }

    /**
     * Remove a configuration group.
     *
     * @param string $group The group to remove.
     * @return void
     */
    public function remove(string $group): void
    {
        if (isset($this->groups[$group])) {
            foreach ($this->groups[$group] as $key) {
                unset($this->config[$key]);
            }
            unset($this->groups[$group]);
        }
    }

    /**
     * Merges data into the existing configuration.
     *
     * @param array $data Associative array of configuration data.
     * @return bool True if the data is merged successfully.
     */
    protected function merge(array $data): bool
    {
        $this->config = array_merge($this->config, $data);
        return true;
    }

    /**
     * Clears all configuration settings.
     * 
     * @return void
     */
    final public function clear(): void
    {
        $this->config = [];
        $this->groups = [];
    }
}