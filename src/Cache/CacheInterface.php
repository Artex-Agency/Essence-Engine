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

/**
 * Cache Interface
 * 
 * Defines the standard methods for cache operations. This interface
 * ensures consistent behavior across different caching implementations.
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
interface CacheInterface
{
    /**
     * Retrieves an item from the cache by key.
     *
     * @param string $key     The unique identifier for the cached item.
     * @param mixed  $default The default value to return if the key is not found.
     * @return mixed The cached value or the default if not found.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Stores an item in the cache with an optional time-to-live (TTL).
     *
     * @param string $key   The unique identifier for the cached item.
     * @param mixed  $value The value to be cached.
     * @param int    $ttl   Time-to-live in seconds; 0 for infinite.
     * @return bool True on success; otherwise false.
     */
    public function set(string $key, mixed $value, int $ttl = 0): bool;

    /**
     * Deletes an item from the cache by key.
     *
     * @param string $key The unique identifier for the cached item.
     * @return bool True on successful deletion; otherwise false.
     */
    public function delete(string $key): bool;

    /**
     * Clears all items from the cache.
     *
     * @return bool True if cache was successfully cleared.
     */
    public function clear(): bool;

    /**
     * Checks if a cache item exists and is not expired.
     *
     * @param string $key The unique identifier for the cached item.
     * @return bool True if item exists and is valid; otherwise false.
     */
    public function has(string $key): bool;
}