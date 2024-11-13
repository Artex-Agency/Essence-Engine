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
use SimpleXMLElement;
use Artex\Essence\Engine\System\Config\Exception\ConfigReadException;

/**
 * XML Config Parser
 *
 * Parses XML configuration files into associative arrays.
 * 
 * @package    Artex\Essence\Engine\System\Config\Parsers
 * @category   Configuration
 * @access     public
 */
class XmlConfigParser implements ConfigParserInterface
{
    /**
     * Parses an XML file and converts it to an associative array.
     *
     * @param string $filename Path to the XML configuration file.
     * @return array The parsed configuration data as an associative array.
     * @throws ConfigReadException If the XML file cannot be read or parsed.
     */
    public function parse(string $filename): array
    {
        libxml_use_internal_errors(true);
        
        $data = simplexml_load_file($filename, SimpleXMLElement::class, LIBXML_NOERROR);

        return $this->parseXML($data, $filename);
    }

    /**
     * Parses an XML string and converts it to an associative array.
     *
     * @param string $config XML configuration string.
     * @return array The parsed configuration data as an associative array.
     * @throws ConfigReadException If there is an error parsing the XML string.
     */
    public function parseString(string $config): array
    {
        libxml_use_internal_errors(true);

        $data = simplexml_load_string($config, SimpleXMLElement::class, LIBXML_NOERROR);

        return $this->parseXML($data);
    }

    /**
     * Completes the parsing of XML data, converting it to an array.
     *
     * @param SimpleXMLElement|false|null $data XML data to parse.
     * @param string|null $filename Optional filename for error context.
     * @return array The parsed configuration data as an associative array.
     * @throws ConfigReadException If there is an error in the XML data.
     */
    protected function parseXML(SimpleXMLElement|false|null $data, ?string $filename = null): array
    {
        if ($data === false) {
            $errors = libxml_get_errors();
            $latestError = end($errors);

            $errorDetails = [
                'message' => $latestError->message ?? 'Unknown XML parsing error',
                'type'    => $latestError->level ?? 1,
                'code'    => $latestError->code ?? 0,
                'file'    => $filename,
                'line'    => $latestError->line ?? 0,
            ];

            throw new ConfigReadException($errorDetails);
        }

        $data = json_decode(json_encode($data), true);

        return $data ?? [];
    }

    /**
     * Returns the file extensions supported by this parser.
     *
     * @return array Supported file extensions (e.g., 'xml').
     */
    public static function getSupportedExtensions(): array
    {
        return ['xml'];
    }
}