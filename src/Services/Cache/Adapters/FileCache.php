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

namespace Artex\Essence\Engine\Services\Cache\Adapters;

use Artex\Essence\Engine\Services\Cache\CacheInterface;

/**
 * File-Based Cache Adapter
 * 
 * Provides a file-based caching mechanism. Caches are stored
 * as serialized files within a specified directory.
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
class FileCache implements CacheInterface
{
    /** @var string $cacheDir Directory for storing cache files */
    private string $cacheDir;

    /**
     * FileCache Constructor
     *
     * Initializes the file cache with a specified directory.
     *
     * @param array $config Configuration array with 'cache_dir' path.
     */
    public function __construct(array $config = [])
    {
        $this->cacheDir = $config['cache_dir'] ?? sys_get_temp_dir();
    }

    /**
     * Retrieves an item from the cache by key.
     *
     * @param string $key     The unique identifier for the cached item.
     * @param mixed  $default The default value to return if not found.
     * @return mixed The cached value or default if not found or expired.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->getCacheFilePath($key);
        if (!file_exists($file) || time() > filemtime($file)) {
            return $default;
        }
        return unserialize(file_get_contents($file)) ?: $default;
    }

    /**
     * Stores an item in the cache with a specified TTL.
     *
     * @param string $key   The unique identifier for the cached item.
     * @param mixed  $value The value to cache.
     * @param int    $ttl   Time-to-live in seconds; 0 for infinite.
     * @return bool True on success; otherwise false.
     */
    public function set(string $key, mixed $value, int $ttl = 0): bool
    {
        $file = $this->getCacheFilePath($key);
        file_put_contents($file, serialize($value));
        touch($file, time() + $ttl);
        return true;
    }

    /**
     * Deletes an item from the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @return bool True on success; otherwise false.
     */
    public function delete(string $key): bool
    {
        $file = $this->getCacheFilePath($key);
        return file_exists($file) ? unlink($file) : false;
    }

    /**
     * Clears all items from the file-based cache.
     *
     * @return bool True if cache was successfully cleared.
     */
    public function clear(): bool
    {
        foreach (glob($this->cacheDir . DIRECTORY_SEPARATOR . '*.cache') as $file) {
            unlink($file);
        }
        return true;
    }

    /**
     * Checks if a cache item exists and is not expired.
     *
     * @param string $key The unique identifier for the cached item.
     * @return bool True if item exists and is valid; otherwise false.
     */
    public function has(string $key): bool
    {
        $file = $this->getCacheFilePath($key);
        return file_exists($file) && time() <= filemtime($file);
    }

    /**
     * Generates the file path for a cache item based on its key.
     *
     * @param string $key The unique identifier for the cached item.
     * @return string The full file path to the cache item.
     */
    private function getCacheFilePath(string $key): string
    {
        return $this->cacheDir . DIRECTORY_SEPARATOR . md5($key) . '.cache';
    }
}