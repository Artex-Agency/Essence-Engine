<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 *
 * @link      https://artexessence.com/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 */
declare(strict_types=1);

namespace Essence\Utils;

/**
 * File
 *
 * @package    Essence\Utils
 * @category   Utility
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class File
{


    /**
     * Overwrite the file contents.
     *
     * @param string $file  The full file path.
     * @param mixed  $data  The data to write to the file.
     * @param bool   $lock  Whether to lock the file during writing.
     *
     * @return int|false The number of bytes written, or false on failure.
     */
    public static function write(string $file, mixed $data, bool $lock = false): int|false
    {
        return self::writeFile($file, $data, $lock);
    }

    /**
     * Append data to a file.
     *
     * @param string $file  The full file path.
     * @param mixed  $data  The data to append to the file.
     * @param bool   $lock  Whether to lock the file during writing.
     *
     * @return int|false The number of bytes written, or false on failure.
     */
    public static function appendFileContents(string $file, mixed $data, bool $lock = false): int|false
    {
        return self::writeFile($file, $data, $lock, FILE_APPEND);
    }

    /**
     * Prepend data to a file.
     *
     * @param string $file  The full file path.
     * @param mixed  $data  The data to prepend to the file.
     * @param bool   $lock  Whether to lock the file during writing.
     *
     * @return int|false The number of bytes written, or false on failure.
     */
    public static function prependFileContents(string $file, mixed $data, bool $lock = false): int|false
    {
        if (is_file($file)) {
            $existingContent = @file_get_contents($file);
            if ($existingContent === false) {
                trigger_error("Failed to read existing content from file: $file", E_USER_WARNING);
                return false;
            }
            $data .= $existingContent;
        }
        return self::writeFile($file, $data, $lock);
    }

    /**
     * Core write function used by all methods.
     *
     * @param string $file   The file path.
     * @param mixed  $data   The data to write.
     * @param bool   $lock   Whether to lock the file.
     * @param int    $flags  Flags for file_put_contents.
     *
     * @return int|false
     */
    private static function putFileContents(string $file, mixed $data, bool $lock = false, int $flags = 0): int|false
    {
        // Ensure the directory exists
        $directory = dirname($file);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            trigger_error("Failed to create directory: $directory", E_USER_WARNING);
            return false;
        }

        // Add lock flag if requested
        if ($lock) {
            $flags |= LOCK_EX;
        }

        // Write data to file
        $result = @file_put_contents($file, $data, $flags);

        if ($result === false) {
            trigger_error("Failed to write to file: $file", E_USER_WARNING);
        }

        return $result;
    }
}