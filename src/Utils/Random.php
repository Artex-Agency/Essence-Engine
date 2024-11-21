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
 */
declare(strict_types=1);

namespace Essence\Utils;

use \substr;
use \bin2hex;
use \random_int;
use \random_bytes;

/**
 * Random generator class
 * 
 * Provides random strings, numbers, and tokens.
 *
 * @package    Essence\Utils
 * @category   Utility
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
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