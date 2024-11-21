<?php declare(strict_types=1);
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Content Helpers
 * 
 * A collection of utility functions for input sanitization, 
 * XSS prevention, and content formatting. These functions 
 * provide a robust and secure way to process and output user data.
 *
 * This file is part of the Artex Essence meta-framework.
 *
 * @package    Essence\Helpers
 * @category   Helpers
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */

/**
 * Comprehensive input sanitization function.
 *
 * Combines character encoding, dangerous attribute stripping, 
 * and allowed tag filtering for robust input sanitization.
 *
 * @param string $input         The input string to sanitize.
 * @param array  $allowed_tags  An optional list of allowed HTML tags.
 * 
 * @return string The fully sanitized string.
 */
function sanitize(string $input, array $allowed_tags = []): string
{
    $input = cleanChars($input);                  // Encode special characters
    $input = stripDangerousAttributes($input);   // Remove dangerous attributes
    $input = sanitizeInput($input, $allowed_tags); // Filter allowed tags
    return $input;
}

/**
 * Cleans special characters to prevent HTML injection.
 *
 * Converts special characters to HTML entities, making the input safe for output in HTML contexts.
 * 
 * - **ENT_QUOTES**: Converts both single and double quotes.
 * - **ENT_SUBSTITUTE**: Replaces invalid code unit sequences with the Unicode Replacement Character.
 * - **UTF-8 Encoding**: Ensures compatibility with all Unicode characters.
 *
 * @param string  $input         The string to sanitize.
 * @param bool    $double_encode Whether to encode already-encoded entities. Defaults to true.
 * 
 * @return string The sanitized string, safe for HTML output.
 */
function cleanChars(string $input, bool $double_encode = true): string
{
    return htmlspecialchars(
        $input, 
        ENT_QUOTES | ENT_SUBSTITUTE, 
        defined('ESS_CHARSET') ? ESS_CHARSET : 'UTF-8', 
        $double_encode
    );
}

/**
 * Sanitizes input to prevent XSS by removing dangerous tags and attributes.
 *
 * This function uses `strip_tags` and a custom whitelist of allowed tags
 * for stricter content sanitization.
 *
 * @param string $input  The input string to sanitize.
 * @param array  $allowed_tags An optional array of allowed HTML tags. Defaults to no tags.
 * 
 * @return string The sanitized string with only allowed tags.
 */
function sanitizeInput(string $input, array $allowed_tags = []): string
{
    $allowed = implode('', array_map(fn($tag) => "<$tag>", $allowed_tags));
    return strip_tags($input, $allowed);
}

/**
 * Removes potentially harmful JavaScript attributes from HTML.
 *
 * Strips attributes like `on*` (e.g., `onclick`, `onload`) and `javascript:` URLs.
 *
 * @param string $input The input string containing HTML.
 * 
 * @return string The sanitized HTML with dangerous attributes removed.
 */
function stripDangerousAttributes(string $input): string
{
    // Remove inline event handlers and JavaScript URLs
    $pattern = [
        '/\s*on\w+=".*?"/i',      // Remove `on*` attributes
        '/\s*on\w+=\'.*?\'/i',    // Remove `on*` attributes with single quotes
        '/\s*javascript:.*?"/i',  // Remove JavaScript URIs
        '/\s*javascript:.*?\'/i', // Remove JavaScript URIs with single quotes
    ];
    return preg_replace($pattern, '', $input);
}

/**
 * Normalizes line endings to a consistent format.
 *
 * Converts all line endings (CRLF, CR, LF) to a single format (LF by default).
 *
 * @param string $input  The string to normalize.
 * @param string $eol    The desired line ending. Defaults to "\n" (LF).
 * 
 * @return string The normalized string.
 */
function normalizeLineEndings(string $input, string $eol = "\n"): string
{
    return preg_replace('/\r\n|\r|\n/', $eol, $input);
}




/**
 * Clamps a value between a minimum and maximum range.
 *
 * If the value is less than the minimum, it will be set to the minimum.
 * If the value is greater than the maximum, it will be set to the maximum.
 * Otherwise, the value remains unchanged.
 *
 * @param float|int $value The value to clamp.
 * @param float|int $min   The minimum allowable value.
 * @param float|int $max   The maximum allowable value.
 * 
 * @return float|int The clamped value.
 *
 * @throws InvalidArgumentException If the minimum is greater than the maximum.
 */
function clamp(float|int $value, float|int $min, float|int $max): float|int
{
    if ($min > $max) {
        throw new InvalidArgumentException("Minimum value cannot be greater than maximum value.");
    }

    return max($min, min($value, $max));
}



/**
* Remove slashes
*
* @param  mixed $data
* @return array|string Stripped string or array
********************************************************** */
if(!function_exists('remove_slashes_deep')){        
    function remove_slashes_deep($data=''){
        return (is_array($data) ? array_map('remove_slashes_deep', $data) : stripslashes($data));
    }
}




/**
* add slashes
*
* @param  mixed $data
* @return array|string Slashed string or array
********************************************************** */
if(!function_exists('add_slashes_deep')){        
    function add_slashes_deep($data=''){
        return (is_array($data) ? array_map('add_slashes_deep', $data) : addslashes($data));
    }
}





/**
 * Checks if string is serialized
 *
 * @param  string  $data The string to check if serialized.
 * @return boolean True if the string is serialized; otherwise false.
 */
function is_serialized(string $data): bool 
{
    $data = trim($data);
    if (in_array(substr($data, 0, 2), ['a:', 's:', 'i:', 'd:', 'b:', 'O:'])) {
        try {
            $unserialized = unserialize($data);
            if ($unserialized !== false || $data === 'b:0;') {
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }
    return false;
}


// SANITIZE SETTING NAME
if(!function_exists('cleanSettingName'))
{
    /**
     * Clean/Sanitize setting name
     *
     * @param  string $name The setting name to sanitize
     * @return string Returns a sanitized setting name, or empty on error.
     */
    function cleanSettingName(string $name):string
    {
        $name = trim($name);
        $name = preg_replace('/[^a-zA-Z0-9-._]/', '', $name);
        return (($name) ?? '');
    }
}


// SANITIZE VARIABLE NAME
if(!function_exists('cleanVarName')){
    /**
     * Clean/Sanitize Variable name
     *
     * @param  string $name The variable name to sanitize
     * @return string Returns a sanitized variable name, or empty on error.
     */
    function cleanVarName(string $name):string
    {
        $name = trim($name);
        $name = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
        return (($name) ?? '');
    }
}
