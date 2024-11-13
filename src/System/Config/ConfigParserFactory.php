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
 * @category   Configuration
 * @package    Artex\Essence\Engine\System\Config
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @author     
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config;

use \InvalidArgumentException;
use \Artex\Essence\Engine\System\Config\Parsers\ConfigParserInterface;

/**
 * ConfigParserFactory
 *
 * A factory class responsible for instantiating appropriate 
 * configuration file parsers based on file extension. This allows 
 * loading configurations in various formats (e.g., JSON, XML, INI) 
 * without requiring manual selection of parsers.
 *
 * @package    Artex\Essence\Engine\System\Config
 */
class ConfigParserFactory
{
    /**
     * Default mapping of file extensions to parser class names.
     *
     * @var array<string, class-string<ConfigParserInterface>>
     */
    private static array $defaultConfigParsers = [
        'php'  => \Artex\Essence\Engine\System\Config\Parsers\PhpConfigParser::class,
        'conf' => \Artex\Essence\Engine\System\Config\Parsers\ConfConfigParser::class,
        'json' => \Artex\Essence\Engine\System\Config\Parsers\JsonConfigParser::class,
        'ini'  => \Artex\Essence\Engine\System\Config\Parsers\IniConfigParser::class,
        'xml'  => \Artex\Essence\Engine\System\Config\Parsers\XmlConfigParser::class,
    ];

    /**
     * Custom parsers registered via directive or code, allowing overrides or additions.
     *
     * @var array<string, class-string<ConfigParserInterface>>
     */
    private static array $customConfigParsers = [];

    /**
     * Creates a parser instance based on the file's extension.
     *
     * @param  string $filePath The path to the configuration file.
     * @return ConfigParserInterface Returns an instance of the appropriate parser.
     * @throws InvalidArgumentException If no suitable parser is found.
     */
    public static function createParser(string $filePath): ConfigParserInterface
    {
        // Extract and normalize file extension to lowercase
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Merge default and custom parsers, allowing custom to override default
        $allParsers = array_merge(self::$defaultConfigParsers, self::$customConfigParsers);

        // Attempt to retrieve the parser class for the extension
        $parserClass = $allParsers[$extension] ?? null;

        // Return an instance of the parser class, or throw if not found
        if ($parserClass && is_a($parserClass, ConfigParserInterface::class, true)) {
            return new $parserClass();
        }

        throw new InvalidArgumentException("No parser available for file extension: .$extension");
    }

    /**
     * Registers a custom parser, allowing users to extend or override default parsers.
     *
     * @param string $extension  The file extension for the parser (e.g., 'yaml').
     * @param string $parserClass Fully qualified class name of the parser, 
     *                            must implement ConfigParserInterface.
     * @return void
     * @throws InvalidArgumentException If the class does not implement the correct interface.
     */
    public static function registerParser(string $extension, string $parserClass): void
    {
        // Validate that the class implements ConfigParserInterface
        if (!is_a($parserClass, ConfigParserInterface::class, true)) {
            throw new InvalidArgumentException("Parser class must implement ConfigParserInterface.");
        }

        // Add or overwrite in custom parsers array
        self::$customConfigParsers[strtolower($extension)] = $parserClass;
    }

    /**
     * Optional method to load parsers from a configuration directive.
     *
     * @param array $parsers An array mapping file extensions to parser class names.
     * @return void
     */
    public static function loadParsersFromConfig(array $parsers): void
    {
        foreach ($parsers as $extension => $parserClass) {
            self::registerParser($extension, $parserClass);
        }
    }
}