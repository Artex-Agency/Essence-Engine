<?php declare(strict_types=1);
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Part of the Artex Essence meta-framework.
 *  
 * @link      https://artexessence.com/ Project
 * @license   Artex Permissive Software License
 * @copyright 2024 Artex Agency Inc.
 */
namespace Essence\System;

/**
 * Standardizes server variables for compatibility across various server environments.
 * Focuses on ensuring consistent `$_SERVER` entries, particularly `REQUEST_URI`,
 * in environments like Microsoft IIS and CGI-based SAPIs.
 */
function adjustServerVariables(): void
{
    $_SERVER['SERVER_SOFTWARE'] = $_SERVER['SERVER_SOFTWARE'] ?? '';
    $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '';

    // Adjust REQUEST_URI for IIS or when it's missing
    if (empty($_SERVER['REQUEST_URI']) || (isIisServer() && PHP_SAPI !== 'cgi-fcgi')) {
        setRequestUri();
    }

    // Additional adjustments for php.cgi environments
    adjustForPhpCgi();
}

/**
 * Checks if the server software indicates Microsoft IIS.
 *
 * @return bool True if the server is running on IIS, false otherwise.
 */
function isIisServer(): bool
{
    return stripos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') === 0;
}

/**
 * Sets the `REQUEST_URI` server variable based on headers or `PATH_INFO`.
 */
function setRequestUri(): void
{
    // Use original or rewritten URLs if available
    if (!empty($_SERVER['HTTP_X_ORIGINAL_URL'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
        return;
    }

    if (!empty($_SERVER['HTTP_X_REWRITE_URL'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
        return;
    }

    // Derive REQUEST_URI from PATH_INFO or ORIG_PATH_INFO
    $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'] ?? ($_SERVER['ORIG_PATH_INFO'] ?? '');

    if (!empty($_SERVER['PATH_INFO'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . ($_SERVER['PATH_INFO'] !== $_SERVER['SCRIPT_NAME'] ? $_SERVER['PATH_INFO'] : '');
        if (!empty($_SERVER['QUERY_STRING'])) {
            $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
}

/**
 * Adjusts `SCRIPT_FILENAME` and clears `PATH_INFO` for php.cgi environments.
 */
function adjustForPhpCgi(): void
{
    if (!empty($_SERVER['SCRIPT_FILENAME']) && str_ends_with($_SERVER['SCRIPT_FILENAME'], 'php.cgi')) {
        $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];
    }

    if (strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false) {
        unset($_SERVER['PATH_INFO']);
    }
}

// Execute the adjustments for IIS or CGI environments
if (isIisServer() || PHP_SAPI === 'cgi-fcgi') {
    adjustServerVariables();
}