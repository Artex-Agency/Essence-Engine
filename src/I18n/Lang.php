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

namespace Essence\I18n;

use \trim;
use \Locale;
use \is_file;
use \strtolower;
use \function_exists;
use \Essence\I18n\LocaleService;

/**
 * Language Handler
 *
 * Handles translations, language management, and locale settings for applications.
 *
 * @package    Essence\I18n
 * @category   Localization
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 */
class Lang extends LocaleService
{
    /**
     * Available translations
     *
     * @var array
     */
    protected array $translations = [];

    /**
     * Fallback language translations
     *
     * @var array
     */
    protected array $fallback = [];

    /**
     * language files to preload
     *
     * @var array
     */
    protected array $preload = [];

    /**
     * Translation root directory.
     *
     * @var string
     */
    protected string $translationRoot = '';

    /**
     * Translation path for the current language.
     *
     * @var string
     */
    protected string $translationPath = '';

    /**
     * Constructor.
     * 
     * @param string $translationsDir The root language directory.
     * @param string $language The language code (default 'en').
     * @param array $preload A list of files to preload.
     */
    public function __construct(string $translationsDir, string $language = 'en', array $preload = []){
        if (!$this->setLanguageRoot($translationsDir)) { 
            return;
        }
        $this->preload = $preload;
        if ($this->setLanguage($language)) {
            $this->defaultLang = $language;
        }
        $this->preloadFiles();
    }

    /**
     * Translates a key with optional replacements and a default value.
     *
     * Supports keys in the format 'file.key' to specify the file.
     * 
     * @param string $key Translation key (e.g., 'errors.some_error').
     * @param string|null $default Default value if the key is not found.
     * @param array $replacements Key-value replacements for dynamic content.
     * @return string The translated string, the default value, or the key if neither is available.
     */
    public function translate(string $key, ?string $default = null, array $replacements = []): string
    {
        // Split the key by dot notation ('file.key')
        [$file, $actualKey] = explode('.', $key, 2) + [null, $key];

        // Load the file if not already loaded
        if (!isset($this->translations[$file])) {
            $this->loadFile($file);
        }

        // Retrieve the translation or fallback to default value or key
        $translation = $this->translations[$file][$actualKey] 
                    ?? $this->fallback[$actualKey] 
                    ?? $default 
                    ?? $key;

        // Apply replacements
        foreach ($replacements as $search => $replace) {
            $translation = str_replace("{{$search}}", $replace, $translation);
        }

        return $translation;
    }

    /**
     * Check if a translation exists
     *
     * @param string $key The translation key.
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->translations[$key]) || isset($this->fallback[$key]);
    }

    /**
     * Get all available translations
     *
     * @return array
     */
    public function getTranslations(): array
    {
        return array_keys($this->translations);
    }

    /**
     * Preload translation files specified in the preload array.
     *
     * @return void
     */
    protected function preloadFiles(): void
    {
        foreach ($this->preload as $file) {
            $this->loadFile($file);
        }
    }

    /**
     * Loads a specific translation file into the cache.
     *
     * @param string|null $file The file to load, without extension (e.g., 'errors').
     * @return void
     */
    protected function loadFile(?string $file): void
    {
        if(!$file){ return; }
        $file = strtolower(trim($file));
        $filePath = ($this->translationPath . $file . '.lang.php');

        // Set default to avoid reloading missing files
        if(!is_file($filePath)){
            $this->translations[$file] = [];
            return;
        }
        $this->translations[$file] = ((include $filePath) ?? []);
    }

    /**
     * Sets the root directory for language files.
     *
     * @param string $languageRoot The root directory path.
     * @return bool True if the directory is valid, false otherwise.
     */
    protected function setLanguageRoot(string $languageRoot): bool
    {
        $languageRoot = rtrim($languageRoot, '/') . '/';
        if (!is_dir($languageRoot)) {
            return false;
        }
        $this->translationRoot = $languageRoot;
        return true;
    }

    /**
     * Sets the active language path.
     *
     * @param string $language The language code (e.g., 'en').
     * @return bool True if the path is valid, false otherwise.
     */
    protected function setLanguagePath(string $language): bool
    {
        $languagePath = $this->translationRoot . $language . '/';
        if (is_dir($languagePath)) {
            $this->translationPath = $languagePath;
            return true;
        }
        return false;
    }

    /**
     * Update services
     *
     * @return void
     */
    public function serviceUpdated()
    {
        // Load fallback translations if different from the active language
        if ($this->language !== $this->defaultLang) {
            $this->fallback = $this->translations;
        }
        if($this->setLanguagePath($this->language)){
            $this->translations = [];
            $this->preloadFiles();
        }
    }

}