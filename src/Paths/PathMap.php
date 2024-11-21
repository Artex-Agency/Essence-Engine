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

/**
 * Path Map
 *
 * Manages path placeholders, allowing users to define, retrieve, parse, 
 * and replace placeholders with actual path values within strings and arrays.
 *
 * @package    Essence
 * @category   Paths
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 */
class PathMap
{
    /**
     * Default path placeholders and their associated constants.
     *
     * @var array<string, string>
     */
    protected array $placeholders = [
        '{{ROOT_PATH}}'                => ROOT_PATH,
        '{{APP_PATH}}'                 => APP_PATH,
        '{{BIN_PATH}}'                 => BIN_PATH,
        '{{CONFIG_PATH}}'              => CONFIG_PATH,
        '{{CORE_PATH}}'                => CORE_PATH,
        '{{DISPLAY_PATH}}'             => DISPLAY_PATH,
        '{{ESS_PATH}}'                 => ESS_PATH,
        '{{ESS_ROOT}}'                 => ESS_ROOT,
        '{{RESOURCES_PATH}}'           => RESOURCES_PATH,
        '{{ROUTES_PATH}}'              => ROUTES_PATH,
        '{{STORAGE_PATH}}'             => STORAGE_PATH,
        '{{VENDOR_PATH}}'              => VENDOR_PATH,
        '{{CONFIG_EXAMPLES_PATH}}'     => CONFIG_EXAMPLES_PATH,
        '{{DIRECTIVES_PATH}}'          => DIRECTIVES_PATH,
        '{{DATABASE_PATH}}'            => DATABASE_PATH,
        '{{DATABASE_FACTORY_PATH}}'    => DATABASE_FACTORY_PATH,
        '{{DATABASE_MIGRATIONS_PATH}}' => DATABASE_MIGRATIONS_PATH,
        '{{DATABASE_SEED_PATH}}'       => DATABASE_SEED_PATH,
        '{{LANG_PATH}}'                => LANG_PATH,
        '{{LOCALES_PATH}}'             => LOCALES_PATH,
        '{{SCAFFOLDING_PATH}}'         => SCAFFOLDING_PATH,
        '{{BLUEPRINTS_PATH}}'          => BLUEPRINTS_PATH,
        '{{PRESETS_PATH}}'             => PRESETS_PATH,
        '{{STUBS_PATH}}'               => STUBS_PATH,
        '{{CACHE_PATH}}'               => CACHE_PATH,
        '{{COMPILED_PATH}}'            => COMPILED_PATH,
        '{{DAT_PATH}}'                 => DAT_PATH,
        '{{DOWNLOADS_PATH}}'           => DOWNLOADS_PATH,
        '{{LOGS_PATH}}'                => LOGS_PATH,
        '{{SESSIONS_PATH}}'            => SESSIONS_PATH,
        '{{TEMP_PATH}}'                => TEMP_PATH,
        '{{UPLOADS_PATH}}'             => UPLOADS_PATH,
        '{{TEMP_LOGS_PATH}}'           => TEMP_LOGS_PATH,
        '{{TEMP_UPLOADS_PATH}}'        => TEMP_UPLOADS_PATH,
    ];

    /**
     * Constructor
     * Initializes with default or custom placeholders.
     *
     * @param array<string, string> $placeholders Custom placeholders to merge with defaults.
     */
    public function __construct(array $placeholders = [])
    {
        $this->placeholders = array_merge($this->placeholders, $placeholders);
    }

    /**
     * Adds a new path placeholder.
     *
     * @param string $name The placeholder name.
     * @param string $path The directory path.
     * @return bool True if the placeholder was added, false if the name or path is invalid.
     */
    public function add(string $name, string $path): bool
    {
        $name = $this->normalize($name);
        if (!$name || !is_dir($path)) {
            return false;
        }
        $this->placeholders[$name] = rtrim($path, '/') . '/';
        return true;
    }

    /**
     * Checks if a placeholder exists.
     *
     * @param string $name The placeholder name.
     * @return bool True if the placeholder exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return $this->exists($this->normalize($name));
    }

    /**
     * Retrieves a path placeholder value.
     *
     * @param string $name The placeholder name.
     * @return string|null The path if found, or null if it doesn't exist.
     */
    public function get(string $name): ?string
    {
        $name = $this->normalize($name);
        return $this->placeholders[$name] ?? null;
    }

    /**
     * Removes a path placeholder.
     *
     * @param string $name The placeholder name to remove.
     * @return void
     */
    public function remove(string $name): void
    {
        unset($this->placeholders[$this->normalize($name)]);
    }

    /**
     * Replaces all placeholders in a string with their path values.
     *
     * @param string $input The string containing placeholders.
     * @return string The string with placeholders replaced by paths.
     */
    public function parse(string $input): string
    {
        return str_replace(array_keys($this->placeholders), array_values($this->placeholders), $input);
    }

    /**
     * Replaces placeholders in each string element of an array.
     *
     * @param array $input The array of strings containing placeholders.
     * @return array The array with placeholders replaced by paths.
     */
    public function parseArray(array $input): array
    {
        return array_map(
            fn($value) => is_string($value) ? $this->parse($value) : $value,
            $input
        );
    }

    /**
     * Checks if a placeholder exists.
     *
     * @param string $name The placeholder name.
     * @return bool True if the placeholder exists, false otherwise.
     */
    protected function exists(string $name): bool
    {
        return isset($this->placeholders[$name]);
    }

    /**
     * Normalizes placeholder name to uppercase and removes non-alphanumeric characters.
     *
     * @param string $name The placeholder name to normalize.
     * @return string The normalized placeholder name.
     */
    protected function normalize(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
        return $name ? '{{' . strtoupper($name) . '}}' : '';
    }

    /**
     * Retrieves all placeholders.
     *
     * @return array An array of all placeholders.
     */
    public function getAll(): array
    {
        return $this->placeholders;
    }

    /**
     * Clears all path placeholders.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->placeholders = [];
    }
}