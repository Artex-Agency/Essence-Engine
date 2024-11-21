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

namespace Essence\System;

use \Essence\System\System;

/**
 * Runtime
 *
 * Description
 * 
 * @package    Essence\System
 * @category   System
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Runtime
{
    /** @var VERSION Sytstem Runtime version. */
    const VERSION ='1.0.0-Dev.1';

    /** @var ?Environment $Env The environment object. */
    private ?Environment $Env = null;


    protected array $system_classes = [
        'Environment' => \Essence\System\Environment::class,
        'Gateway'     => \Essence\System\Gateway::class,
        'benchmark'   => \Essence\System\Benchmark::class,
        'OS'          => \Essence\System\Server\OperatingSystem::class,
        'Software'    => \Essence\System\Server\Software::class,
    ];
    
    /**
     * Undocumented function
     *
     * @param string $vars
     */
    public function __construct(string $envPath, string $gateway)
    {
        // Set Environment
        $Env = new $this->system_classes['Environment']($envPath);

        // Set System
        $System = new System($Env,
            new $this->system_classes['Gateway'](),
            $this->system_classes['OS'], 
            $this->system_classes['Software']
        );

    }



    /**
     * Undocumented function
     *
     * @param string $vars
     */
    public function runtime(string $vars)
    {

        $Env = new $this->system_classes['Environment']()
        $System = new System(

        );

        $env_config = [
            'vars_use_putenv' => false,
            'vars_use_global' => true,
            'default_mode'    => 'production',
            'staging_mode'    => false,
            'sandbox_mode'    => false,
            'testing_mode'    => false,
        ];

        // Load Environment variables
        $this->Env = new Environment($vars);
        Registry::set('environment', $this->Env);

        pp($this->Env->getAll());
        pp($this->Env->getMode());
        pp($this->Env->getInterface());

        $log_config = [
            'enabled'   => false,
            'threshold' => 0,
        ];

        $Logger = LogFactory::create(true, 0);
        Registry::set('logger', $Logger);


        // Load config
        $Config = new Config();

        if(!$Config->load(CONFIG_PATH . 'system.php')){
            pp('CONFIG FAIL');
        }
        $Config->load(CONFIG_PATH . 'test.xml');
        
        pp($Config->getAll());

        // Register Config
        Registry::set('config', $Config);

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
        

        pp($Logger->getLog());
  
        // Register system shutdown override event
        register_shutdown_function([$this, 'onShutdown']);

        echo '<h2>ESSENCE ENGINE: RUNTIME</h2>';
    }

    /**
     * Runtime config
     *
     * @return void
     */
    public function configure()
    {

        // Set the error log file location for this script
        ini_set('error_log', LOGS_PATH . 'error_log');

        /** @var string ESS_CHARSET The default character set. Default: `UTF-8` */
        define('ESS_CHARSET', $this->Variables->get('APP_CHARSET', 'UTF-8'));
        set_php_ini('default_charset', ESS_CHARSET);

        // Set timezone
        setTimeZone($this->Variables->get('APP_TIMEZONE', 'America/New_York'));

        // Load server config
        $directives = engine_load_directive('server');
        foreach($directives as $key => $val){
            set_php_ini($key, $val);
        }
        unset($directives, $key, $val);
    }


/*
define('SECURE_NONE',   0);
define('SECURE_LOW',    3);
define('SECURE_LAX',    6);
define('SECURE_STRICT', 9);

session.name
session.use_strict_mode
session.use_only_cookies
session.use_cookies
session.cookie_path = /
session.cookie_domain
session.cookie_secure
session.cookie_samesite ['', 'Lax', 'Strict']
session.sid_length = 32(min)


$arr = [
    0 => [ // SECURE_NONE
        'session.use_strict_mode'  => false,
        'session.use_cookies'      => true,
        'session.use_only_cookies' => false,
        'session.cookie_secure'    => false,
        'session.cookie_samesite'  => ''
    ],
    3 => [ // SECURE_MIN
        'session.use_strict_mode'  => false,
        'session.use_cookies'      => true,
        'session.use_only_cookies' => false,
        'session.cookie_secure'    => true,
        'session.cookie_samesite'  => ''
    ],
    6 => [ // SECURE_LAX
        'session.use_strict_mode'  => true,
        'session.use_cookies'      => true,
        'session.use_only_cookies' => true,
        'session.cookie_secure'    => true,
        'session.cookie_samesite'  => 'Lax'
    ],
    9 => [ // SECURE_STRICT
        'session.use_strict_mode'  => true,
        'session.use_cookies'      => true,
        'session.use_only_cookies' => true,
        'session.cookie_secure'    => true,
        'session.cookie_samesite'  => 'Strict'
    ]
]
*/


 // Runtime Process
 // #######################################

    public function terminate()
    {

    }


 // Runtime Protocols
 // #######################################

    public function do_load()
    {
        $this->loaded = true;
    }

    public function do_boot()
    {
        $this->booted = true;
    }

    public function do_output()
    {
        $this->output = true;
    }

    public function do_crash()
    {
        $this->crashed = true;
    }

    public function do_exit()
    {
        $this->exited = true;
    }



 // Runtime Events
 // #######################################

    public function onLoad()
    {
        echo '<h3>ESSENCE STATE: Loading</h3>';
    }

    public function onBoot()
    {
        echo '<h3>ESSENCE STATE: Booted</h3>';
    }

    public function onError()
    {
        echo '<h3>ESSENCE STATE: Errored</h3>';
    }




 // SHUTDOWN
 // #######################################

 


    public function onShutdown()
    {
        // Termintate 
        $this->terminate();
        echo '<h3>ESSENCE STATE: Shutdown</h3>';
    }




    /**
     * Destructor
     */
    public function __destruct()
    {
        // Destruct
    }
}