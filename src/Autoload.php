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

namespace Artex\Essence\Engine;

use \trim;
use \ltrim;
use \rtrim;
use \strlen;
use \substr;
use \is_file;
use \strncmp;
use \str_replace;
use \RuntimeException;
use \spl_autoload_register;

/**
 * PSR-4 Autoloader
 *
 * This class implements a PSR-4 compliant autoloader for dynamically loading 
 * PHP classes based on their fully-qualified names. The autoloader is 
 * registered automatically upon instantiation.
 * 
 * @package    Artex\Essence\Engine
 * @category   Bootstrap
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
class Autoload
{
    /**
     * @var array Associative array mapping namespace prefixes to their 
     * corresponding base directories.
     */
    private array $prefixes = [];

    /**
     * Autoload constructor.
     * 
     * Registers the autoloader with the SPL autoload registry. If namespace 
     * prefix and base directory parameters are provided, the namespace will 
     * be registered.
     * 
     * @uses spl_autoload_register()
     * @param string $prefix  Optional namespace prefix to register.
     * @param string $baseDir Optional base directory for the namespace prefix.
     */
    public function __construct(string $prefix = '', string $baseDir = '')
    {
        if ($prefix && $baseDir) {
            $this->addNamespace($prefix, $baseDir);
        }
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Registers a namespace prefix and its base directory for autoloading.
     *
     * @param string $prefix   The namespace prefix.
     * @param string $baseDir  The base directory for the namespace prefix.
     * 
     * @return void
     */
    public function addNamespace(string $prefix, string $baseDir): void
    {
        // Normalize the namespace prefix and base directory
        $prefix  = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, '/') . '/';
        
        // Add the namespace and its base directory to the map
        $this->prefixes[$prefix] = $baseDir;
    }

    /**
     * Loads a class file based on its fully-qualified class name.
     *
     * @param string $class The fully-qualified class name to load.
     * 
     * @return void
     * @throws RuntimeException If the class file cannot be found.
     */
    public function loadClass(string $class): void
    {
        $class = ltrim($class, '\\');

        foreach ($this->prefixes as $prefix => $baseDir) {
            $len = strlen($prefix);

            if (strncmp($prefix, $class, $len) === 0) {
                // Extract the class name from the namespace prefix
                $relativeClass = substr($class, $len);
                
                // Build the file path from the base directory and class name
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                if (!is_file($file)){
                    // Optionally throw an exception or log the error
                    throw new RuntimeException("Class file not found: $file");
                    return;
                }
                require $file;
            }
        }
    }
}