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

use \trim;
use \match;
use \is_file;
use \PHP_OS_FAMILY;
use \parse_ini_file;
use \INI_SCANNER_RAW;

/**
 * OS 
 * 
 * Detects and stores the server operating system name and type.
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
class OS
{
    /** @var string OS_WINDOWS Clean OS type container. */
    public const OS_WINDOWS = 'Windows';

    /** @var string OS_LINUX Clean OS type container. */
    public const OS_LINUX   = 'Linux';

    /** @var string OS_MAC Clean OS type container. */
    public const OS_MAC     = 'macOS';

    /** @var string OS_BSD Clean OS type container. */
    public const OS_BSD     = 'BSD';

    /** @var string OS_UNIX Clean OS type container. */
    public const OS_UNIX    = 'Unix';

    /** @var string OS_UNKNOWN Clean lanel for unknown OS types. */
    public const OS_UNKNOWN = 'Unknown';

    /** @var string $type The server OS type. */
    protected string $type = self::OS_UNKNOWN;

    /** @var string $distro The server OS distro. */
    protected string $distro = '';

    /**
     * Server OS
     */
    public function __construct()
    {
        $this->detect();
    }

    /**
     * Gets the current OS type.
     *
     * @return string The OS type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the OS Distro.
     *
     * @return string The OS Distro.
     */
    public function getDistro(): string
    {
        return $this->distro;
    }


    /**
     * Checks if Windows OS.
     *
     * @return bool True if server is windows; false otherwise.
     */
    public function isWindows(): bool
    {
        return (self::OS_WINDOWS === $this->type);
    }

    /**
     *  Checks if Mac OS.
     * 
     * @return bool True if the OS is Mac, false otherwise.
     */
    public function isMac(): bool
    {
        return (self::OS_MAC === $this->type);
    }

    /**
     * Checks if Linux OS.
     * 
     * @return bool True if the OS is Linux, false otherwise.
     */
    public function isLinux(): bool
    {
        return (self::OS_LINUX === $this->type);
    }

    /**
     * Checks if BSD OS.
     * 
     * @return bool True if the OS is BSD, false otherwise.
     */
    public function isBSD(): bool
    {
        return (self::OS_BSD === $this->type);
    }

    /**
     * Checks if Unix OS.
     * 
     * @return bool True if the OS is BSD, false otherwise.
     */
    public function isUnix(): bool
    {
        return (self::OS_UNIX === $this->type);
    }

    /**
      * Detect the operating system and return a string representing the OS.
      * 
     */
    protected function detect(): void
    {
        // Match Type
       $this->type = match (PHP_OS_FAMILY) {
            'Windows'   => self::OS_WINDOWS,
            'Darwin'    => self::OS_MAC,
            'Linux'     => self::OS_LINUX,
            'BSD'       => self::OS_BSD,
            'Solaris'   => self::OS_UNIX,
            'SunOS'     => self::OS_UNIX,
            'Unknown'   => self::OS_UNKNOWN,
            default     => self::OS_UNKNOWN,
        };

        // Set Distro
        $this->distro = (
            (self::OS_LINUX === $this->type)
            ? $this->getLinuxDistro() 
            : $this->type
        );

        // Set Unix
        if(self::OS_UNIX === $this->type){
            $this->distro = PHP_OS_FAMILY;
        }
    }

    /**
     * Detect specific Linux distribution.
     *
     * @return string The Linux distribution or 'Linux'.
     */
    protected function getLinuxDistro(): string
    {
        if (is_file('/etc/os-release')) {
            $osRelease = parse_ini_file('/etc/os-release', false, INI_SCANNER_RAW);

            if (isset($osRelease['NAME'])) {
                return trim($osRelease['NAME']);
            }
        }

        if (is_file('/etc/lsb-release')) {
            $lsbRelease = parse_ini_file('/etc/lsb-release', false, INI_SCANNER_RAW);

            if (isset($lsbRelease['DISTRIB_ID'])) {
                return trim($lsbRelease['DISTRIB_ID']);
            }
        }

        // Fallback to generic Linux
        return self::OS_LINUX;
    }
}