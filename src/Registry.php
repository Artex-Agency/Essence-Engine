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
 * @package    Essence\System
 * @category   Runtime
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/ Project Website
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







    public static function setInstances(string $key, $value): void
    {
        self::$instances[$key] = $value;
    }



    
    // Private constructor to enforce singleton
    private function __construct() {}

}