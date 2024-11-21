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

namespace Essence\System\Config\Parsers;

use \is_file;
use \is_array;
use \is_readable;
use \Essence\System\Config\Exception\ConfigReadException;
use \Essence\System\Config\Parsers\ConfigParserInterface;
/**
 * PhpConfigParser
 *
 * Parses configuration data from PHP files that return an array.
 * This parser expects the PHP file to contain an array of configuration
 * settings and returns it as an associative array.
 *
 * @package    Essence\System\Config\Parsers
 * @category   Configuration
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @implements ConfigParserInterface
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
     * @throws ConfigReadException If the file is not readable or does not return an array.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigReadException([
                'message' => "Failed to parse PHP file at: $filePath",
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        $data = require $filePath;

        if (!is_array($data)) {
            throw new ConfigReadException([
                'message' => "PHP configuration file must return an array. Error in file: $filePath",
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        return $data;
    }
}