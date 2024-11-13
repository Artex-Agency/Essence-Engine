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

use Throwable;

/**
 * Error Parser Interface
 *
 * Defines a contract for parsing error information into various output formats.
 *
 * @package    Artex\Essence\Engine\System\Error\Parsers
 * @category   Error Management
 * @version    1.0.1
 * @since      1.0.0
 * @access     public
 */
interface ErrorParserInterface
{
    /**
     * Parses an error or exception into the desired format.
     *
     * @param Throwable $exception The error or exception instance.
     * @return string The parsed error message in the specified format.
     */
    public function parse(Throwable $exception): string;
}