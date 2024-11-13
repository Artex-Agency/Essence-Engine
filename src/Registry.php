<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine;
use \Artex\Essence\Engine\Registry\BaseRegistry;
use \Artex\Essence\Engine\Registry\PathRegistry;
use \Artex\Essence\Engine\Components\ServiceContainer;
use \Artex\Essence\Engine\Bootstrap\Exceptions\BootstrapException;

/**
 * Registry
 *
 * Description
 * 
 * @package    Artex\Essence\Engine
 * @category   Bootstrap
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Registry extends BaseRegistry
{
    // The single instance of this class
    private static ?Registry $registry = null;


    /** @var object $events Service Container. */
    protected ?object $events = null;

    private static array $instances = [];

    // Get the singleton instance
    public static function getRegistry(): Registry
    {
        return self::$instance ??= new Registry();
    }



    /** @var array Path alia=s storage. */
    private array $aliases = [];


    /** @var array Path storage. */
    private array $paths = [];
    

    /**
     * Sets an alias for a directory or URL path.
     *
     * @param string $name Alias name, prefixed with '@'.
     * @param string $path Actual directory or URL path.
     * @return void
     */
    public function setAlias(string $name, string $path): void
    {
        $name = $this->normalizeAliasName($name);
        $this->aliases[$name] = rtrim($path, '/') . '/';
    }

    /**
     * Retrieves the full path for a given alias, resolving nested aliases.
     *
     * @param string $name Alias name to retrieve.
     * @return string|null Resolved path or null if alias not found.
     */
    public function getAlias(string $name): ?string
    {
        $name = $this->normalizeAliasName($name);

        if (!isset($this->aliases[$name])) {
            return null;
        }
        return $this->resolveNestedAlias($this->aliases[$name]);
    }

    /**
     * Checks if an alias exists in the aliases array.
     *
     * @param string $name Alias name.
     * @return bool True if exists, false otherwise.
     */
    public function hasAlias(string $name): bool
    {
        $name = $this->normalizeAliasName($name);
        return isset($this->aliases[$name]);
    }

    /**
     * Resolves any nested aliases within a path string.
     *
     * @param string $path Path that may contain nested aliases.
     * @return string Resolved path.
     */
    private function resolveNestedAlias(string $path): string
    {
        return preg_replace_callback('/@(\w+)/', function ($matches) {
            $nestedAlias = '@' . $matches[1];
            return $this->aliases[$nestedAlias] ?? $matches[0];
        }, $path);
    }

    /**
     * Ensures all alias names are prefixed with '@'.
     *
     * @param string $name Alias name.
     * @return string
     */
    private function normalizeAliasName(string $name): string
    {
        return strpos($name, '@') === 0 ? $name : '@' . $name;
    }





    public static function setInstances(string $key, $value): void
    {
        self::$instances[$key] = $value;
    }



    
    // Private constructor to enforce singleton
    private function __construct() {}

}