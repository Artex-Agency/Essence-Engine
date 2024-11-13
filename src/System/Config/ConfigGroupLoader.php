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
 * ConfigGroupLoader
 *
 * Loads and manages groups of configuration files based on templates or patterns.
 * This loader provides a simple, flexible approach to load multiple configuration files as a group.
 *
 * @package    Artex\Essence\Engine\System\Config
 * @category   Configuration
 * @access     public
 */
class ConfigGroupLoader extends ConfigLoader 
{
    protected string $basePath;
    protected bool $makeUnique;  // Centralize this setting

    /**
     * Constructor for ConfigGroupLoader
     *
     * @param string $basePath Base directory for configuration files
     * @param bool $makeUnique Whether to group keys with unique identifiers
     */
    public function __construct(string $basePath, bool $makeUnique = false)
    {
        $this->basePath = $basePath;
        $this->makeUnique = $makeUnique;
    }

    /**
     * Load configurations by a naming template (e.g., 'system.*').
     *
     * @param string $template Template for grouping config files (e.g., 'system')
     * @return array Merged configuration data
     */
    public function loadByTemplate(string $template): array
    {
        $configs = [];

        // Scan the base directory for matching files
        $files = glob("{$this->basePath}/{$template}.*.php");
        
        foreach ($files as $file) {
            $fileData = require $file;
            $configData = $this->makeUnique ? $this->applyUniqueKeys($fileData, $file) : $fileData;
            $configs = array_merge($configs, $configData);
        }

        return $configs;
    }

    /**
     * Optionally make keys unique by prepending the filename.
     *
     * @param array $data Config data to process
     * @param string $file The filename for unique keying
     * @return array Modified data with unique keys
     */
    private function applyUniqueKeys(array $data, string $file): array
    {
        $groupName = pathinfo($file, PATHINFO_FILENAME);
        $groupedData = [];

        foreach ($data as $key => $value) {
            $groupedData["{$groupName}.{$key}"] = $value;
        }

        return $groupedData;
    }
}