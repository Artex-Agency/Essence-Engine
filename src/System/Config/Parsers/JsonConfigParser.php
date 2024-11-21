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
use \is_readable;
use \json_decode;
use \json_last_error;
use \file_get_contents;
use \json_last_error_msg;
use \Essence\System\Config\Exception\ConfigReadException;
use \Essence\System\Config\Parsers\ConfigParserInterface;

/**
 * JsonConfigParser
 *
 * Parses configuration data from JSON files.
 * Implements the ConfigParserInterface to convert JSON configuration
 * files into associative arrays for easy access.
 *
 * @package    Essence\System\Config\Parsers
 * @category   Configuration
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @implements ConfigParserInterface
 */
class JsonConfigParser implements ConfigParserInterface
{
    /**
     * Parses a JSON file and converts it to an associative array.
     *
     * @param string $filePath Path to the JSON configuration file.
     * @return array Parsed configuration data as an associative array.
     * @throws ConfigReadException If the file cannot be read, or the JSON data is invalid.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigReadException([
                'message' => "Failed to parse JSON file at: $filePath",
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        $json = file_get_contents($filePath);
        if ($json === false) {
            throw new ConfigReadException([
                'message' => "Failed to parse JSON file at: $filePath",
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConfigReadException([
                'message' => "JSON decoding error in file $filePath: " . json_last_error_msg(),
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        return $data ?? [];
    }
}