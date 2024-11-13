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

use \rtrim;
use \strtr;
use \base64_decode;
use \base64_encode;
/**
 * Base64 encode/decode wrapper utility class
 *
 * Provides methods for encoding and decoding web-safe Base64 strings.
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
class Base64
{
    /**
     * Encode a string to web-safe base64.
     *
     * @static
     * @param  string|null  $string The string to encode.
     * @return string|false Returns base64 encoded string, false on error.
     */
    public static function encode(?string $string = ''): string|false
    {
        $encoded = base64_encode($string);
        $encoded = (($encoded) ? strtr($encoded, '+/', '-_') : false);
        return (($encoded) ? rtrim($encoded, '=') : false);
    }

    /**
     * Decode a web-safe base64 encoded string.
     *
     * @static
     * @param string|null $string The base64 encoded string.
     * @param boolean     $strict Returns false if input contains character from outside the base64 alphabet. 
     * @param string|null $string The base64 encoded string.
     */
    public static function decode(?string $string = '', bool $strict = false): string|false
    {
        $string  = strtr($string, '-_', '+/');
        $decoded = base64_decode($string, $strict);
        return (($decoded) ? $decoded : false);
    }
}