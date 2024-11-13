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

namespace Artex\Essence\Engine\System\Cache\Adapters;

use Redis;
use RuntimeException;
use Artex\Essence\Engine\System\Cache\CacheItem;
use Artex\Essence\Engine\System\Cache\Interfaces\CacheInterface;

/**
 * Redis Cache Adapter
 *
 * Provides caching using Redis, suitable for distributed environments.
 * This class leverages PHP's Redis extension to communicate with a Redis
 * server for storing and retrieving cache items.
 *
 * @package    Artex\Essence\Engine\System\Cache\Adapters
 * @category   Caching
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @license    Artex Permissive Software License (APSL)
 */
class RedisCache implements CacheInterface
{
    /**
     * Redis instance.
     *
     * @var Redis
     */
    protected Redis $redis;

    /**
     * Constructor
     *
     * Initializes the Redis connection with configuration settings.
     *
     * @param array $config Redis configuration parameters (host, port, timeout).
     */
    public function __construct(array $config = [])
    {
        $this->redis = new Redis();
        $connected = $this->redis->connect(
            $config['host'] ?? '127.0.0.1',
            $config['port'] ?? 6379,
            $config['timeout'] ?? 2.5
        );

        if (!$connected) {
            throw new RuntimeException('Could not connect to Redis server.');
        }
    }

    /**
     * Saves an item in the Redis cache.
     *
     * @param string $key Unique identifier for the cache item.
     * @param mixed  $value Data to be cached.
     * @param int    $ttl Time-to-live in seconds for the cache item.
     * @return bool True if item was successfully saved.
     */
    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        $serializedValue = serialize($value);
        return $this->redis->setex($key, $ttl, $serializedValue);
    }

    /**
     * Retrieves an item from the Redis cache.
     *
     * @param string $key Unique identifier for the cache item.
     * @return mixed|null The cached data, or null if not found.
     */
    public function get(string $key): mixed
    {
        $data = $this->redis->get($key);
        return $data !== false ? unserialize($data) : null;
    }

    /**
     * Deletes an item from the Redis cache.
     *
     * @param string $key Unique identifier for the cache item.
     * @return bool True if item was successfully deleted.
     */
    public function delete(string $key): bool
    {
        return $this->redis->del($key) > 0;
    }

    /**
     * Clears all data from the Redis cache.
     *
     * @return bool True if all items were successfully cleared.
     */
    public function clear(): bool
    {
        return $this->redis->flushDB();
    }
}