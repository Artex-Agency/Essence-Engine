<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config;

use \is_file;
use \is_array;
use \pathinfo;
use Artex\Essence\Engine\System\Config\ConfigParserFactory;

/**
 * Config
 * 
 * Manages application configuration data with efficient loading,
 * organization, and retrieval of settings. Supports grouped 
 * configurations and unique key generation per file.
 *
 * @package    Artex\Essence\Engine\System\Config
 * @category   Configuration
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 */
class Config
{
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
     * Default configuration directory path.
     *
     * @var string|null
     */
    private ?string $defaultPath = null;

    /**
     * Constructor
     *
     * @param string|null $defaultPath Default path for config files (optional).
     */
    public function __construct(?string $defaultPath = null)
    {
        $this->defaultPath = $defaultPath;
    }

    /**
     * Loads configuration data from a file.
     *
     * Reads the specified file, parses its contents, and merges 
     * the data into the existing configuration. Optionally, groups
     * configuration keys by the file's base name to create unique keys.
     *
     * @param string|null $filePath   Absolute or relative path to the file.
     * @param bool        $makeUnique Whether to group keys by file base name.
     * 
     * @return bool  True if the configuration was successfully loaded.
     */
    public function load(?string $filePath = null, bool $makeUnique = true): bool
    {
        $filePath = $this->resolveFilePath($filePath);
        if (!is_file($filePath)) {
            return false;
        }

        $parser = ConfigParserFactory::createParser($filePath);
        if (!$parser) {
            return false;
        }

        $data = $parser->parse($filePath);
        if (!is_array($data)) {
            return false;
        }

        return $makeUnique ? $this->groupData($data, $filePath) : $this->merge($data);
    }

    /**
     * Resolve the full file path by combining the default path with the specified file.
     *
     * @param string|null $filePath The specified file path or filename (optional).
     * @return string|null Full resolved file path, or null if unresolved.
     */
    private function resolveFilePath(?string $filePath): ?string
    {
        // Check if a file path is provided
        if ($filePath) {
            // If the given path is already a full path, return it
            if (is_file($filePath)) {
                return $filePath;
            }
            // If not a full path, use the default path as base
            return rtrim($this->defaultPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($filePath, DIRECTORY_SEPARATOR);
        }

        // Default to a path within the default directory
        return $this->defaultPath;
    }
    /**
     * Groups and sanitizes configuration keys by file base name.
     *
     * Strips the file extension and sanitizes the filename to use as a 
     * group identifier, then prepends it to each configuration key.
     * Merges grouped keys into the primary config.
     *
     * @param array  $data Parsed configuration data from the file.
     * @param string $file The file path to be processed.
     * 
     * @return bool True after processing and merging grouped data.
     */
    private function groupData(array $data, string $file): bool
    {
        $group = strtolower(trim(pathinfo($file, PATHINFO_FILENAME)));
        $group = preg_replace('/[^\w]/', '_', $group);
        $group = (strlen($group) >= 30) ? substr($group, 0, 30) : $group;

        $this->groups[$group] ??= [];

        foreach ($data as $key => $val) {
            if (!$key) continue;

            $key = strtolower($key);
            $this->groups[$group][$key] = $val;
            $this->config["$group.$key"] = $val;
        }
        
        return true;
    }

    /**
     * Adds or updates a configuration setting.
     *
     * @param string $key   The unique identifier/key for the setting.
     * @param mixed  $value The value associated with the key.
     * @return void
     */
    public function add(string $key, $value): void
    {
        $this->config[$key] = $value;
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
     * Retrieves the value of a configuration setting.
     * 
     * Looks up a configuration value by key, with an optional default
     * value if the key does not exist.
     * 
     * @param string $key     The unique identifier/key for the setting.
     * @param mixed  $default Default value returned if the key is not found.
     *
     * @return mixed  The value of the setting or the default.
     */
    final public function get(string $key, $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Retrieves all configuration settings.
     * 
     * Returns the entire configuration array.
     *
     * @return array The array containing all configuration settings.
     */
    final public function getAll(): array
    {
        return $this->config ?? [];
    }

    /**
     * Deletes a configuration entry.
     *
     * @param string $key The key to delete.
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($this->config[$key]);
        }
    }

    /**
     * Clears all configuration settings.
     * 
     * Resets the configuration array to an empty array.
     *
     * @return array The empty configuration array after clearing.
     */
    final public function clear(): array
    {
        return $this->config = [];
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
}