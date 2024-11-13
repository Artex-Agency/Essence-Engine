<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @version    1.0.0
 * @since      1.0.0
 * @package    Artex\Essence\Engine\System\Config\Parsers
 * @category   Configuration
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config\Parsers;

use RuntimeException;

/**
 * PhpConfigParser
 *
 * Parses configuration data from PHP files that return an array.
 * This parser expects the PHP file to contain an array of configuration
 * settings and returns it as an associative array.
 *
 * @package    Artex\Essence\Engine\System\Config\Parsers
 * @category   Configuration
 * @access     public
 */
class PhpConfigParser implements ConfigParserInterface
{
    /**
     * Loads and parses configuration data from a PHP file.
     *
     * The file should return an array representing the configuration.
     * If the file does not return an array, an empty array is returned.
     *
     * @param string $filePath Path to the PHP configuration file.
     * @return array Parsed configuration data as an associative array.
     * @throws RuntimeException If the file is not readable or does not return an array.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new RuntimeException("Cannot read PHP configuration file at: $filePath");
        }

        $data = require $filePath;

        if (!is_array($data)) {
            throw new RuntimeException("PHP configuration file must return an array. Error in file: $filePath");
        }

        return $data;
    }
}