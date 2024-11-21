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

use \hash;
use \defined;
use \in_array;
use \php_uname;
use \json_encode;
use \PHP_VERSION;
use \zend_version;
use \version_compare;
use \php_ini_loaded_file;
use \get_loaded_extensions;

/**
 * Server
 *
 * Provides system-level server information and generates a unique 
 * fingerprint based on server configuration.
 *
 * @package    Essence\System\Server
 * @category   Server
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Server
{
    /**
     * List of loaded PHP extensions.
     *
     * @var array
     */
    protected array $extensions = [];

    /**
     * Unique hash representing the server fingerprint.
     *
     * @var string
     */
    protected string $hash = '';

    /**
     * Initializes server fingerprint by setting loaded extensions and
     * generating a unique hash.
     *
     * @param string $operatingSystem Optional. Operating system name.
     * @param string $software        Optional. Server software name.
     */
    public function __construct(string $operatingSystem = 'unknown', string $software = 'unknown')
    {
        $this->extensions = $this->getSortedExtensions();
        $this->generateHash($operatingSystem, $software);
    }

    /**
     * Checks if the current PHP version meets the minimum requirement.
     *
     * @param string $version Minimum required PHP version.
     * @return bool True if the current PHP version meets the requirement, false otherwise.
     */
    public function phpMinVersion(string $version): bool
    {
        return version_compare(PHP_VERSION, $version, '>=');
    }

    /**
     * Checks if a specific PHP extension is loaded.
     *
     * @param string $extension The name of the extension to check.
     * @return bool True if the extension is loaded, false otherwise.
     */
    public function hasExtension(string $extension): bool
    {
        return in_array($extension, $this->extensions, true);
    }

    /**
     * Retrieves an alphabetically sorted list of loaded PHP extensions.
     *
     * @return array Sorted list of loaded extensions.
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * Generates a unique hashed server fingerprint.
     *
     * @param string $operatingSystem The server operating system.
     * @param string $serverSoftware  The server software name.
     * @return void
     */
    protected function generateHash(string $operatingSystem, string $serverSoftware): void
    {
        $serverDetails = [
            'os'              => $operatingSystem,
            'server_software' => $serverSoftware,
            'php_version'     => PHP_VERSION,
            'ess_version'     => defined('ESS_VERSION') ? ESS_VERSION : '0',
            'zend_version'    => zend_version(),
            'php_ini_path'    => php_ini_loaded_file() ?: 'none',
            'loaded_extensions' => $this->extensions,
            'document_root'   => $_SERVER['DOCUMENT_ROOT'] ?? '',
            'architecture'    => php_uname('m')
        ];

        // Generate SHA-256 hash of JSON-encoded server details
        $this->hash = hash('sha256', json_encode($serverDetails));
    }

    /**
     * Retrieves the server fingerprint hash.
     *
     * @return string The generated hash representing the server.
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Verifies a provided hash against the current server hash.
     *
     * @param string $hash Hash to verify.
     * @return bool True if the provided hash matches the current server hash.
     */
    public function verifyHash(string $hash): bool
    {
        return hash_equals($this->hash, $hash);
    }

    /**
     * Clears the loaded extensions and hash properties.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->extensions = [];
        $this->hash = '';
    }

    /**
     * Destructor clears loaded data.
     */
    public function __destruct()
    {
        $this->clear();
    }

    /**
     * Retrieves a sorted list of loaded PHP extensions.
     *
     * @return array Alphabetically sorted loaded extensions.
     */
    private function getSortedExtensions(): array
    {
        $extensions = get_loaded_extensions();
        sort($extensions);
        return $extensions;
    }
}