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
 * @package    Artex\Essence\Engine\System\Parsers
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config\Parsers;

/**
 * ConfigParserInterface
 *
 * Defines a contract for configuration file parsers. Each implementing 
 * parser class is responsible for parsing a specific configuration 
 * file type (e.g., JSON, PHP, INI) and returning the parsed data as an 
 * associative array.
 *
 * @package    Artex\Essence\Engine\System\Config\Parsers
 * @category   Configuration
 * @access     public
 */
interface ConfigParserInterface
{
    /**
     * Parse a configuration file and return its contents as an array.
     *
     * @param string $filePath The path to the configuration file.
     * @return array An associative array representing the parsed configuration data.
     */
    public function parse(string $filePath): array;
}