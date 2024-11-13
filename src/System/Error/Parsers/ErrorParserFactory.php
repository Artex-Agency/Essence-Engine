<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Error\Parsers;

use InvalidArgumentException;

/**
 * Error Parser Factory
 *
 * Factory for creating error parsers based on the desired output format.
 *
 * @package    Artex\Essence\Engine\System\Error\Parsers
 * @category   Error Management
 * @version    1.0.1
 * @since      1.0.0
 * @access     public
 */
class ErrorParserFactory
{
    /**
     * Creates an error parser based on the specified format.
     *
     * @param string $format The output format (e.g., 'html', 'cli', 'json').
     * @return ErrorParserInterface The appropriate error parser instance.
     *
     * @throws InvalidArgumentException If the format is unsupported.
     */
    public static function createParser(string $format): ErrorParserInterface
    {
        return match ($format) {
            'html' => new HtmlErrorParser(),
            'cli'  => new CliErrorParser(),
            'json' => new JsonErrorParser(),
            default => throw new InvalidArgumentException("Unsupported format: $format"),
        };
    }
}