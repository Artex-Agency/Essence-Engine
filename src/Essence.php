<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence;

use \Essence\System\Registry;
use \Essence\System\ServiceContainer;
use \Artex\Essence\Engine\Bootstrap\Bootstrap;

/**
 * Bootstrap manager for initializing application components.
 *
 * @package    Essence
 * @category   Foundation
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Essence
{
    /** @var VERSION Essence class version. */
    const VERSION ='1.0.0-Dev.1';

    /** @var Essence Contains the singleton instance.  */
    private static ?Essence $instance = null;

    /** @var ServiceContainer The main service container instance. */
    private ServiceContainer $container;

    /**
     * Invoke
     * A singleton method to retrieve the Essence master class instance.
     * 
     * @access public
     * @return Essence
     */
    public static function invoke(): Essence
    {
        return self::$instance ??= new Essence();
    }

    /**
     * Internal Constructor
     * Private constructor to prevent direct instantiation.
     * 
     * @access private
     */
    private function __construct()
    {
        $this->container = ServiceContainer::getInstance();
        $this->initializeCoreServices();
    }





    /**
     * Undocumented function
     *
     * @access private
     * @return void
     */
    private function initializeCoreServices(): void
    {
        require_once ESS_PATH .'System/Config/ConfigGroupLoader.php';

        $service = [
            'Config' => \Artex\Essence\Engine\System\Config\Config::class,
            'ConfigGroupLoader' => \Artex\Essence\Engine\System\Config\ConfigGroupLoader::class,
            'Logger' => \Artex\Essence\Engine\System\Logger\LogFactory::class,
        ];
    
        $this->container->set('Config', new $service['Config'](CONFIG_PATH));
        $this->container->set('ConfigGroupLoader', new $service['ConfigGroupLoader'](CONFIG_PATH));
        
        $Config = $this->container->get('Config');

        $configLoader = $this->container->get('ConfigGroupLoader');
        $out = $configLoader->loadByTemplate('app');
    
            
pp($out);

// Load all system-related configurations
$systemConfig = $configLoader->loadByTemplate('system');





pp($systemConfig);
/*      
    
  
    


        $this->container->set('Logger', fn() => \Artex\Essence\Engine\System\Logger\LogFactory::create(true, 0));
        $Logger = $this->container->get('Logger');
        pp($Logger);
        exit;
        

        
        exit;
*/
        
        





        //$this->container->singleton('logger', fn() => new Logger());
        //$this->container->singleton('errorHandler', fn() => new ErrorHandler());
        //system.php
        // Add more core services as needed
    }

    /**
     * Registers core services, allowing the container to manage their 
     * lifecycle.
     *
     * @return void
     */
    private function registerCoreServices(): void
    {
        $this->container->singleton('config', function () {
            // Initialize and return configuration instance
        });
        
        $this->container->singleton('logger', function () {
            // Initialize and return logger instance
        });
    }



    private function boot()
    {
        $Bootstrap = new Bootstrap();
    }


    /**
     * Get Container
     * Gets the service container object.
     * 
     * @return ServiceContainer
     */
    public function getContainer(): ServiceContainer
    {
        return $this->container;
    }

    /**
     * Get Service
     * Get a registered service from the service container.
     *
     * @param string $serviceId The unique identifier of the service.
     * @return mixed The resolved service instance.
     */
    public function getService(string $serviceId): mixed
    {
        return $this->container->get($serviceId);
    }

    /**
     * Get Version
     *
     * @return string The version.
     */
    public function getVersion():string
    {
        return self::VERSION;
    }
}