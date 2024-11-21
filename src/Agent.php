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

use \trim;
use \array_map;
use \preg_match;
use \strtolower;

/**
 * Agent
 * 
 * Detects browser, platform, device, and version from the user-agent string.
 * Optimized for efficiency and compatibility with modern and legacy browsers and devices.
 *
 * @package    Essence\System
 * @category   Detection
 * @version    2.1.0
 * @access     public
 * @since      1.0.0
 */
class Agent
{
    /**
     * User Agent string.
     * 
     * @var string
     */
    private string $userAgent;

    /**
     * Device details.
     * 
     * @var string
     */
    private string $device = 'Desktop';

    /**
     * Platform details.
     * 
     * @var string
     */
    private string $platform;

    /**
     * Manufacturer details.
     * 
     * @var string
     */
    private string $manufacturer;

    /**
     * Browser details.
     * 
     * @var string
     */
    private string $browser;

    /**
     * Browser version details.
     * 
     * @var string
     */
    private string $version;

    /**
     * Extended browser patterns for matching.
     * @var array
     */
    private static array $browserPatterns = [
        'Trident\/7.0' => 'Internet Explorer 11',
        'OPR'          => 'Opera',
        'Edge'         => 'Edge',
        'Chrome'       => 'Google Chrome',
        'Safari'       => 'Safari',
        'Firefox'      => 'Firefox',
        'MSIE'         => 'Internet Explorer',
        'SeaMonkey'    => 'SeaMonkey',
        'Midori'       => 'Midori',
        'Maxthon'      => 'Maxthon',
        'Netscape'     => 'Netscape',
        'Konqueror'    => 'Konqueror',
        'Silk'         => 'Silk',
        'Opera Mini'   => 'Opera Mini',
        'Mozilla'      => 'Mozilla'
    ];

    /**
     * Extended platform patterns for matching.
     * @var array
     */
    private static array $platformPatterns = [
        'windows'  => 'Windows',
        'mac'      => 'Macintosh',
        'android'  => 'Android',
        'linux'    => 'Linux',
        'iphone'   => 'iPhone',
        'ipad'     => 'iPad',
        'blackberry' => 'BlackBerry',
        'cros'     => 'Chrome OS',
        'unix'     => 'Unix',
        'freebsd'  => 'FreeBSD',
        'netbsd'   => 'NetBSD',
        'openbsd'  => 'OpenBSD',
        'symbian'  => 'Symbian'
    ];

    /**
     * Extended manufacturers for platform detection.
     * @var array
     */
    private static array $manufacturerList = [
        'Windows'   => 'Microsoft',
        'Macintosh' => 'Apple',
        'Android'   => 'Google',
        'iPhone'    => 'Apple',
        'iPad'      => 'Apple',
        'BlackBerry' => 'RIM',
        'Linux'     => 'Open Source'
    ];

    /**
     * Initializes user agent properties.
     */
    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $this->parseAgent();
    }

    /**
     * Parse the user agent string to identify the browser, platform, and device.
     */
    private function parseAgent(): void
    {
        $this->detectBrowser();
        $this->detectPlatform();
        $this->detectDevice();
    }

    /**
     * Detects the browser from the user-agent string.
     */
    private function detectBrowser(): void
    {
        foreach (self::$browserPatterns as $pattern => $name) {
            if (preg_match("/{$pattern}/i", $this->userAgent)) {
                $this->browser = $name;
                $this->version = $this->detectVersion($pattern);
                return;
            }
        }
        $this->browser = 'Unknown';
        $this->version = 'Unknown';
    }

    /**
     * Detects the browser version from the user-agent string.
     * 
     * @param string $pattern The pattern of the detected browser.
     * @return string The version of the browser.
     */
    private function detectVersion(string $pattern): string
    {
        if (preg_match("/{$pattern}[\/\s]?([0-9\.]+)/i", $this->userAgent, $matches)) {
            return $matches[1];
        }
        return 'Unknown';
    }

    /**
     * Detects the platform (OS) from the user-agent string.
     */
    private function detectPlatform(): void
    {
        foreach (self::$platformPatterns as $pattern => $name) {
            if (preg_match("/{$pattern}/i", $this->userAgent)) {
                $this->platform = $name;
                $this->manufacturer = self::$manufacturerList[$name] ?? 'Unknown';
                return;
            }
        }
        $this->platform = 'Unknown';
        $this->manufacturer = 'Unknown';
    }

    /**
     * Detects the device type (mobile, tablet, or desktop).
     */
    private function detectDevice(): void
    {
        if (preg_match('/Mobile|Android|iPhone/i', $this->userAgent)) {
            $this->device = 'Mobile';
        } elseif (preg_match('/iPad|Tablet/i', $this->userAgent)) {
            $this->device = 'Tablet';
        } else {
            $this->device = 'Desktop';
        }
    }

    /**
     * Returns browser information.
     * 
     * @return array
     */
    public function getBrowserInfo(): array
    {
        return [
            'browser' => $this->browser,
            'version' => $this->version
        ];
    }

    /**
     * Returns platform information.
     * 
     * @return array
     */
    public function getPlatformInfo(): array
    {
        return [
            'platform' => $this->platform,
            'manufacturer' => $this->manufacturer
        ];
    }

    /**
     * Returns device information.
     * 
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * Returns all agent data.
     * 
     * @return array
     */
    public function getAgentData(): array
    {
        return [
            'user_agent' => $this->userAgent,
            'browser' => $this->browser,
            'version' => $this->version,
            'platform' => $this->platform,
            'manufacturer' => $this->manufacturer,
            'device' => $this->device
        ];
    }
}