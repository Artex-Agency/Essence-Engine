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

namespace Artex\Essence\Engine\Registry;
use \Artex\Essence\Engine\Registry\BaseRegistry;
use \Artex\Essence\Engine\Bootstrap\Exceptions\BootstrapException;

/**
 * Registry
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\Registry
 * @category   Registry
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class PathRegistry
{
    // The single instance of this class
    private static ?PathRegistry $instance = null;
    public static function getRegistry(): PathRegistry
    {
        return self::$instance ??= new PathRegistry();
    }


    protected string $env_file = '[root_path].env';


    protected function startup()
    {
        /** @var string ESS_ROOT The Essence Engine directory root path. */
        (defined('ESS_PATH') OR
            define('ESS_PATH', (rtrim(__DIR__, '/') . '/'))
        );

        /** @var string ESS_PATH The Essence Engine directory path. */
        (defined('ESS_ROOT') OR
            define('ESS_ROOT', (rtrim(dirname(__DIR__), '/') . '/'))
        );

        $this->primePaths['[ess_path]'] = ESS_PATH;
        $this->primePaths['[ess_root]'] = ESS_ROOT;

        // Check root path
    }
    public function essPath()


    protected function setRoot()
    {
        if(defined('ROOT_PATH')){
            $this->primePaths['[root_path]'] = ROOT_PATH;
            $this->env_file = str_replace('[root_path]', ROOT_PATH, $this->env_file);
        }
    }

    
    define('ESS_BOOT_PATH', (ESS_PATH.'System/'));
    define('ESS_BOOT_PATH', (ESS_PATH.'System/'));


    


    protected array $essPaths = 
    [
        'bootstrap'  => 'Bootstrap',
        'components' => 'Components',
        'engine'     => 'Engine',
        'exceptions' => 'Exceptions',
        'filesystem' => 'Filesystem',
        'helpers'    => 'Helpers',
        'registry'   => 'Registry',
        'resources'  => 'Resources',
        'services'   => 'Services',
        'system'     => 'System',
    ];

    public function EssPath(string $group)
    {
        $group = strtolower($group);
        if (isset($this->essPaths[$group])){
            return (ESS_PATH.$this->essPaths[$group]);
        }
        return false;
    }

    protected array $rootPaths = [
        'bin'        => '[root_path]bin/',
        'config'     => '[root_path]config/',
        'storage'    => '[root_path]storage/',
        'display'    => '[root_path]display/',
        'vendor'     => '[root_path]vendor/',
        'storage'    => '[root_path]storage/',
    ];

    protected array $storagePaths = [
        'cache'      => '[root_path]storage/cache/',
        'logs'       => '[root_path]storage/logs/',
        'files'      => '[root_path]storage/files/',
        'stats'      => '[root_path]storage/stats/',
        'logs'       => '[root_path]storage/logs/',
        'logs'       => '[root_path]storage/logs/',
    ];
    config
/** @var string CACHE_PATH The cache root directory. */
define('CACHE_PATH', (ROOT_PATH.'storage/cache/'));
/** @var string LOGS_PATH The logs root directory. */
define('LOGS_PATH',  (ROOT_PATH.'storage/logs/'));
/** @var string STATS_PATH The stats root directory. */
define('STATS_PATH', (ROOT_PATH.'storage/stats/'));


define('TEMP_PATH',  (ROOT_PATH.'storage/temp/'));

define('CACHE_PATH', (ROOT_PATH.'storage/cache/'));
/** @var string BOOTSTAP_PATH The bootstrap directory path. */
define('ESS_RESOURCE_PATH',(ESS_PATH.'Resources/'));

    protected string $ess_root  = '[ess_root]';
    protected string $ess_path  = '[ess_path]';
    protected string $root_path = '[root_path]';



    /** @var array Path storage. */
    private array $paths = [];


    /** @var array Path alia=s storage. */
    private array $aliases = [];

    

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
        // Loop through any aliases found in the path to resolve them completely
        while (preg_match('/@([\w\-]+)/', $path, $matches)) {
            $alias = '@' . $matches[1];
            if (isset($this->aliases[$alias])) {
                // Replace alias with the actual path and continue resolving
                $path = str_replace($alias, $this->aliases[$alias], $path);
            } else {
                // If alias is not found, return the original path to avoid breaking
                return $path;
            }
        }
        return $path;
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

    public static function getInstances(string $key)
    {
        return self::$instances[$key] ?? null;
    }



    // Private constructor to enforce singleton
    private function __construct() {}

}