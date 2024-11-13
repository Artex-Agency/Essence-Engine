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
 * @since      1.0.0
 * @version    1.0.0
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config;

use RuntimeException;

/**
 * ConfigManager
 *
 * Manages the loading of different configuration types and orchestrates
 * individual config loaders to streamline configuration access across
 * the application.
 *
 * @package    Artex\Essence\Engine\System\Config
 * @category   Configuration
 * @access     public
 */
class ConfigManager
{
    protected array $configData = [];
    protected ConfigLoader $loader;

    public function __construct(string $configPath, ?ConfigLoader $loader = null)
    {
        $this->loader = $loader ?? new ConfigLoader($configPath);
    }

    /**
     * Load configuration based on file name or directory pattern.
     *
     * @param string $name Name of the config to load.
     * @return void
     */
    public function load(string $name): void
    {
        $this->configData[$name] = $this->loader->load($name);
    }

    /**
     * Get configuration by key with support for nested keys.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $segments = explode('.', $key);
        $data = $this->configData;

        foreach ($segments as $segment) {
            if (!isset($data[$segment])) {
                return $default;
            }
            $data = $data[$segment];
        }
        
        return $data;
    }

    /**
     * Set or override a config value, allowing dynamic updates.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $segments = explode('.', $key);
        $data =& $this->configData;

        foreach ($segments as $segment) {
            if (!isset($data[$segment])) {
                $data[$segment] = [];
            }
            $data =& $data[$segment];
        }

        $data = $value;
    }
}