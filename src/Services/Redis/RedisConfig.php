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
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Services\Redis;

use \filter_var;
use \FILTER_VALIDATE_IP;
use \FILTER_VALIDATE_DOMAIN;
use \InvalidArgumentException;

/**
 * RedisConfig
 *
 * Manages configuration for Redis connections, including host, port, and optional password.
 * This configuration can be extended or adapted for various Redis configurations
 * by adjusting the default values or overriding properties as needed.
 *
 * @package    Artex\Essence\Engine\Services\Redis
 * @category   Data Service
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @license    Artex Permissive Software License (APSL)
 */
class RedisConfig
{
    /** @var string The Redis server hostname or IP address. */
    private string $host;

    /** @var int The Redis server port number.*/
    private int $port;

    /** @var string|null Optional password for authenticating with Redis. */
    private ?string $password;

    /**
     * RedisConfig constructor.
     *
     * @param array $config Associative array with configuration values:
     *                      - 'host': Redis host (default: '127.0.0.1')
     *                      - 'port': Redis port (default: 6379)
     *                      - 'password': (Optional) Password for authentication
     *
     * @throws InvalidArgumentException If any configuration value is invalid.
     */
    public function __construct(array $config = [])
    {
        $this->host = $config['host'] ?? '127.0.0.1';
        $this->port = (int)($config['port'] ?? 6379);
        $this->password = $config['password'] ?? null;

        $this->validateConfig();
    }

    /**
     * Validates the configuration values for the Redis connection.
     *
     * @return void
     *
     * @throws InvalidArgumentException If the host or port configuration value is invalid.
     */
    private function validateConfig(): void
    {
        if (!$this->isValidHost($this->host)) {
            throw new InvalidArgumentException("Invalid Redis host specified.");
        }

        if ($this->port <= 0 || $this->port > 65535) {
            throw new InvalidArgumentException("Invalid Redis port specified.");
        }
    }

    /**
     * Validates the host as a valid IP address or domain name.
     *
     * @param string $host The host to validate.
     * @return bool True if host is valid, false otherwise.
     */
    private function isValidHost(string $host): bool
    {
        return filter_var($host, FILTER_VALIDATE_IP) || filter_var($host, FILTER_VALIDATE_DOMAIN) || $host === 'localhost';
    }

    /**
     * Retrieves the Redis host.
     *
     * @return string The Redis server host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Retrieves the Redis port.
     *
     * @return int The Redis server port.
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Retrieves the Redis password.
     *
     * @return string|null The password, or null if none is set.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Checks if a password is required for the Redis connection.
     *
     * @return bool True if a password is required, false otherwise.
     */
    public function requiresPassword(): bool
    {
        return !is_null($this->password);
    }
}