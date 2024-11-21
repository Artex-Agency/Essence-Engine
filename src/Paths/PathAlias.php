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

namespace Essence\Paths;

use RuntimeException;

/**
 * PathAlias Class
 *
 * Manages and interpolates path aliases with customizable escape sequences.
 *
 * @package    Essence\Paths
 * @category   Paths
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 */
class PathAlias
{
    /**
     * Alias escape characters
     *
     * ```php
     * # Use alias escape characters to separate an alias from content.
     * 
     * $text = '@AliasSomeContent'; // sometimes won't parse.
     * 
     * $text = '!@{@Alias}@!SomeContent'; // Separates @Alias
     * 
     * $text = '@Alias}@!SomeContent'; // Also works.
     * 
     * $text = '@Alias\@!SomeContent'; // Another example.
     * 
     * # Note: The parser removes the escape characters automatically.
     * 
     * ```
     * @var array<string>
     */
    protected array $aliasEscapes = ['[!@]', '[@!]', '{!@', '@!}', '_@!', '!@_', '!@{', '}@!', '\@!', '!@!'];


    /**
     * Predefined path aliases
     *
     * @var array<string, string>
     */
    protected array $pathAliases = [
        '@root'    => ROOT_PATH,
        '@app'     => APP_PATH,
        '@core'    => CORE_PATH,
        '@ess'     => ESS_PATH,
        '@compile' => COMPILED_PATH,
        '@config'  => CONFIG_PATH,
        '@cache'   => CACHE_PATH,
        '@logs'    => LOGS_PATH,
        '@temp'    => TEMP_PATH,
        '@uploads' => UPLOADS_PATH,
        '@dat'     => DAT_PATH,
        '@lang'    => LANG_PATH,
        '@locale'  => LOCALES_PATH,
        '@display' => DISPLAY_PATH,
    ];

    /**
     * Initializes PathAlias with custom aliases and escape characters.
     *
     * @param array<string, string> $aliases Custom aliases.
     * @param array<string> $escapes Custom escape patterns.
     */
    public function __construct(array $aliases = [], array $escapes = [])
    {
        $this->pathAliases = array_merge($this->pathAliases, $aliases);
        $this->aliasEscapes = array_merge($this->aliasEscapes, $escapes);
    }

    /**
     * Parses and replaces aliases within a string.
     *
     * @param string $input The text containing alias placeholders.
     * @return string The interpolated string with aliases replaced.
     */
    public function parseAlias(string $input): string
    {
        $output = preg_replace_callback(
            '/@(\w+)(?=\s|[^a-zA-Z0-9_]|$)/',
            function ($matches) {
                return $this->pathAliases['@' . $matches[1]] ?? $matches[0];
            },
            $input
        );

        return str_replace($this->aliasEscapes, '', $output ?? '');
    }

    /**
     * Adds a new alias if the path is valid.
     *
     * @param string $alias The alias identifier.
     * @param string $path The path associated with the alias.
     * @return bool True if alias is added successfully, false otherwise.
     */
    public function set(string $alias, string $path): bool
    {
        $alias = $this->normalizeAlias($alias);
        if (!is_dir($path)) {
            return false;
        }
        $this->pathAliases[$alias] = $path;
        return true;
    }

    /**
     * Retrieves a path by its alias.
     *
     * @param string $alias The alias identifier.
     * @return string|null The path or null if not found.
     */
    public function get(string $alias): ?string
    {
        $alias = $this->normalizeAlias($alias);
        return $this->pathAliases[$alias] ?? null;
    }

    /**
     * Checks if an alias exists.
     *
     * @param string $alias The alias identifier.
     * @return bool True if the alias exists, false otherwise.
     */
    public function has(string $alias): bool
    {
        $alias = $this->normalizeAlias($alias);
        return array_key_exists($alias, $this->pathAliases);
    }

    /**
     * Removes an alias.
     *
     * @param string $alias The alias identifier.
     * @return void
     */
    public function remove(string $alias): void
    {
        $alias = $this->normalizeAlias($alias);
        unset($this->pathAliases[$alias]);
    }

    /**
     * Retrieves all aliases.
     *
     * @return array<string, string> An array of aliases and their paths.
     */
    public function getAll(): array
    {
        return $this->pathAliases;
    }

    /**
     * Normalizes alias by ensuring it starts with '@' and contains only alphanumeric and underscores.
     *
     * @param string $alias The alias identifier.
     * @return string The normalized alias.
     */
    protected function normalizeAlias(string $alias): string
    {
        $alias = preg_replace('/[^a-zA-Z0-9_]/', '', $alias);
        return '@' . strtolower($alias);
    }

    /**
     * Clears all defined aliases.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->pathAliases = [];
    }
}