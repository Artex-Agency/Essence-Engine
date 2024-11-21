<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 #
 # ARTEX ESSENCE ⦙⦙⦙⦙⦙⦙ A FAST & FLEXIBLE FRAMEWORK
/**
 * This file is part of the Artex Essence Core framework.
 *
 * @link      https://artexessence.com/core/ Artex Software
 * @link      https://artexessence.com/core/ Project Website
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Cache;

use Artex\Essence\Engine\Services\Cache\Adapters\FileCacheAdapter;
use Artex\Essence\Engine\Services\Cache\Adapters\RedisCacheAdapter;
use Artex\Essence\Engine\Services\Cache\Adapters\CacheAdapterInterface;

/**
 * CacheManager
 *
 * Provides a unified interface for caching using various adapters.
 * Automatically selects the best available caching option, with fallbacks.
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
    /** @var CacheAdapterInterface The current cache adapter in use. */
    private CacheAdapterInterface $adapter;

    /**
     * Constructor.
     * Sets up the cache manager with the best available adapter.
     *
     * @param CacheAdapterInterface|null $adapter Specific adapter to use, or null to auto-select.
     */
    public function __construct(?CacheAdapterInterface $adapter = null)
    {
        if ($adapter) {
            $this->adapter = $adapter;
        } else {
            $this->adapter = $this->detectBestAdapter();
        }
    }

    /**
     * Gets a cached item by key.
     *
     * @param string $key The cache key.
     * @return mixed|null The cached data or null if not found.
     */
    public function get(string $key)
    {
        return $this->adapter->get($key);
    }

    /**
     * Sets a cached item by key.
     *
     * @param string $key  The cache key.
     * @param mixed $value The data to cache.
     * @param int|null $ttl Optional time-to-live in seconds.
     * @return bool True if the cache was set successfully.
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        return $this->adapter->set($key, $value, $ttl);
    }

    /**
     * Deletes a cached item by key.
     *
     * @param string $key The cache key.
     * @return bool True if the cache was deleted successfully.
     */
    public function delete(string $key): bool
    {
        return $this->adapter->delete($key);
    }

    /**
     * Clears all cache.
     *
     * @return bool True if the cache was cleared successfully.
     */
    public function clear(): bool
    {
        return $this->adapter->clear();
    }

    /**
     * Detects and sets the best available cache adapter.
     *
     * @return CacheAdapterInterface The selected cache adapter.
     */
    private function detectBestAdapter(): CacheAdapterInterface
    {
        // Example: use Redis if available, otherwise fall back to file cache
        if (class_exists('Redis')) {
            return new RedisCacheAdapter();
        }
        
        return new FileCacheAdapter('/path/to/cache/directory');
    }
}