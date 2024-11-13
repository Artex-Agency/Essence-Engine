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

/**
 * Cache Manager
 *
 * Manages cache adapters dynamically, providing a unified API
 * for different cache implementations (e.g., File, Redis).
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
class CacheManager
{
    /** @var CacheInterface $cache The active cache adapter instance. */
    private CacheInterface $cache;

    /**
     * CacheManager Constructor
     *
     * Initializes the cache manager with the specified adapter and configuration.
     *
     * @param string $adapter The cache adapter type (e.g., 'file', 'redis').
     * @param array  $config  Optional configuration parameters.
     * @throws InvalidArgumentException If an unsupported adapter is specified.
     */
    public function __construct(string $adapter, array $config = [])
    {
        $this->cache = match ($adapter) {
            'file'  => new FileCache($config),
            'redis' => new RedisCache($config),
            default => throw new InvalidArgumentException("Unsupported cache adapter: $adapter")
        };
    }

    /**
     * Retrieves the active cache instance.
     *
     * @return CacheInterface The active cache adapter.
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }
}