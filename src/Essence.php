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

namespace Essence;

use \Exception;
use \Essence\System\Registry;
use \Essence\System\ENVIRONMENT;
use \Essence\System\Error\Error;
use \Essence\Bootstrap\Bootstrap;
use \Essence\System\Debug\DebugBar;
use \Essence\System\ServiceContainer;
use \Essence\System\Error\ErrorConfig;
use \Essence\System\Error\ErrorHandler;
use \Essence\System\Error\ErrorRenderer;
use \Essence\System\Events\DebugInterface;
use \Essence\System\Events\EventDispatcher; // Replace with your actual debug bar class
/**
 * Essence framework master class
 *
 * @package    Essence
 * @category   Foundation
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/ Project Website
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

    public static function getDispatcher(): EventDispatcher
    {
        return self::$dispatcher;
    }

    /**
     * Internal Constructor
     * Private constructor to prevent direct instantiation.
     * 
     * @access private
     */
    private function __construct()
    {
        $systemClasses = [
            'benchmark' =>   \Essence\System\Benchmark::class,
            'config'    =>   \Essence\System\Config\Config::class,
            'debug'     =>   \Essence\System\Debug\Debug::class,
            'env'       =>   \Essence\System\Environment::class,
            'error'     =>   \Essence\System\Error\Error::class,
            'gateway'   =>   \Essence\System\Gateway::class,
// TODO Add Lang / Locale
            'logger'    =>   \Essence\System\Logger\LogFactory::class,
            'system'    =>   \Essence\System\System::class,
        ];

        $this->container = ServiceContainer::getInstance();

        $Render = new \Essence\Output\Render();
        $Render->include(ESS_TEMPLATE_PATH.'test.php', ['test' => 'x369x']);
        $Render->compile();

        // Load .env file
        Environment::loadFromFile(ROOT_PATH.'.env');

        // Detect the environment
        $env_mode = Environment::detect('production');
/*
        $Gateway = new \Essence\System\Gateway(
            (defined('ESS_GATEwAY') ? ESS_GATEwAY : 'http')
        );

        echo '<p><b>TYPE: </b>' . gettype($Gateway).'</p>';

    

        //$this->container
        //protected ?Benchmark $Benchmark = null;
        //protected ?Benchmark $cycleTimer = null;
        $System = new \Essence\System\System(
            new \Essence\System\Environment(),
            $Gateway
        );

        $Env = new \Essence\System\Environment(ROOT_PATH.'.env');
        echo '<p>App Name: ' . $Env('APP_NAME', 'error') . '</p>';
        echo '<p>Environment: ' . $Env->detect('production') . '</p>';

        $Gateway = new \Essence\System\Gateway(
            (defined('GATEWAY_INTERFACE') ? GATEWAY_INTERFACE : 'http')
        );

        echo '<p>Operating System: ' . \Essence\System\Server\OperatingSystem::getType() . '</p>';
        echo '<p>Server Software: ' . \Essence\System\Server\Software::get() . '</p>';

        echo '<p>Interface: ' . $Gateway->getInterface() . '</p>';
        echo '<p>Match: ' . (($Gateway->isMatch()) ? 'Yes' : 'No') . '</p>';
*/
        // Set the error log file location for this script
        ini_set('error_log', LOGS_PATH . 'error_log');

        $Config = new \Essence\System\Config\Config(CONFIG_PATH);
        $Config->load('app.cfg.php', true);
        echo '<p>App Name: ' . $Config->get('app.name', 'Error') . '</p>';

        $PathMap = new \Essence\Paths\PathMap();

        $envConfig = get_environment_config(Environment::detect());
        $Config->load($envConfig['errors'], true);
        $Config->load($envConfig['logging'], true);
        echo '<pre>';
        print_r($Config->getAll());
        echo '</pre>';


        $RuntimeConfig = new \Essence\Runtime\RuntimeConfig(
            $Config->parse('runtime.cfg.php'),
            $PathMap
        );
        echo '<pre>';
       // print_r($System->getSystemData());
        echo '</pre>';

        $Agent = new \Essence\Agent();
        echo '<pre>';
        print_r($Agent->getAgentData());
        echo '</pre>';
    




// Step 1: Configure Error Settings
$errorConfig = new ErrorConfig([
    'error_reporting'  => E_ALL, // Report all errors during development
    'display_errors'   => true,  // Display errors in the output
    'convert_to_exceptions' => true, // Convert errors to exceptions]
    'log_errors' => true,
    'log_threshold' => 1,
    'error_display_mode' => 'detailed',
    'error_template' => ESS_ERROR_VIEW.'detailed_error.php',
    'error_rendering_mode' => 'full', // Options: 'full', 'overlay', 'append'
    'detailed_error_template' => ESS_ERROR_VIEW.'detailed_error.php',
]);


// Step 2: Create Event Dispatcher
//$config = new ErrorConfig($errorConfigSettings);
$debugBar = new DebugBar();
$dispatcher = new EventDispatcher();
$renderer = new ErrorRenderer(ESS_ERROR_VIEW.'detailed_error.php', $errorConfig); // Initialize ErrorRenderer

// Step 4: Register the Error Handler
$Logger = \Essence\System\Logger\LogFactory::create(true, 0);



// Bind the logger to handle errors
$dispatcher->bind('error.occurred', function (array $errorDetails) use ($Logger) {
    $logger->error(
        sprintf(
            'Error: %s in %s on line %d',
            $errorDetails['message'],
            $errorDetails['file'],
            $errorDetails['line']
        )
    );
});

// Bind a monitoring service
$dispatcher->bind('error.occurred', function (array $errorDetails) {
    $monitoringService = new MonitoringService();
    $monitoringService->capture($errorDetails);
});

// Bind the debug bar to show error details
$dispatcher->bind('error.occurred', function (array $errorDetails) use ($debugBar) {
    $debugBar->addError($errorDetails);
});




$interpolator = new \Essence\Utils\Interpolate();
$errorHandler = new \Essence\System\Error\ErrorHandler($errorConfig, $renderer, $Logger, $debugBar, $dispatcher);
$errorHandler->register();



$metrics = $debugBar->getMetrics();




// Example of triggering an error
trigger_error("This is a user warning", E_USER_WARNING);

// Example of an uncaught exception
throw new Exception("This is a test exception");




exit;

die('ESSENCE CONSTRUCT');



        $Benchmark = new \Essence\System\Benchmark();
        $Benchmark->start(
            ((defined('ESS_START_TIME')) ? ESS_START_TIME : null)
        );

        $Server = new \Essence\System\Server\Server(
            \Essence\System\Server\OperatingSystem::getType(), 
            \Essence\System\Server\Software::get()
        );
        echo "<p>Server Hash: ".$Server->getHash()."</p>";
        echo "<p>Has Redis: ".($Server->hasExtension('Redis') ? "&check;" : "&times;")."</p>";

        $Logger->emergency("System is unusable! Immediate attention needed.", [
            'user_id' => 123,
            'file' => __FILE__,
            'line' => __LINE__,
        ]);
        
        $Logger->alert("Critical database issue detected! Action required now.", [
            'db_error_code' => 500,
            'error_message' => "Could not connect to database"
        ]);
        
        $Logger->critical("Application encountered a critical condition.", [
            'condition' => 'Memory exhaustion',
            'available_memory' => '50MB'
        ]);
        
        $Logger->error("Failed to complete user registration.", [
            'user_email' => 'example@domain.com',
            'error' => 'Email already exists'
        ]);
        
        $Logger->warning("Deprecated API being used.", [
            'api' => '/v1/old-endpoint',
            'replacement' => '/v2/new-endpoint'
        ]);
        
        $Logger->notice("New user registered successfully.", [
            'user_email' => 'newuser@domain.com'
        ]);
        
        $Logger->info("User logged in successfully.", [
            'user_id' => 456,
            'ip_address' => '192.168.1.1'
        ]);
        
        $Logger->debug("Debugging payment module.", [
            'transaction_id' => 'TXN12345',
            'status' => 'pending'
        ]);
        
        $Logger->error("Payment failed for transaction.", [
            'transaction_id' => 'TX123456',
            'error_code' => 'PAY-001',
            'user_id' => 789
        ]);
        
        echo '<pre>';
        print_r($Logger->getLog());
        echo '</pre>';

        $Benchmark->stop();
        echo '<p>Benchmark Time: ' . $Benchmark->getTime() . '</p>';
        echo '<p>Benchmark Memory Usage: ' . $Benchmark->getMemory() . '</p>';


/*

$interpolator->addStaticToken('custom_metric', 'Custom Value');
$config->setTheme('modern'); // Switch to the "modern" theme

-----


$data = [
    'message' => $exception->getMessage(),
    'file' => $exception->getFile(),
    'line' => $exception->getLine(),
    'trace' => $exception->getTraceAsString(),
];

echo $errorRenderer->render($config->getThemeTemplate('modern', 'detailed'), $data);
*/

/*

$config = new ErrorConfig();
$interpolator = new Interpolate();

// Add static tokens from config
$interpolator->addStaticTokens([
    'framework_name' => 'Artex Essence',
    'environment' => $config->get('environment', 'production'),
    'current_time' => date('Y-m-d H:i:s'),
]);

// Add dynamic tokens
$interpolator->addDynamicToken('memory_usage', fn() => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB');
$interpolator->addDynamicToken('execution_time', function () {
    return round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000, 2) . ' ms';
});
$interpolator->addDynamicToken('event_count', fn() => count($dispatcher->getListeners()));

// Inject the interpolator into ErrorRenderer or other components
$errorRenderer = new ErrorRenderer($config, $interpolator);
*/

        die('ESSENCE CONSTRUCT');
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