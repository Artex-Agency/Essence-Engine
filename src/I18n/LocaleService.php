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

use \trim; // For handling locale-related functions like canonicalize
use \rtrim; // To check if specific functions exist before using them
use \Locale; // To validate that a string contains only alphabetic characters
use \explode; // For pattern matching in validation
use \in_array; // To split strings into arrays
use \preg_match; // To remove whitespace or specific characters from strings
use \strtolower; // To remove trailing characters (used for directory paths)
use \strtoupper; // To convert strings to lowercase
use \array_merge; // To convert strings to uppercase
use \ctype_alpha; // To check if a value exists in an array
use \array_unique; // To remove duplicate values from arrays
use \function_exists; // To merge multiple arrays into one

/**
 * Locale Service
 *
 * Provides foundational methods for managing languages and locales
 * in accordance with ISO 639-1 and BCP 47 standards.
 *
 * @package    Essence\I18n
 * @category   Localization
 * @access     public
 * @version    1.1.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
abstract class LocaleService
{
    /**
     * List of valid language codes (ISO 639-1).
     *
     * @var array<string>
     */
    protected array $validLanguages = [
        'en', 'fr', 'de', 'es', 'ar', 'ru', 'tr', 'hi', 'ur', 'bn', 
        'vi', 'th', 'ms', 'id', 'sw', 'he', 'fa', 'it', 'pt', 'zh', 'ja', 'ko',
        'nl', 'sv', 'no', 'da', 'pl', 'cs', 'el', 'fi', 'hu', 'ro', 'sk', 'bg',
        'uk', 'sr', 'hr', 'lt', 'lv', 'et'
    ];

    /**
     * List of valid locales (language_REGION format).
     *
     * @var array<string>
     */
    protected array $validLocales = [
        'en-US', 'en-GB', 'en-AU', 'en-CA', 'fr-CA', 'fr-FR', 'es-ES', 'es-MX',
        'ar-SA', 'zh-CN', 'zh-TW', 'pt-BR', 'pt-PT', 'de-DE', 'it-IT', 'ja-JP',
        'ko-KR', 'ru-RU', 'tr-TR', 'nl-NL', 'sv-SE', 'no-NO', 'da-DK', 'pl-PL',
        'cs-CZ', 'el-GR', 'fi-FI', 'hu-HU', 'ro-RO', 'sk-SK', 'bg-BG', 'uk-UA',
        'sr-RS', 'hr-HR', 'lt-LT', 'lv-LV', 'et-EE'
    ];

    /**
     * Active locale.
     *
     * @var string
     */
    protected string $locale = 'en_US';

    /**
     * Default locale.
     *
     * @var string
     */
    protected string $defaultLocale = 'en_US';

    /**
     * Active language.
     *
     * @var string
     */
    protected string $language = 'en';

    /**
     * Default language.
     *
     * @var string
     */
    protected string $defaultLang = 'en';

    /**
     * Normalize and optionally validate a language or locale code.
     *
     * @param string $input The language or locale code to normalize.
     * @param bool $validate Whether to validate the normalized value.
     * @param bool $isLocale Whether to treat the input as a locale.
     * @return string|null The normalized code or null if invalid.
     */
    protected function normalizeCode(string $input, bool $validate = true, bool $isLocale = false): ?string
    {
        // Split the input into language and region parts
        [$language, $region] = array_pad(explode('-', $input, 2), 2, null);
        $language = strtolower(trim($language));
        $region = $region ? strtoupper(trim($region)) : null;

        // Validate the language or locale code
        if ($validate) {
            if ($isLocale && !$this->isValidLocale("$language_$region")) {
                return null;
            }
            if (!$isLocale && !$this->isValidLanguage($language)) {
                return null;
            }
        }

        return $isLocale && $region ? "{$language}_{$region}" : $language;
    }

    /**
     * Validate a language code.
     *
     * @param string $languageCode The language code to validate.
     * @return bool True if valid, false otherwise.
     */
    public function isValidLanguage(string $languageCode): bool
    {
        return ctype_alpha($languageCode) && strlen($languageCode) === 2 
            && in_array($languageCode, $this->validLanguages, true);
    }

    /**
     * Validate a locale code.
     *
     * @param string $localeCode The locale code to validate.
     * @return bool True if valid, false otherwise.
     */
    public function isValidLocale(string $localeCode): bool
    {
        return preg_match('/^[a-z]{2}_[A-Z]{2}$/', $localeCode)
            && in_array($localeCode, $this->validLocales, true);
    }

    /**
     * Set the active language.
     *
     * @param string $language The language code to set.
     * @return bool True if the language was successfully set.
     */
    public function setLanguage(string $language): bool
    {
        $normalized = $this->normalizeCode($language, true);
        if (!$normalized || !$this->setLanguagePath($normalized)) {
            return false;
        }

        $this->language = $normalized;
        $this->serviceUpdated();
        return true;
    }

    /**
     * Set the active locale.
     *
     * @param string $locale The locale code to set.
     * @return bool True if the locale was successfully set.
     */
    public function setLocale(string $locale): bool
    {
        $normalized = $this->normalizeCode($locale, true, true);
        if (!$normalized) {
            return false;
        }

        $this->locale = $normalized;
        $this->serviceUpdated();
        return true;
    }

    /**
     * Detect locale from HTTP headers.
     *
     * @return string|null The detected locale or null if not found.
     */
    public function detectLocaleFromHeaders(): ?string
    {
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null;
        if (!$acceptLanguage) {
            return null;
        }

        $locales = explode(',', $acceptLanguage);
        foreach ($locales as $locale) {
            $parts = explode(';', $locale);
            $normalized = $this->normalizeCode(trim($parts[0]), true, true);
            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    /**
     * Add or extend valid languages.
     *
     * @param array $languages Array of language codes to add.
     * @return void
     */
    public function addLanguages(array $languages): void
    {
        $this->validLanguages = array_unique(array_merge($this->validLanguages, $languages));
    }

    /**
     * Add or extend valid locales.
     *
     * @param array $locales Array of locale codes to add.
     * @return void
     */
    public function addLocales(array $locales): void
    {
        $this->validLocales = array_unique(array_merge($this->validLocales, $locales));
    }

    /**
     * Update services when language or locale changes.
     *
     * @return void
     */
    abstract protected function serviceUpdated(): void;
}