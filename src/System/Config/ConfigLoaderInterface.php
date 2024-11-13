<?php
namespace Artex\Essence\Engine\System\Config;

/**
 * ConfigLoaderInterface
 *
 * Interface defining methods for configuration loaders.
 *
 * @package    Artex\Essence\Engine\System\Config
 * @category   Configuration
 * @access     public
 */
interface ConfigLoaderInterface
{
    /**
     * Loads configuration data.
     *
     * @return array The loaded configuration data.
     */
    public function load(): array;

    /**
     * Gets a specific configuration value.
     *
     * @param string $key The configuration key.
     * @param mixed $default The default value if key not found.
     * @return mixed
     */
    public function get(string $key, $default = null): mixed;
}