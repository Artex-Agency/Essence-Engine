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

namespace Artex\Essence\Engine\System;

use \Artex\Essence\Engine\System\Environment\Variables;

/**
 * Runtime
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\System
 * @category   System
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
class Runtime
{


    protected array $classes = [
        'variables' => \Artex\Essence\Engine\System\Environment\Variables::class
    ];

    protected ?Variables $Variables = null;

    protected bool $configured = false;

    protected bool $errored  = false;

    protected bool $loaded  = false;
    protected bool $booted  = false;
    protected bool $output  = false;
    protected bool $crashed = false;
    protected bool $exited  = false;

    public function __construct(string $envFile='')
    {
        echo '<h2>ESSENCE ENGINE: RUNTIME</h2>';

        // Load environment variables
        $class = ($this->classes['variables']);
        $this->Variables = new $class($envFile);

        echo '<pre>';
        print_r($_ENV);
        echo '</pre>';

    }


 // Runtime Process
 // #######################################

    public function configure()
    {

        /** @var string ESS_CHARSET The default character set. Default: `UTF-8` */
        define('ESS_CHARSET', $this->Variables->get('APP_CHARSET', 'UTF-8'));
        ini_set('default_charset', ESS_CHARSET);

        /** @var string ESS_TIMEZONE The application default timezone. Default: `America/New_York` */
        define('ESS_TIMEZONE', $this->Variables->get('APP_TIMEZONE', 'America/New_York'));
        date_default_timezone_set(ESS_TIMEZONE);

        // PHP
        ini_set('max_execution_time', $Config('MAX_EXECUTION_TIME', '36') );

        // PHP upload max filesize
        set_php_ini_mem('upload_max_filesize', '2M');

        // PHP post max size
        set_php_ini_mem('post_max_size', '2M');

        // PHP memory limit
        set_php_ini_mem('memory_limit', '2M');

        $this->Variables->get('APP_CHARSET', 'UTF-8');


        $this->Variables->get('APP_TIMEZONE', 'America/New_York');

        $this->Variables->get('APP_CHARSET', 'UTF-8');
        
        
// Set timezone
    setTimeZone( $Config('TIMEZONE', 'America/New_York') );



// Set the maximum script execution time
    ini_set('max_execution_time', $Config('MAX_EXECUTION_TIME', '36') );

// Set the maximum upload filesize
    ini_set('upload_max_filesize', 
        formatConfigMB( $Config('MAX_UPLOAD_SIZE', '2M'))
    );

// Set the max size for a post
    ini_set('post_max_size', 
        formatConfigMB( $Config('MAX_POST_SIZE', '2M') )
    );

// Set the maximum memory limit
    ini_set('memory_limit', 
        formatConfigMB( $Config('MAX_MEMORY_LIMIT', '2M') )
    );

    }


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