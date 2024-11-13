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
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Services\Cache\Adapters;

use Artex\Essence\Engine\Services\Cache\CacheItem;
use Artex\Essence\Engine\Services\Cache\Interfaces\CacheInterface;

/**
 * In-Memory Cache Adapter
 *
 * Provides caching in memory, suitable for short-lived data caching within
 * a single script execution. Data will be lost after the script terminates.
 *
 * @package    Artex\Essence\Engine\Services\Cache\Adapters
 * @category   Cache
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @access     public
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class MemoryCache implements CacheInterface
{
    /**
     * Internal storage for cache items.
     *
     * @var array
     */
    private array $cache = [];

    /**
     * Saves an item in the memory cache.
     *
     * @param string $key Unique identifier for the cache item.
     * @param mixed  $value Data to be cached.
     * @param int    $ttl Time-to-live in seconds for the cache item.
     * @return bool True if item was successfully saved.
     */
    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        $expiration = time() + $ttl;
        $this->cache[$key] = new CacheItem($key, $value, $expiration);
        return true;
    }

    /**
     * Retrieves an item from the memory cache.
     *
     * @param string $key Unique identifier for the cache item.
     * @return mixed|null The cached data, or null if not found or expired.
     */
    public function get(string $key): mixed
    {
        if (isset($this->cache[$key])) {
            $item = $this->cache[$key];
            if (!$item->isExpired()) {
                return $item->getValue();
            }
            // Remove expired item
            unset($this->cache[$key]);
        }
        return null;
    }

    /**
     * Deletes an item from the memory cache.
     *
     * @param string $key Unique identifier for the cache item.
     * @return bool True if item was successfully deleted.
     */
    public function delete(string $key): bool
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
            return true;
        }
        return false;
    }

    /**
     * Clears all data from the memory cache.
     *
     * @return bool True if all items were successfully cleared.
     */
    public function clear(): bool
    {
        $this->cache = [];
        return true;
    }
}