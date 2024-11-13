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

namespace Artex\Essence\Engine\Services\Cache;

use InvalidArgumentException;
use Artex\Essence\Engine\Services\Cache\Adapters\FileCache;
use Artex\Essence\Engine\Services\Cache\Adapters\RedisCache;
use Artex\Essence\Engine\Services\Cache\Interfaces\CacheInterface;

/**
 * Cache Factory
 *
 * Factory class for instantiating appropriate cache adapter instances.
 *
 * Based on a configuration setting or environment, the CacheFactory
 * will provide instances of different cache adapters, such as FileCache 
 * or RedisCache. This allows for flexibility in selecting the cache
 * storage strategy at runtime.
 *
 * @package    Artex\Essence\Engine\Services\Cache
 * @category   Cache
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @access     public
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class CacheFactory
{
    /**
     * Creates a cache instance based on the specified type.
     *
     * @param string $type Type of cache to create (e.g., 'file', 'redis').
     * @param array  $config Optional configuration for the cache instance.
     * 
     * @return CacheInterface The cache adapter instance.
     * @throws InvalidArgumentException If the specified cache type is unsupported.
     */
    public static function createCache(string $type, array $config = []): CacheInterface
    {
        switch (strtolower($type)) {
            case 'file':
                return new FileCache($config);
            case 'redis':
                return new RedisCache($config);
            default:
                throw new InvalidArgumentException("Unsupported cache type: $type");
        }
    }
}