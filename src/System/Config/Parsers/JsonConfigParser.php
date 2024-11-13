<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine Meta-Framework.
 *
 * @link      https://artexessence.com/core/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @version   1.0.0
 * @since     1.0.0
 * @package   Artex\Essence\Engine\System\Config\Parsers
 * @category  Configuration
 * @access    public
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config\Parsers;

use RuntimeException;
use Artex\Essence\Engine\System\Config\Parsers\ConfigParserInterface;

/**
 * JsonConfigParser
 *
 * Parses configuration data from JSON files.
 * Implements the ConfigParserInterface to convert JSON configuration
 * files into associative arrays for easy access.
 *
 * @package    Artex\Essence\Engine\System\Parsers
 * @category   Configuration
 * @access     public
 */
class JsonConfigParser implements ConfigParserInterface
{
    /**
     * Parses a JSON file and converts it to an associative array.
     *
     * @param string $filePath Path to the JSON configuration file.
     * @return array Parsed configuration data as an associative array.
     * @throws RuntimeException If the file cannot be read, or the JSON data is invalid.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new RuntimeException("Cannot read the JSON file at: $filePath");
        }

        $json = file_get_contents($filePath);
        if ($json === false) {
            throw new RuntimeException("Failed to read JSON data from file: $filePath");
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON decoding error in file $filePath: " . json_last_error_msg());
        }

        return $data ?? [];
    }
}