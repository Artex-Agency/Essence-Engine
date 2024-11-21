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

use \trim;
use \preg_match_all;
use \PREG_SET_ORDER;
use \file_get_contents;
use \Essence\System\Config\Exception\ConfigReadException;
use \Essence\System\Config\Parsers\ConfigParserInterface;

/**
 * CONF Config Parser
 *
 * Parses configuration data from custom CONF files.
 *
 * This parser reads key-value pairs from custom `.conf` files where each line represents
 * a configuration setting. It handles nested keys and allows flexible key-value separators.
 *
 * @package    Essence\System\Config\Parsers
 * @category   Configuration
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @implements ConfigParserInterface
 */
class ConfConfigParser implements ConfigParserInterface
{
    /**
     * Parses a custom CONF configuration file.
     *
     * This method reads key-value pairs from a CONF file, storing each pair in an associative array.
     * Supports basic key-value syntax with flexible separators (`:` or `=`).
     *
     * @param string $filePath Path to the CONF file.
     * @return array Parsed configuration data as an associative array.
     * @throws ConfigReadException If the file cannot be read or parsed.
     */
    public function parse(string $filePath): array
    {
        // Attempt to retrieve the file contents
        $data = file_get_contents($filePath);
        if ($data === false) {
            throw new ConfigReadException([
                'message' => "Failed to read configuration file at path: {$filePath}",
                'file'    => $filePath,
                'line'    => __LINE__,
            ]);
        }

        $out = [];

        // Define regex for capturing key-value pairs with `:` or `=` as separator
        $regex = '/^(?P<key>[a-zA-Z._\-]+[a-zA-Z0-9]*)\s*[:=]\s*(?P<value>.*)$/m';

        // Process matches using the defined regex pattern
        if (preg_match_all($regex, $data, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $key = trim($match['key']);
                $value = trim($match['value']);
                
                if ($key !== '' && $value !== '') {
                    $out[$key] = $value;
                }
            }
        }

        return $out;
    }
}