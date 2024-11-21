<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Utils;

use \rtrim;
use \strtr;
use \base64_decode;
use \base64_encode;
/**
 * Base64 encode/decode wrapper utility class
 *
 * Provides methods for encoding and decoding web-safe Base64 strings.
 *
 * @package    Essence\Utils
 * @category   Utility
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
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