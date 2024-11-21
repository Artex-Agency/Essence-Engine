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

use \substr;
use \openssl_decrypt;
use \openssl_encrypt;
use \OPENSSL_RAW_DATA;
use \RuntimeException;
use \Essence\Utils\Base64;
use \openssl_cipher_iv_length;
use \openssl_random_pseudo_bytes;

/**
 * Crypt
 *
 * A utility class for AES-256-GCM encryption and decryption. Uses 
 * best practices for secure, high-performance cryptography.
 *
 * @package    Essence\Utils
 * @category   Utility
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Crypt
{
    /**
     * Encryption algorithm and mode, for example: AES-256-GCM.
     * @var string
     */
    private static string $method = 'aes-256-gcm';

    /**
     * Encryption key, stored securely outside of source code.
     * @var string
     */
    private static string $key = 'your-secure-key-here';

    /**
     * Encrypts a given string using AES-256-GCM.
     *
     * Generates a unique IV for each encryption, uses authenticated 
     * encryption with GCM, and returns a Base64 encoded string 
     * containing the IV, tag, and ciphertext.
     *
     * @param string $text The plaintext string to encrypt.
     * @return string The base64-encoded ciphertext with IV and tag.
     * @throws RuntimeException if encryption fails.
     */
    public static function encrypt(string $text): string
    {
        $ivLength = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $tag = '';

        // Encrypt text
        $cipherText = openssl_encrypt(
            $text, 
            self::$method, 
            self::$key, 
            OPENSSL_RAW_DATA, 
            $iv, 
            $tag, 
            '', 
            16
        );

        if ($cipherText === false) {
            throw new RuntimeException('Encryption failed');
        }
        // Concatenate IV, tag, and ciphertext, then encode
        return Base64::encode($iv . $tag . $cipherText);
    }

    /**
     * Decrypts a given base64-encoded ciphertext.
     *
     * Decrypts AES-256-GCM encrypted text by extracting the IV, tag, 
     * and ciphertext from the input, validating the structure, and 
     * returning the plaintext.
     *
     * @param string $cipher The base64-encoded ciphertext to decrypt.
     * @return string The decrypted plaintext string.
     * @throws RuntimeException if decryption fails or input is invalid.
     */
    public static function decrypt(string $cipher): string
    {
        $decoded = Base64::decode($cipher, true);
        if ($decoded === false) {
            throw new RuntimeException('Invalid ciphertext input');
        }

        $ivLength = openssl_cipher_iv_length(self::$method);
        $iv = substr($decoded, 0, $ivLength);
        $tag = substr($decoded, $ivLength, 16);
        $cipherText = substr($decoded, $ivLength + 16);

        if ($iv === false || $tag === false || $cipherText === false) {
            throw new RuntimeException('Invalid ciphertext structure');
        }

        $plainText = openssl_decrypt(
            $cipherText,
            self::$method,
            self::$key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($plainText === false) {
            throw new RuntimeException('Decryption failed');
        }
        return $plainText;
    }
}