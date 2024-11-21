<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Settings Helper
 * 
 *
 * This file is part of the Artex Essence meta-framework.
 *
 * @package    Essence\Helpers
 * @category   Helpers
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);


/**
 * Sets the default timezone.
 * 
 * @param  string $timezone The desired timezone using the valid 
 * @return bool Returns true on success, false on failure.
 * 
 * @see http://www.php.net/manual/en/timezones.php timezone formats 
 */
function setTimeZone(string $timezone): bool
{
    /**
     * Sets the default timezone.
     *
     * @link http://www.php.net/manual/en/function.date-default-timezone-set.php
     */
    return date_default_timezone_set($timezone);
}

/**
 * Gets the default timezone.
 * 
 * @return string Returns the system default timezone.
 * 
 * @see http://www.php.net/manual/en/timezones.php timezone formats 
 */
function getTimeZone(): string
{
    /**
     * Gets the default timezone.
     * 
     * @link http://www.php.net/manual/en/function.date-default-timezone-get.php
     */
    return date_default_timezone_get();
}