<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Core framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @version   1.0.0
 * @since     1.0.0
 * @category  Data Service
 * @package   Artex\Essence\Engine\Services\Redis
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Services\Redis;

use Redis;
use RuntimeException;

/**
 * RedisConnector
 *
 * Manages connections to a Redis server using configuration parameters
 * from the RedisConfig class. This connector establishes and closes
 * connections and performs any required authentication.
 *
 * @package    Artex\Essence\Engine\Services\Redis
 * @category   Data Service
 */
class RedisConnector
{
    /** @var Redis|null The Redis connection instance. */
    private ?Redis $redis = null;

    /** @var RedisConfig The Redis configuration details. */
    private RedisConfig $config;

    /**
     * RedisConnector constructor.
     *
     * @param RedisConfig $config Configuration instance with connection details.
     */
    public function __construct(RedisConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Establishes a connection to the Redis server with the specified configuration.
     * If a password is set, it attempts to authenticate with the Redis server.
     *
     * @return Redis The connected Redis instance.
     *
     * @throws RuntimeException If the connection or authentication fails.
     */
    public function connect(): Redis
    {
        $this->redis = new Redis();

        // Attempt to connect to Redis server
        if (!$this->redis->connect($this->config->getHost(), $this->config->getPort())) {
            throw new RuntimeException(
                "Could not connect to Redis at {$this->config->getHost()}:{$this->config->getPort()}."
            );
        }

        // Authenticate if a password is set
        if ($this->config->requiresPassword() && !$this->redis->auth($this->config->getPassword())) {
            throw new RuntimeException("Redis authentication failed.");
        }

        return $this->redis;
    }

    /**
     * Disconnects from the Redis server by closing the active connection.
     *
     * @return void
     */
    public function disconnect(): void
    {
        if ($this->redis !== null) {
            $this->redis->close();
            $this->redis = null;
        }
    }

    /**
     * Checks if the Redis instance is connected.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected(): bool
    {
        return $this->redis !== null && $this->redis->isConnected();
    }
}