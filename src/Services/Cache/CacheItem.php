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
 * Cache Item
 *
 * Represents a single item in the cache, encapsulating the key, value, 
 * expiration time, and associated tags. This structure allows for 
 * tracking cache entries individually, including their lifespan and
 * categorization via tags.
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
class CacheItem
{
    /**
     * Cache key identifier.
     *
     * @var string
     */
    private string $key;

    /**
     * Cached value.
     *
     * @var mixed
     */
    private mixed $value;

    /**
     * Expiration timestamp of the cache item.
     *
     * @var int
     */
    private int $expiration;

    /**
     * Tags associated with the cache item.
     *
     * @var array
     */
    private array $tags;

    /**
     * CacheItem constructor.
     *
     * @param string $key The unique identifier for the cache item.
     * @param mixed  $value The value to be cached.
     * @param int    $expiration Expiration time as a UNIX timestamp.
     * @param array  $tags Optional tags for categorization and retrieval.
     */
    public function __construct(string $key, mixed $value, int $expiration, array $tags = [])
    {
        $this->key = $key;
        $this->value = $value;
        $this->expiration = $expiration;
        $this->tags = $tags;
    }

    /**
     * Gets the cache key.
     *
     * @return string Cache key.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Gets the cache value.
     *
     * @return mixed Cache value.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Gets the expiration timestamp.
     *
     * @return int Expiration timestamp.
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * Gets the tags associated with the cache item.
     *
     * @return array Cache item tags.
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Checks if the cache item has expired.
     *
     * @return bool True if expired; otherwise false.
     */
    public function isExpired(): bool
    {
        return time() > $this->expiration;
    }
}