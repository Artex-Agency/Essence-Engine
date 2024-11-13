<?php
#  ¸_____¸____¸________¸____¸_¸  ¸__¸
#  |  _  |  __ \_    _|   __|  \/  /
#  |     |     / |  | |   __|}    {
#  |__|__|__|__\ |__| |_____|__/\__\
#  ARTEX SOFTWARE :: PHP ESSENTIALS
#
/**
 * This file is part of the PHP Essential Library by Artex Software.
 * 
 * @link      https://artexsoftware.com/ Artex Software
 * @license   ARTEX OPEN SOURCE LICENSE (AOSL). *Permissive open-source*
 * @copyright © 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Utils;

use \is_array;
use \is_object;
use \serialize;
use \gzcompress;
use \gzcompress;
use \unserialize;
use \base64_decode;
use \base64_encode;
use \RuntimeException;

/**
 * CompressionManager
 * 
 * Provides methods to compress and decompress data using Gzip compression.
 * Suitable for reducing storage size and optimizing cache or database storage
 * when handling large data sets.
 * 
 * @package   Artex\Essence\Engine\System\Utils
 * @category  Data Compression
 * @access    public
 * @version   1.0.0
 * @since     1.0.0
 */
class CompressionManager
{
    /**
     * Compresses data using Gzip compression.
     *
     * @param mixed $data The data to compress (usually a string or serialized object).
     * @param int $level  Compression level (0-9), where 9 is the highest compression.
     * @return string     The compressed data, encoded in base64 for safe storage.
     * @throws \RuntimeException if compression fails.
     */
    public static function compress(mixed $data, int $level = 6): string
    {
        // Serialize if data is an array or object
        if (is_array($data) || is_object($data)) {
            $data = serialize($data);
        }

        // Perform compression
        $compressedData = gzcompress($data, $level);
        if ($compressedData === false) {
            throw new \RuntimeException("Failed to compress data.");
        }

        // Encode in base64 to ensure safe storage in text fields or transport
        return base64_encode($compressedData);
    }

    /**
     * 
     * Decompresses data compressed with Gzip compression.
     *
     * @param string $compressedData The compressed data to decompress.
     * @return mixed                 The original data, decompressed and unserialized if applicable.
     * @throws \RuntimeException if decompression fails.
     */
    public static function decompress(string $compressedData): mixed
    {
        // Decode base64
        $decodedData = base64_decode($compressedData, true);
        if ($decodedData === false) {
            throw new \RuntimeException("Failed to decode base64 data.");
        }

        // Perform decompression
        $decompressedData = gzuncompress($decodedData);
        if ($decompressedData === false) {
            throw new \RuntimeException("Failed to decompress data.");
        }

        // Attempt to unserialize data if it was serialized
        $unserializedData = @unserialize($decompressedData);
        return $unserializedData === false ? $decompressedData : $unserializedData;
    }
}