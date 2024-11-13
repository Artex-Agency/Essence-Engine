<?php
/* ¸_____¸____¸________¸____¸_¸  ¸__¸
   |  _  |  __ \_    _|   __|  \/  /
   |     |     / |  | |   __|}    {
   |__|__|__|__\ |__| |_____|__/\__\
   ARTEX SOFTWARE :: PHP ESSENTIALS
*/
/**
 * This file is part of the PHP Essential Library by Artex Software.
 * 
 * @link      https://artexsoftware.com/ Artex Software
 * @license   ARTEX OPEN SOURCE LICENSE (AOSL). *Permissive open-source*
 * @copyright © 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Utils;

use \max;
use \min;
use \json_decode;
use \json_encode;
use \json_validate;
use \JSON_ERROR_NONE;
use \JSON_ERROR_UTF8;
use \json_last_error;
use \JSON_ERROR_DEPTH;
use \JSON_ERROR_SYNTAX;
use \JSON_PRETTY_PRINT;
use \JSON_ERROR_CTRL_CHAR;
use \JSON_ERROR_STATE_MISMATCH;

/**
 * JSON encode/decode wrapper utility class
 *
 * Provides methods for encoding, decoding, validating, and error checking JSON strings.
 *
 * @package   Ess\Utils
 * @category  Utility
 * @version   1.0.0
 * @since     1.0.0
 * @link      https://artexessence.com/library/ Project
 * @author    James Gober <james@jamesgober.com>
 * @license   ARTEX OPEN SOURCE LICENSE (AOSL). *Permissive open-source*
 * @copyright © 2024 Artex Agency Inc.
 */
class JSON
{
    /**
     * JSON Encode.
     *
     * Attempts to JSON encode a mixed value.
     * 
     * @static
     * @param  mixed   $value the value to be encoded.
     * @param integer  $flags JSON Flags {@link https://www.php.net/manual/en/json.constants.php PHP Reference}.
     * @param integer  $depth Maximum nesting depth of the structure being decoded. The value must be greater than 0, 
     *                        and less than or equal to 2147483647.
     * @return string|false JSON encoded string on success; False otherwise.
     */
    public static function encode(mixed $value=null, int $flags=0, int $depth=512) : string|false
    {
        $depth = (($depth > 0) ? $depth : 512);
		if((!$output = json_encode($value, $flags, $depth)) || (self::has_error())){
            return false;
        }
        return $output;
    }

    /**
     * JSON Pretty Encode
     * 
     * Attempts to JSON encode a mixed value with the JSON_PRETTY_PRINT flag preset.
     * 
     * @static
     * @param   mixed  $value the value to be encoded.
     * @param integer  $flags JSON Flags {@link https://www.php.net/manual/en/json.constants.php PHP Reference}.
     * @param integer  $depth Maximum nesting depth of the structure being decoded. The value must be greater than 0, 
     *                        and less than or equal to 2147483647.
     * @return string|false A pretty JSON encoded string on success; False otherwise.
     */
    public static function encodePretty(mixed $value=null, int $flags=0, int $depth=512) : string|false
    {
        if((defined('JSON_PRETTY_PRINT'))){
            $flags = (($flags) ? ($flags | JSON_PRETTY_PRINT) : JSON_PRETTY_PRINT);
        }
        return self::encode($value, $flags, $depth);
    }

    /**
     * JSON Decode
     * 
     * Attempts to decode a JSON string.
     *
     * @static
     * @param  string  $encoded The JSON encoded string.
     * @param boolean  $associative When true, JSON objects will be returned as associative arrays; 
     *                              when false, JSON objects will be returned as objects. 
     * @param integer  $depth Maximum nesting depth of the structure being decoded. The value must be greater than 0, 
     *                        and less than or equal to 2147483647.
     * @param integer  $flags $flags JSON Flags {@link https://www.php.net/manual/en/json.constants.php PHP Reference}.
     * @return  mixed  JSON decoded object on success; False otherwise.
     */
    public static function decode(string $encoded='', bool $associative=true, int $depth=512, int $flags=0) : mixed
    {
        $depth = max(1, min($depth, 2147483647));
        $output = json_decode($encoded, $associative, $depth, $flags);
    
        // Return false if an error occurs
        if (self::has_error()) {
            return false;
        }
    
        return $output;
    }

    /**
     * JSON Validate
     * 
     * Validates a JSON string.
     *
     * @static
     * @param  string  $string The JSON encoded string.
     * @param integer  $depth Maximum nesting depth of the structure being decoded. The value must be greater than 0, 
     *                        and less than or equal to 2147483647.
     * @return  mixed  True if valid; False otherwise.
     */
    public static function validate(string $string='', int $depth=512) : bool
    {
        $depth = max(1, min($depth, 2147483647));
        return json_validate($string, $depth);
    }

    /**
     * JSON Has Error
     * 
     * Checks if a JSON error exists.
     * 
     * @static
     * @return boolean True if a JSON error exists; False otherwise.
     */
    public static function has_error() : bool
    {
        return ((false !== (self::get_error())) ? true : false);
    }

    /**
     * JSON Get Error
     * 
     * Gets the last JSON error.
     * 
     * @static
     * @return string|false The last JSON error message if exists; False otherwise.
     */
    public static function get_error() : string|false
    {
        $error = false;
        switch (json_last_error()){
            case JSON_ERROR_NONE:
                $error = false;
                break;
            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $error = 'Unknown error';
                break;
            break;
        }
        return $error;
    }
}