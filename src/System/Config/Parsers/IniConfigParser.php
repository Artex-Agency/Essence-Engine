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
 * @category   Configuration
 * @package    Artex\Essence\Engine\System\Config\Parsers
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config\Parsers;

use \Artex\Essence\Engine\System\Config\Exception\ConfigReadException;

/**
 * IniConfigParser
 *
 * Parses INI configuration files into associative arrays.
 *
 * @package    Artex\Essence\Engine\System\Config\Parsers
 * @category   Configuration
 * @access     public
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