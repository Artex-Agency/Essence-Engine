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

namespace Essence\System\Server;

use \strpos;
use \strtolower;

/**
 * Server Software
 *
 * Provides detection and information about the server software
 * type based on common identifiers in server environment variables.
 *
 * @package    Essence\System\Server
 * @category   Server
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Software
{
    /** @var string UNKNOWN Label for unknown server software. */
    public const UNKNOWN = 'Unknown';

    /** @var array<string, string> List of common server software identifiers. */
    private static array $servers = [
        'apache'    => 'Apache',
        'nginx'     => 'Nginx',
        'iis'       => 'IIS',
        'litespeed' => 'LiteSpeed',
        'caddy'     => 'Caddy',
    ];

    /** @var string|null Cached server software type. */
    private static ?string $software = null;

    /**
     * Detects and returns the server software type.
     *
     * @return string The detected server software type.
     */
    public static function get(): string
    {
        if (self::$software === null) {
            $serverSoftware = strtolower($_SERVER['SERVER_SOFTWARE'] ?? '');
            foreach (self::$servers as $key => $name) {
                if (strpos($serverSoftware, $key) !== false) {
                    self::$software = $name;
                    break;
                }
            }
            self::$software = self::$software ?? self::UNKNOWN;
        }
        return self::$software;
    }
}