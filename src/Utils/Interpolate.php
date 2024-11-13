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

use strtr;

/**
 * Interpolate utility class 
 * 
 * Provides functionality for interpolating values between two endpoints.
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
class Interpolate
{
    /** @var array Associative array to store items for interpolation. */
    private array $items = [];

    /**
     * Add item
     * 
     * Adds an item for interpolation.
     *
     * @param  string $key The key of the placeholder.
     * @param  mixed  $value The value to replace the placeholder.
     */
    public function addItem(string $key, $value): void
    {
        $this->items["{{$key}}"] = $value;
    }

    /**
     * Add array
     * 
     * Add an array of items for interpolation.
     *
     * @param  array  $array The array of key => value items.
     */
    public function addArray(array $array): void
    {
        foreach($array as $key => $value) {
            $this->addItem($key, $value);
        }
    }

    /**
     * Translate input
     * 
     * Interpolate a string using the added items.
     *
     * @param  string $input The string with placeholders.
     * @return string The interpolated string.
     */
    public function translate(string $input): string
    {
        return strtr($input, $this->items);
    }

    /**
     * Translate array
     * 
     * Interpolate an array using the added items.
     *
     * @param  array $array An array with placeholders.
     * @return array The interpolated array.
     */
    public function translateArray(array $array): array
    {
        foreach($array as $key => $value) {
            $array[$key] = $this->translate($value);
        }
        return (($array) ?? []);
    }
}