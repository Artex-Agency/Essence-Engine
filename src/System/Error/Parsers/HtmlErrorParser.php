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
 * @category  Error Management
 * @access    public
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Error\Parsers;

use Throwable;

/**
 * HTML Error Parser
 *
 * Parses error information into HTML format for web display.
 *
 * @package    Artex\Essence\Engine\System\Error\Parsers
 * @category   Error Management
 * @version    1.0.1
 * @since      1.0.0
 * @access     public
 */
class HtmlErrorParser implements ErrorParserInterface
{
    /**
     * Parses an error or exception and formats it as HTML.
     *
     * @param Throwable $exception The error or exception instance.
     * @return string The parsed error message formatted in HTML.
     */
    public function parse(Throwable $exception): string
    {
        $html = '<div style="background:#fdd;padding:10px;border:1px solid #c00;margin:10px;">';
        $html .= "<h2>Error: " . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . "</h2>";
        $html .= "<p>File: " . htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8') . "</p>";
        $html .= "<p>Line: " . $exception->getLine() . "</p>";
        $html .= "<pre>" . htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre>";
        $html .= "</div>";
        return $html;
    }
}