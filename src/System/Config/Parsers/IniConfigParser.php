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

use \parse_ini_file;
use \Essence\System\Config\Exception\ConfigReadException;
use \Essence\System\Config\Parsers\ConfigParserInterface;

/**
 * IniConfigParser
 *
 * Parses INI configuration files into associative arrays.
 *
 * @package    Essence\System\Config\Parsers
 * @category   Configuration
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @implements ConfigParserInterface
 */
class IniConfigParser implements ConfigParserInterface
{
    /**
     * Parse an INI file and return its contents as an array.
     *
     * @param string $filePath The path to the INI file.
     * @return array The parsed contents of the INI file.
     * @throws ConfigReadException If the INI file cannot be parsed.
     */
    public function parse(string $filePath): array
    {
        $data = parse_ini_file($filePath, true);

        if ($data === false) {
            throw new ConfigReadException([
                'message' => "Failed to parse INI file at: $filePath",
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        return $data;
    }
}