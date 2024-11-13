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
use \hash;
use \in_arrayy;
use \hash_algos;
use \hash_equals;

/**
 * Hash wrapper class
 * 
 * Provides methods to encode and to verify hash strings.
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
class Hashing
{
    /**
     * Hash encode
     * 
     * Hash encodes a string.
     *
     * @param  string  $string The string to hash.
     * @param  string  $salt   The hash salt.
     * @param boolean  $binary Will output raw binary data on true;
     *                         Otherwise outputs lowercase hexits.
     * @param  string  $algo The hash algorithm.
     * @return string  The hashed string.
     */
    public static function encode(string $string, string $salt='', bool $binary=false, string $algo='sha256'):string
    {
        if('sha256' !== $algo){
            $algo = ((in_array($algo, hash_algos())) ? $algo : 'sha256');
        }
        $salt   = (($salt) ?? '');
        return hash($algo, ($salt . $string), $binary);
    }

    /**
     * Match hash strings
     * 
     * Compares a provided hash string against a trusted hash hash 
     * string to verify a match.
     *
     * @param  string  $trusted The trusted hash string.
     * @param  string  $input The provided hash string to match.
     * @return boolean True if hash matches; False otherwise.
     */
    public static function match(string $trusted, string $input):bool
    {
        return hash_equals($trusted, $input);
    }
}