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

use \trim;
use \getenv;
use \putenv;
use \strpos;
use \is_file;
use \in_array;
use \PHP_SAPI;
use \is_numeric;
use \strtolower;
use \array_merge;
use \Essence\System\Gateway;
use \Essence\System\Environment;
use \Essence\System\Server\Software;
use \Essence\System\Server\OperatingSystem;

/**
 * System
 * 
 * @package    Essence\System
 * @category   System
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class System
{
    /** @var string|null $environment The environment mode. */
    private ?string $environment = 'production';

    /** @var string|null $gateway Gateway portal. */
    private ?string $gateway = null;

    /** @var string|null $iface Gateway interface. */
    private ?string $interface = null;

    /** @var string|null $serverOS operating system type. */
    private ?string $serverOS = null;

    /** @var string|null $serverDistro operating system distribution. */
    private ?string $serverDistro = null;

    /** @var string|null $serverSoftware Server host software. */
    private ?string $serverSoftware = null;


    /**
     * Constructor to initialize the environment.
     *
     * Optionally loads variables from an .env file or another configuration file.
     *
     * @param string|null $envFile Path to an environment file (e.g., .env).
     */
    public function __construct(?Environment $Env = null, ?Gateway $Gateway = null){
        $this->environment    = $Env->detect('production');
        $this->interface      = $Gateway->getInterface();
        $this->gateway        = $Gateway->getGateway();
        $this->serverOS       = OperatingSystem::getType();
        $this->serverDistro   = OperatingSystem::getDistro();
        $this->serverSoftware = Software::get();
    }

    /**
     * Undocumented function
     *
     * @param OperatingSystem $serverOS
     * @param Software $software
     * @return void
     */
    private function registerServer(): void
    {

    }

    /**
     * Get server environment
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Get client gateway portal type
     * @return string
     */
    public function getGateway(): string
    {
        return $this->gateway();
    }

    /**
     * Get client gateway interface type
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface();
    }

    /**
     * Get server operating system type.
     * @return string
     */
    public function server_os_type(): string
    {
        return $this->serverOS;
    }

    /**
     * Get server operating system distro.
     * @return string
     */
    public function get_server_os_distro(): string
    {
        return $this->serverDistro;
    }

    /**
     * Get server software name.
     * @return string
     */
    public function get_server_software(): string
    {
        return $this->serverSoftware;
    }







    /**
     * Undocumented function
     *
     * @return array
     */
    public function getSystemData(): array
    {
        return [
            'server' => [
                'os_type'     => $this->serverOS,
                'os_distro'   => $this->serverDistro,
                'software'    => $this->serverSoftware,
                'php_version' => PHP_VERSION
            ],
            'environment'  => $this->environment,
            'interface'    => $this->interface,
            'app_name'     => ((defined('APP_NAME'))     ? APP_NAME     : 'Unknown'),
            'app_version'  => ((defined('APP_VERSION'))  ? APP_VERSION  : 'Unknown'),
            'framework'    => ((defined('ESS_PROJECT'))  ? ESS_PROJECT  : 'Unknown'),
            'core'         => ((defined('CORE_PROJECT')) ? CORE_PROJECT : 'Unknown'),
        ];
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function GetSystemHash(): string
    {
        $system = $this->getSystemData();
        return hash('sha256', json_encode($this->getSystemData()));
    }
}