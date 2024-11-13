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

use \substr;
use \bin2hex;
use \random_int;
use \random_bytes;

/**
 * Random generator class
 * 
 * Provides random strings, numbers, and tokens.
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
class Random
{
    /**
     * Generate random bytes
     * 
     * Generates a cryptographically secure random token string.
     *
     * @static
     * @param  integer $length the amount of characters to generate.
     * @return string  A random cryptographic token.
     */
    public static function bytes(int $length=36) : string
    {
        $length = (($length) ?? 1);
        $token  = random_bytes($length);
        $token  = bin2hex($token);
        return (substr($token, 0, $length));
    }

    /**
     * Generate a random number
     * 
     * Generates a cryptographically secure random number within the 
     * range of the defined $min and $max values.
     *
     * @static
     * @param  integer $min The lowest value to be returned.
     * @param  integer $max The highest value to be returned.
     * @return integer A uniformly selected integer from the min and  
     *                 $max values. Both the $min and $max values are 
     *                 also possible return values.
     */
    public static function number(int $min=0, int $max=9999):int
    {
        $max = ($max > $min) ? $max : ($min + 1);
        return random_int($min, $max);
    }

    /**
     * Generate a random token
     * 
     * Alias of Random::bytes
     *
     * @static
     * @param  integer $length the amount of characters to generate
     * @return string Returns a random cryptographic token.
     */
    public static function token(int $length=36) : string
    {
        return self::bytes($length);
    }
}