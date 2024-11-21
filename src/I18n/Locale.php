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

use \Locale;
use \Essence\I18n\LocaleService;

/**
 * Locale
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
class Locale extends LocaleService
{
    /**
     * Locale settings
     *
     * @var array
     */
    protected array $settings = [];

    /**
     * Locales root directory.
     *
     * @var string
     */
    protected string $localesRoot = '';

    /**
     * Locales directory.
     *
     * @var string
     */
    protected string $localesPath = '';

    /**
     * Locale files
     *
     * @var array
     */
    protected array $localeFiles = [];

    /**
     * Constructor.
     * 
     * @param string $localesDir The root locales directory.
     * @param string $locale The locale code (default 'en_US').
     * @param array $localeFiles The locale files to load.
     */
    public function __construct(string $localesDir, string $locale = 'en_US', array $localeFiles = []){
        if(!$this->setLocalRoot($localesDir)){
            return;
        }
        $this->localeFiles = $localeFiles ?? [];
        if($this->setLocale($locale)){
        }
    }

    /**
     * Get a setting from the loaded locale file.
     *
     * @param string $key The setting key (e.g., 'date_format').
     * @param mixed $default The default value if not found.
     * @return mixed The setting value or default.
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Format a date based on the current locale.
     *
     * @param \DateTime $date The date to format.
     * @return string The formatted date.
     */
    public function formatDate(\DateTime $date): string
    {
        $format = $this->getLocaleSetting('date_format', 'Y-m-d');
        return $date->format($format);
    }

    /**
     * Format a number based on the current locale.
     *
     * @param float $number The number to format.
     * @return string The formatted number.
     */
    public function formatNumber(float $number): string
    {
        $decimalPoint = $this->getLocaleSetting('number_format.decimal_point', '.');
        $thousandsSep = $this->getLocaleSetting('number_format.thousands_sep', ',');
        return number_format($number, 2, $decimalPoint, $thousandsSep);
    }

    /**
     * Format a currency value based on the current locale.
     *
     * @param float $value The currency value.
     * @return string The formatted currency.
     */
    public function formatCurrency(float $value): string
    {
        $symbol = $this->getLocaleSetting('currency_symbol', '$');
        $format = $this->getLocaleSetting('currency_format', '${value}');
        return str_replace('${value}', number_format($value, 2), $format);
    }



    /**
     * Sets the root directory for locales.
     *
     * @param string $localesRoot The root directory path.
     * @return bool True if the directory is valid, false otherwise.
     */
    protected function setLocalRoot(string $localesRoot): bool
    {
        $localesRoot = rtrim($localesRoot, '/') . '/';
        if (!is_dir($localesRoot)) {
            return false;
        }
        $this->localesRoot = $localesRoot;
        return true;
    }

    /**
     * Sets the active locale path.
     *
     * @param string $locale The locale code (e.g., 'en_US').
     * @return bool True if the path is valid, false otherwise.
     */
    protected function setLocalPath(string $locale): bool
    {
        $localePath = $this->localesRoot . $locale . '/';
        if (is_dir($localePath)) {
            $this->localesPath = $localePath;
            return true;
        }
        return false;
    }

    /**
     * load locale files from list.
     *
     * @return void
     */
    protected function loadLocaleFiles(): void
    {
        foreach ($this->localeFiles as $file) {
            $this->loadFile($file);
        }
    }

    /**
     * Load a locale file.
     *
     * @param string $file The locale file name.
     * @return bool True if loaded successfully, false otherwise.
     */
    protected function loadFile(string $file): bool
    {
        $file = rtrim(strtolower(trim($file)), '.php');
        $filePath = "{$this->localesPath}{$file}.php";
        if (!is_file($filePath)) {
            return false;
        }
        $this->settings = include $filePath;
        return true;
    }

    /**
     * Update services
     *
     * @return void
     */
    public function serviceUpdated()
    {
        // Load fallback translations if different from the active language
        if($this->setLocalPath($this->setLocale)){
            $this->settings = [];
            $this->loadLocaleFiles();
        }
    }
}