<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Database\Drivers\Redis;

use \Redis;
use \RuntimeException;
use \Essence\Database\Drivers\Redis\RedisConfig;
use \Essence\Database\Drivers\Redis\RedisConnector;

/**
 * RedisControl
 *
 * Provides a centralized interface for managing Redis connections and operations.
 * Supports CRUD operations, key management, transactions, and more.
 *
 * @package    Essence\Database\Drivers\Redis
 * @category   Redis Service
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @license    Artex Permissive Software License (APSL)
 */
class RedisControl
{
    /** @var RedisConnector Manages Redis connections. */
    private RedisConnector $connector;

    /** @var Redis|null Active Redis connection instance. */
    private ?Redis $connection = null;

    /**
     * RedisControl constructor.
     *
     * Initializes a new RedisControl instance with the specified configuration.
     *
     * @param array $config Configuration array with values for host, port, password, etc.
     */
    public function __construct(array $config)
    {
        $redisConfig = new RedisConfig($config);
        $this->connector = new RedisConnector($redisConfig);
    }

    /**
     * Establishes a connection to Redis.
     *
     * @return void
     * @throws RuntimeException If connection fails.
     */
    public function connect(): void
    {
        $this->connection = $this->connector->connect();
    }

    /**
     * Disconnects from Redis.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->connector->disconnect();
        $this->connection = null;
    }

    /**
     * Checks if Redis is connected.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected(): bool
    {
        return $this->connection && $this->connection->isConnected();
    }

    /**
     * Sets a key-value pair in Redis with an optional TTL.
     *
     * @param string $key   Key to set.
     * @param mixed  $value Value to store.
     * @param int    $ttl   Time to live in seconds (0 for no expiration).
     * @return bool True if the operation succeeded, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function set(string $key, mixed $value, int $ttl = 0): bool
    {
        $this->ensureConnected();
        return $ttl > 0
            ? $this->connection->setex($key, $ttl, $value)
            : $this->connection->set($key, $value);
    }

    /**
     * Retrieves the value of a given key from Redis.
     *
     * @param string $key The key to retrieve.
     * @return mixed The value of the key, or false if not found.
     * @throws RuntimeException If Redis is not connected.
     */
    public function get(string $key): mixed
    {
        $this->ensureConnected();
        return $this->connection->get($key);
    }

    /**
     * Deletes a key from Redis.
     *
     * @param string $key The key to delete.
     * @return bool True if the key was deleted, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function delete(string $key): bool
    {
        $this->ensureConnected();
        return (bool)$this->connection->del($key);
    }

    /**
     * Alias for delete() method to remove a key from Redis.
     *
     * @param string $key The key to delete.
     * @return bool True if the key was deleted, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function del(string $key): bool
    {
        return $this->delete($key);
    }

    /**
     * Sets a new value for a key and returns the old value.
     *
     * @param string $key The key to set.
     * @param mixed  $value The new value.
     * @return mixed The old value of the key.
     * @throws RuntimeException If Redis is not connected.
     */
    public function getSet(string $key, mixed $value): mixed
    {
        $this->ensureConnected();
        return $this->connection->getSet($key, $value);
    }

    /**
     * Increments the integer value of a key by one.
     *
     * @param string $key The key to increment.
     * @return int The new value after incrementing.
     * @throws RuntimeException If Redis is not connected.
     */
    public function increment(string $key): int
    {
        $this->ensureConnected();
        return $this->connection->incr($key);
    }

    /**
     * Decrements the integer value of a key by one.
     *
     * @param string $key The key to decrement.
     * @return int The new value after decrementing.
     * @throws RuntimeException If Redis is not connected.
     */
    public function decrement(string $key): int
    {
        $this->ensureConnected();
        return $this->connection->decr($key);
    }

    /**
     * Appends a value to the existing value of a key.
     *
     * @param string $key The key to append to.
     * @param string $value The value to append.
     * @return int The length of the string after appending.
     * @throws RuntimeException If Redis is not connected.
     */
    public function append(string $key, string $value): int
    {
        $this->ensureConnected();
        return $this->connection->append($key, $value);
    }

    /**
     * Gets the length of the value stored at a key.
     *
     * @param string $key The key to check.
     * @return int The length of the value.
     * @throws RuntimeException If Redis is not connected.
     */
    public function strlen(string $key): int
    {
        $this->ensureConnected();
        return $this->connection->strlen($key);
    }

    /**
     * Checks if a key exists in Redis.
     *
     * @param string $key The key to check.
     * @return bool True if the key exists, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function exists(string $key): bool
    {
        $this->ensureConnected();
        return (bool)$this->connection->exists($key);
    }

    /**
     * Sets an expiration time on a key.
     *
     * @param string $key The key to set expiration for.
     * @param int $seconds Time in seconds for the key to expire.
     * @return bool True if the expiration was set, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function expire(string $key, int $seconds): bool
    {
        $this->ensureConnected();
        return $this->connection->expire($key, $seconds);
    }

    /**
     * Renames a key to a new name.
     *
     * @param string $oldKey The current key name.
     * @param string $newKey The new key name.
     * @return bool True if the key was renamed successfully, false otherwise.
     * @throws RuntimeException If Redis is not connected or old key does not exist.
     */
    public function rename(string $oldKey, string $newKey): bool
    {
        $this->ensureConnected();
        if (!$this->exists($oldKey)) {
            throw new RuntimeException("Key $oldKey does not exist.");
        }
        return $this->connection->rename($oldKey, $newKey);
    }

    /**
     * Removes all keys from the current database.
     *
     * @return bool True if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function flushDb(): bool
    {
        $this->ensureConnected();
        return $this->connection->flushDB();
    }

    /**
     * Removes all keys from all databases.
     *
     * @return bool True if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function flushAll(): bool
    {
        $this->ensureConnected();
        return $this->connection->flushAll();
    }

    /**
     * Watches one or more keys for changes before a transaction.
     *
     * @param string ...$keys Keys to watch.
     * @return bool True if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function watch(string ...$keys): bool
    {
        $this->ensureConnected();
        return $this->connection->watch(...$keys);
    }

    /**
     * Cancels all watched keys.
     *
     * @return bool True if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function unwatch(): bool
    {
        $this->ensureConnected();
        return $this->connection->unwatch();
    }

    /**
     * Marks the start of a transaction block.
     *
     * @return bool True if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function multi(): bool
    {
        $this->ensureConnected();
        $this->connection->multi();
        return true;
    }

    /**
     * Executes all commands issued after MULTI.
     *
     * @return array|bool Array of replies if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function exec(): array|bool
    {
        $this->ensureConnected();
        return $this->connection->exec();
    }

    /**
     * Discards all commands issued after MULTI.
     *
     * @return bool True if successful, false otherwise.
     * @throws RuntimeException If Redis is not connected.
     */
    public function discard(): bool
    {
        $this->ensureConnected();
        return $this->connection->discard();
    }

    /**
     * Ensures the Redis connection is active before performing operations.
     *
     * @return void
     * @throws RuntimeException If Redis is not connected.
     */
    private function ensureConnected(): void
    {
        if (!$this->isConnected()) {
            throw new RuntimeException("Redis is not connected.");
        }
    }
}