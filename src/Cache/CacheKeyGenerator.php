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
 * CacheKeyGenerator
 * 
 * Generates unique, descriptive cache keys for caching operations. 
 * Uses namespace and data attributes to produce context-aware identifiers.
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
class CacheKeyGenerator
{
    /**
     * Generates a unique cache key.
     *
     * Uses a namespace and data attributes to create a unique identifier
     * for cache entries.
     * 
     * @param string $namespace The namespace for logical grouping.
     * @param mixed  $data      The data to base the key generation on.
     * @return string The generated cache key.
     */
    public static function generate(string $namespace, mixed $data): string
    {
        $dataString = is_array($data) ? json_encode($data) : serialize($data);
        return $namespace . ':' . md5($dataString);
    }
}