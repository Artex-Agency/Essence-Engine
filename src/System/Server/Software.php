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

namespace Artex\Essence\Engine\System\Server;

use \strpos;
use \strtolower;

/**
 * Server Host
 *
 * Detects and provides information about the server software running 
 * the application.Common server types include Apache, Nginx, IIS, 
 * LiteSpeed, and Caddy.
 *
 * @package    Artex\Essence\Engine\System\Server
 * @category   Server
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Software
{
    /**
     * @var string The detected server type.
     */
    private string $Software;

    /**
     * Initializes the ServerHost class and determines the server type.
     */
    public function __construct()
    {
        $this->Software = $this->detectServerType();
    }
    
    /**
     * Detects the type of server software running based on common server identifiers.
     *
     * @return string The detected server type (e.g., 'Apache', 'Nginx', 'IIS', 'LiteSpeed', 'Caddy', or 'Unknown').
     */
    private function detectServerType(): string
    {
        if (!isset($_SERVER['SERVER_SOFTWARE'])) {
            return 'Unknown';
        }
        $serverSoftware = strtolower($_SERVER['SERVER_SOFTWARE']);

        // Detect Apache 
        if (strpos($serverSoftware, 'apache') !== false) {
            return 'Apache';
        }

        // Detect Nginx 
        if (strpos($serverSoftware, 'nginx') !== false) {
            return 'Nginx';
        }

        // Detect IIS 
        if (strpos($serverSoftware, 'iis') !== false) {
            return 'IIS';
        }

        // Detect LiteSpeed 
        if (strpos($serverSoftware, 'litespeed') !== false) {
            return 'LiteSpeed';
        }

        // Detect Caddy
        if (strpos($serverSoftware, 'caddy') !== false) {
            return 'Caddy';
        }

        // Unknown
        return 'Unknown';
    }

    /**
     * Returns the detected server software type.
     *
     * @return string The detected server type.
     */
    public function get(): string
    {
        return $this->Software;
    }

    /**
     * Checks if the server software is Apache.
     *
     * @return bool True if the server is Apache, false otherwise.
     */
    public function isApache(): bool
    {
        return $this->Software === 'Apache';
    }

    /**
     * Checks if the server software is Nginx.
     *
     * @return bool True if the server is Nginx, false otherwise.
     */
    public function isNginx(): bool
    {
        return $this->Software === 'Nginx';
    }

    /**
     * Checks if the server software is IIS (Internet Information Services).
     *
     * @return bool True if the server is IIS, false otherwise.
     */
    public function isIIS(): bool
    {
        return $this->Software === 'IIS';
    }

    /**
     * Checks if the server software is LiteSpeed.
     *
     * @return bool True if the server is LiteSpeed, false otherwise.
     */
    public function isLiteSpeed(): bool
    {
        return $this->Software === 'LiteSpeed';
    }

    /**
     * Checks if the server software is Caddy.
     *
     * @return bool True if the server is Caddy, false otherwise.
     */
    public function isCaddy(): bool
    {
        return $this->Software === 'Caddy';
    }
}