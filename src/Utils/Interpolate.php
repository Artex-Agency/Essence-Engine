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

use strtr;

/**
 * Interpolate utility class 
 * 
 * Provides functionality for interpolating values between two endpoints.
 * 
 * @package    Essence\Utils
 * @category   Utility
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Interpolate
{
    /** @var array Static tokens (key-value pairs). */
    private array $staticItems = [];

    /** @var array Dynamic tokens (key-callable pairs). */
    private array $dynamicItems = [];

    /** @var array Static tokens (key-value pairs). */
    private array $staticTokens = [];

    /** @var array Dynamic tokens (key-callable pairs). */
    private array $dynamicTokens = [];

    /** @var array Caching for previously resolved strings. */
    private array $cache = [];

    /**
     * Add a single placeholder and its value.
     *
     * @param string $key   The placeholder key (e.g., 'key' for {{key}})
     * @param mixed  $value The value to replace the placeholder with.
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        $this->staticItems[$this->normalizeKey($key)] = $value;
    }

    /**
     * Add multiple placeholders at once.
     *
     * @param array $data Associative array of key-value pairs.
     * @return void
     */
    public function addArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->add($key, $value);
        }
    }

    /**
     * Add a dynamic token.
     *
     * @param string   $key      The token name without delimiters (e.g., 'key').
     * @param callable $callable A callable that returns the token value.
     * @return void
     */
    public function addDynamicItem(string $key, callable $callable): void
    {
        $this->dynamicItems[$this->normalizeKey($key)] = $callable;
    }


    /**
     * Add a dynamic token.
     *
     * @param string   $key   Token key (e.g., 'key' for {{key}}).
     * @param callable $callable A callable returning the token value.
     * @return void
     */
    public function addDynamicToken(string $key, callable $callable): void
    {
        $this->dynamicTokens["{{$key}}"] = $callable;
    }

   /**
     * Add a static token.
     *
     * @param string $key   Token key (e.g., 'key' for {{key}}).
     * @param mixed  $value Token value.
     * @return void
     */
    public function addStaticToken(string $key, mixed $value): void
    {
        $this->staticTokens["{{$key}}"] = $value;
    }

    /**
     * Add multiple static tokens at once.
     *
     * @param array $tokens Associative array of token-value pairs.
     * @return void
     */
    public function addStaticTokens(array $tokens): void
    {
        foreach ($tokens as $key => $value) {
            $this->addStaticToken($key, $value);
        }
    }

    /**
     * Normalize a key for consistent token naming.
     *
     * @param string $key The raw key.
     * @return string The normalized key.
     */
    private function normalizeKey(string $key): string
    {
        return "{{" . strtolower(trim($key)) . "}}";
    }

    /**
     * Interpolate a string with both static and dynamic tokens.
     *
     * @param string $input The string containing tokens.
     * @return string The interpolated string.
     */
    public function translate(string $input): string
    {
        // Check if the result is already cached
        if (isset($this->cache[$input])) {
            return $this->cache[$input];
        }

        // Resolve dynamic tokens
        $resolvedDynamic = array_map(fn($callback) => $callback(), $this->dynamicItems);

        // Merge static and dynamic tokens
        $allTokens = array_merge($this->staticItems, $resolvedDynamic);

        // Perform interpolation
        $result = strtr($input, $allTokens);

        // Cache the result for future use
        $this->cache[$input] = $result;

        return $result;
    }

    /**
     * Interpolate an array with both static and dynamic tokens.
     *
     * @param array $array The array containing tokens in its values.
     * @return array The interpolated array.
     */
    public function translateArray(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = is_string($value) ? $this->translate($value) : $value;
        }
        return $array;
    }

    /**
     * Interpolate a string using the added tokens.
     *
     * @param string $template The template string.
     * @return string Interpolated string.
     */
    public function parse(string $template): string
    {
        return strtr($template, $this->getResolvedTokens());
    }

    /**
     * Interpolate an array of strings.
     *
     * @param array $templates Array of templates.
     * @return array Array with interpolated strings.
     */
    public function parseArray(array $templates): array
    {
        return array_map(fn($template) => $this->parse($template), $templates);
    }

    /**
     * Add a dynamic token for debugging.
     *
     * @return void
     */
    public function addDebugTokens(): void
    {
        $this->addDynamicItem('timestamp', fn() => date('Y-m-d H:i:s'));
        $this->addDynamicItem('memory_usage', fn() => memory_get_usage(true) . ' bytes');
    }

    /**
     * Get all currently registered tokens.
     *
     * @return array Combined static and dynamic tokens.
     */
    public function getTokens(): array
    {
        return array_merge($this->staticItems, array_map(fn($callback) => $callback(), $this->dynamicItems));
    }

    /**
     * Get all tokens (resolved dynamic + static).
     *
     * @return array Resolved tokens for interpolation.
     */
    private function getResolvedTokens(): array
    {
        $resolvedDynamicTokens = array_map(fn($callback) => $callback(), $this->dynamicTokens);
        return array_merge($this->staticTokens, $resolvedDynamicTokens);
    }

}