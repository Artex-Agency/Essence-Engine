<?php
/**
 * This file is part of the Talent Joe Application software, and was 
 * created exclusively for {@link https://talentjoe.com TalentJoe.com}.
 * 
 * @link      https://talentjoe.dev Talent Joe Devs
 * @license   Proprietary Software. [ SEE LICENSE FILE ]
 * @copyright 2024 Talent Joe Enterprises and Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Output;

use extract;
use is_file;
use is_array;
use ob_clean;
use ob_start;
use is_string;
use ob_end_flush;
use ob_get_clean;
use ob_get_length;

/**
 * Render Class
 *
 * Handles the rendering of templates and content output for the 
 * application.Supports template inclusion, compiling, and rendering 
 * non-template content such as API or AJAX responses. The class 
 * provides flexibility in how the content is output or processed.
 *
 * @package   TalentJoe\Display
 * @version   1.0.0
 * @since     1.0.0
 * @author    James Gober <james@jamesgober.com>
 */
class Render
{
    /**
     * Constructor: Initializes output buffering for rendering.
     * 
     * Starts output buffering with an optional callback, chunk size, 
     * and flags. Cleans the buffer if anything is already in it.
     *
     * @param callable|null $callback An optional callback to modify 
     *                                the buffer content.
     * @param int           $size     Size of the chunks to flush. 
     *                                Defaults to 0 for no chunking.
     * @param int           $flags    Bitmask controlling operations 
     *                                on the output buffer.
     */
    public function __construct(callable $callback = null, int $size = 0, int $flags = PHP_OUTPUT_HANDLER_STDFLAGS)
    {
        // Clean the buffer if any previous output exists
        if (ob_get_length() > 0) {
            ob_clean();
        }

        // Start output buffering
        if (!ob_start($callback, $size, $flags)) {
            throw new \RuntimeException("Failed to start output buffering.");
        }
    }

    /**
     * Add content to the output buffer.
     *
     * Adds a string of content to the output buffer for later 
     * rendering.
     *
     * @param string $content The content to add to the buffer.
     * @return void
     */
    public function addContent(string $content): void
    {
        echo $content;
    }

    /**
     * Include template(s) with variables.
     * 
     * Includes a single template or multiple templates with variables 
     * passed for use inside the template(s).
     * 
     * @param string|array $template  A single template file path, or 
     *                                an array of template file paths.
     * @param array        $variables The variables to extract and pass 
     *                                to the template(s).
     * @return void
     */
    public function include(string|array $template, array $variables = []): void
    {
        // Extract variables for the template(s)
        extract($variables, EXTR_PREFIX_INVALID, 'v_');

        // Include a single template
        if (is_string($template) && is_file($template)) {
            include $template;
            return;
        }

        // Handle multiple templates if passed as an array
        if (is_array($template)) {
            foreach ($template as $file) {
                if (is_file($file)) {
                    include $file;
                }
            }
        }
    }

    /**
     * Compile and render the buffered output.
     *
     * Compiles the buffered content and either displays the output or 
     * returns it as a string depending on the $display parameter.
     *
     * @param bool $display If true, the compiled output is displayed. 
     *                      If false, it is returned as a string.
     * @return string|null  The compiled template content if $display 
     *                      is false, null otherwise.
     */
    public function compile(bool $display = true): ?string
    {
        // Display rendered output and end buffering
        if ($display) {
            ob_end_flush();
            return null;
        }

        // Return the rendered output and clean the buffer
        return ob_get_clean();
    }
}