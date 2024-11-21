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

use \trim;
use \is_file;
use \PHP_OS_FAMILY;
use \parse_ini_file;
use \INI_SCANNER_RAW;

/**
 * OperatingSystem
 *
 * Provides detection and information about the server operating system
 * and, if applicable, specific Linux distributions.
 *
 * @package    Essence\System\Server
 * @category   Server
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class OperatingSystem
{
    /** @var string OS_WINDOWS OS type label for Windows. */
    public const OS_WINDOWS = 'Windows';

    /** @var string OS_LINUX OS type label for Linux. */
    public const OS_LINUX = 'Linux';

    /** @var string OS_MAC OS type label for macOS. */
    public const OS_MAC = 'macOS';

    /** @var string OS_BSD OS type label for BSD. */
    public const OS_BSD = 'BSD';

    /** @var string OS_UNIX OS type label for Unix. */
    public const OS_UNIX = 'Unix';

    /** @var string OS_UNKNOWN Label for unknown OS types. */
    public const OS_UNKNOWN = 'Unknown';

    /** @var string|null Cached operating system type. */
    private static ?string $os = null;

    /** @var string|null Cached operating system distribution. */
    private static ?string $distro = null;

    /**
     * Returns the detected operating system type.
     *
     * @return string OS type.
     */
    public static function getType(): string
    {
        if (self::$os === null) {
            self::$os = match (PHP_OS_FAMILY) {
                'Windows' => self::OS_WINDOWS,
                'Darwin' => self::OS_MAC,
                'Linux' => self::OS_LINUX,
                'BSD' => self::OS_BSD,
                'Solaris', 'SunOS' => self::OS_UNIX,
                default => self::OS_UNKNOWN,
            };
        }
        return self::$os;
    }

    /**
     * Returns the detected Linux distribution or OS type if not Linux.
     *
     * @return string OS distribution or type.
     */
    public static function getDistro(): string
    {
        if (self::$distro === null && self::getType() === self::OS_LINUX) {
            self::$distro = self::detectLinuxDistro();
        }
        return self::$distro ?? self::getType();
    }

    /**
     * Detects specific Linux distribution from system files.
     *
     * @return string The Linux distribution name or 'Linux' if unknown.
     */
    private static function detectLinuxDistro(): string
    {
        if (is_file('/etc/os-release')) {
            $osRelease = parse_ini_file('/etc/os-release', false, INI_SCANNER_RAW);
            return trim($osRelease['NAME'] ?? 'Linux');
        }
        if (is_file('/etc/lsb-release')) {
            $lsbRelease = parse_ini_file('/etc/lsb-release', false, INI_SCANNER_RAW);
            return trim($lsbRelease['DISTRIB_ID'] ?? 'Linux');
        }
        return 'Linux';
    }

    /**
     * Checks if the server OS is Windows.
     *
     * @return bool True if the OS is Windows; false otherwise.
     */
    public static function isWindows(): bool
    {
        return self::$os === self::OS_WINDOWS;
    }

    /**
     * Checks if the server OS is macOS.
     * 
     * @return bool True if the OS is macOS; false otherwise.
     */
    public static function isMac(): bool
    {
        return self::$os === self::OS_MAC;
    }

    /**
     * Checks if the server OS is Linux.
     * 
     * @return bool True if the OS is Linux; false otherwise.
     */
    public static function isLinux(): bool
    {
        return self::$os === self::OS_LINUX;
    }

    /**
     * Checks if the server OS is BSD.
     * 
     * @return bool True if the OS is BSD; false otherwise.
     */
    public static function isBSD(): bool
    {
        return self::$os === self::OS_BSD;
    }

    /**
     * Checks if the server OS is Unix-based.
     * 
     * @return bool True if the OS is Unix-based; false otherwise.
     */
    public static function isUnix(): bool
    {
        return self::$os === self::OS_UNIX;
    }
}