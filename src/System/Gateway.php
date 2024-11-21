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
use \match;
use \getenv;
use \PHP_SAPI;
use \strtolower;
use \posix_isatty;
use \str_contains;
use \php_sapi_name;

/**
 * Gateway
 *
 * Determines the interface type for the running PHP process,
 * such as HTTP, CLI, daemon, or worker. This class inspects 
 * environment variables and the PHP SAPI to make an accurate 
 * determination.
 *
 * @package    Essence\System
 * @category   Environment
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Gateway
{
    /** @var array<string, string> Maps PHP SAPI names to common interface types. */
    protected array $sapis = [
        'cli'            => 'cli',      
        'phpdbg'         => 'cli',      
        'embed'          => 'embedded', 
        'fpm-fcgi'       => 'http',     
        'cgi-fcgi'       => 'http',     
        'apache2handler' => 'http',     
        'litespeed'      => 'http',     
        'srv'            => 'http',     
    ];

    /** @var string Gateway type specified during instantiation */
    protected string $gateway = 'http';

    /** @var string Detected interface type (e.g., HTTP, CLI) */
    protected string $iface = 'http';

    /**
     * Initializes the Gateway by detecting the SAPI and environment.
     *
     * @param string $gateway Initial gateway type, defaults to 'http'.
     */
    public function __construct(string $gateway = 'http')
    {
        $this->gateway = $gateway;

        // Detect the interface based on PHP's SAPI
        $sapi = php_sapi_name() ?: PHP_SAPI;
        $this->iface = $this->sapis[$sapi] ?? 'unknown';

        // For CLI SAPI, determine specific type (e.g., daemon, cron)
        if ($this->iface === 'cli') {
            $this->iface = $this->checkProcess() ?? $this->checkDeep();
        }
    }

    /**
     * Determines specific CLI interface type based on environment variables.
     *
     * @return string|null CLI interface type, or null if not detected.
     */
    protected function checkProcess(): ?string
    {
        $essProcess = getenv('ESSENCE_PROCESS') ?: '';
        return match(strtolower(trim($essProcess))) {
            'socket' => 'socket',
            'daemon' => 'daemon',
            'worker' => 'worker',
            'shell'  => 'shell',
            'cron'   => 'cron',
            'cli'    => 'cli',
            default  => null
        };
    }

    /**
     * Determines CLI type through deeper checks if `ESSENCE_PROCESS` is not set.
     *
     * @return string CLI interface type (e.g., daemon, cron, shell).
     */
    protected function checkDeep(): string
    {
        $environment = getenv('TERM') ?: '';
        $interactive = function_exists('posix_isatty') && posix_isatty(STDIN);

        return match (true) {
            (!$environment && !$interactive) => 'daemon',
            (!$environment && str_contains(getenv('_') ?? '', 'cron')) => 'cron',
            (str_contains($environment, 'xterm')) => 'shell',
            default => 'shell',
        };
    }

    /**
     * Gets the current detected interface type.
     *
     * @return string Interface type (e.g., 'http', 'cli', 'daemon').
     */
    public function getInterface(): string
    {
        return $this->iface;
    }

    /**
     * Gets the current gateway type.
     *
     * @return string Gateway type specified on initialization.
     */
    public function getGateway(): string
    {
        return $this->gateway;
    }

    /**
     * Checks if the gateway type matches the detected interface type.
     *
     * @return bool True if gateway matches the interface type, false otherwise.
     */
    public function isMatch(): bool
    {
        return ($this->gateway === $this->iface);
    }

    /**
     * Checks if the interface is HTTP.
     *
     * @return bool True if interface is 'http'.
     */
    public function isHTTP(): bool
    {
        return $this->iface === 'http';
    }

    /**
     * Checks if the interface is CLI.
     *
     * @return bool True if interface is 'cli'.
     */
    public function isCLI(): bool
    {
        return $this->iface === 'cli';
    }

    /**
     * Checks if the interface is Socket.
     *
     * @return bool True if interface is 'socket'.
     */
    public function isSocket(): bool
    {
        return $this->iface === 'socket';
    }

    /**
     * Checks if the interface is Daemon.
     *
     * @return bool True if interface is 'daemon'.
     */
    public function isDaemon(): bool
    {
        return $this->iface === 'daemon';
    }

    /**
     * Checks if the interface is Worker.
     *
     * @return bool True if interface is 'worker'.
     */
    public function isWorker(): bool
    {
        return $this->iface === 'worker';
    }

    /**
     * Checks if the interface is Shell.
     *
     * @return bool True if interface is 'shell'.
     */
    public function isShell(): bool
    {
        return $this->iface === 'shell';
    }

    /**
     * Checks if the interface is Cron.
     *
     * @return bool True if interface is 'cron'.
     */
    public function isCron(): bool
    {
        return $this->iface === 'cron';
    }

    /**
     * Magic invoke method to return the current interface.
     *
     * @return string Current interface type.
     */
    public function __invoke(): string
    {
        return $this->iface;
    }
}