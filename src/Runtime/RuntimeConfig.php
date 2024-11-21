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

namespace Essence\Runtime;

use \ini_get;
use \ini_set;
use \in_array;
use \is_string;
use \filter_var;
use \strtolower;
use \strtoupper;
use \preg_replace;
use RuntimeException;
use \FILTER_VALIDATE_INT;
use \Essence\Paths\PathMap;
use \FILTER_FLAG_ALLOW_FRACTION;
use \FILTER_SANITIZE_NUMBER_FLOAT;

/**
 * RuntimeConfig Class
 *
 * Manages runtime PHP ini settings by validating and setting values
 * based on their expected types, such as numeric limits, memory limits,
 * boolean flags, and paths.
 *
 * @package    Essence\Runtime
 * @category   Configuration
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 */
class RuntimeConfig
{
    /**
     * Path mapping utility.
     *
     * @var PathMap|null
     */
    protected ?PathMap $pathMap = null;

    /**
     * Mapping of ini settings to their expected types.
     *
     * @var array<string, string>
     */
    protected array $iniMap = [
        'max_execution_time'  => 'number',
        'max_file_uploads'    => 'number',
        'max_input_time'      => 'number',
        'max_input_vars'      => 'number',
        'upload_max_filesize' => 'bytes',
        'post_max_size'       => 'bytes',
        'memory_limit'        => 'bytes',
        'allow_url_fopen'     => 'bool',
        'allow_url_include'   => 'bool',
        'file_uploads'        => 'bool',
        'log_errors'          => 'bool',
        'expose_php'          => 'bool',
        'session.save_path'   => 'file',
        'upload_tmp_dir'      => 'file',
        'error_log'           => 'file',
    ];

    /**
     * Constructor
     *
     * Initializes with configuration settings and PathMap for path aliasing.
     *
     * @param array<string, mixed> $config Initial configuration settings.
     * @param PathMap $pathMap PathMap instance for path alias resolution.
     */
    public function __construct(array $config, PathMap $pathMap)
    {
        $this->pathMap = $pathMap;
        foreach ($config as $key => $value) {
            $this->setIni($key, $value);
        }
    }

    /**
     * Retrieves a PHP ini setting.
     *
     * @param string $key The ini configuration key.
     * @return mixed The value of the ini setting.
     */
    public function getIni(string $key): mixed
    {
        return ini_get($key);
    }

    /**
     * Sets a PHP ini configuration value based on its type.
     *
     * @param string $key   The ini configuration key.
     * @param mixed  $value The value to set for the configuration key.
     * @return bool True if successfully set, false otherwise.
     */
    public function setIni(string $key, mixed $value): bool
    {
        $type = $this->iniMap[$key] ?? null;
        return match ($type) {
            'number' => $this->setIniNumeric($key, $value),
            'bytes'  => $this->setIniBytes($key, $value),
            'bool'   => $this->setIniBool($key, $value),
            'file'   => $this->setIniFile($key, $value),
            default  => ini_set($key, (string) $value) !== false,
        };
    }

    /**
     * Validates and sets numeric ini settings.
     *
     * Ensures numeric values are formatted correctly for settings like
     * `max_execution_time`, `max_input_time`, etc.
     *
     * @param string $key The ini configuration key.
     * @param mixed  $value The value to set.
     * @return bool True if successfully set, false otherwise.
     */
    protected function setIniNumeric(string $key, mixed $value): bool
    {
        $numericValue = filter_var($value, FILTER_VALIDATE_INT);
        return ($numericValue !== false) && ini_set($key, (string) $numericValue) !== false;
    }

    /**
     * Validates and sets byte-based ini settings.
     *
     * Formats memory-related limits such as `memory_limit`, `upload_max_filesize`, etc.
     *
     * @param string $key The ini configuration key.
     * @param mixed  $value The value to set.
     * @return bool True if successfully set, false otherwise.
     */
    protected function setIniBytes(string $key, mixed $value): bool
    {
        $numericValue = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $unit = strtoupper(preg_replace('/[0-9.]/', '', (string) $value)) ?: 'M';

        if (!in_array($unit, ['K', 'M', 'G'])) {
            $unit = 'M'; // Default to MB if no valid unit is found
        }
        return ini_set($key, $numericValue . $unit) !== false;
    }

    /**
     * Validates and sets boolean ini settings.
     *
     * Sets boolean settings such as `log_errors`, `file_uploads`, etc.
     *
     * @param string $key The ini configuration key.
     * @param mixed  $value The value to set.
     * @return bool True if successfully set, false otherwise.
     */
    protected function setIniBool(string $key, mixed $value): bool
    {
        $boolValue = match (strtolower((string) $value)) {
            'true', '1'  => '1',
            'false', '0' => '0',
            default       => '0',
        };
        return ini_set($key, $boolValue) !== false;
    }

    /**
     * Interpolates and sets file path-based ini settings.
     *
     * Parses the file path using PathMap for alias support in settings
     * such as `upload_tmp_dir`, `session.save_path`, etc.
     *
     * @param string $key The ini configuration key.
     * @param mixed  $value The value to set.
     * @return bool True if successfully set, false otherwise.
     */
    protected function setIniFile(string $key, mixed $value): bool
    {
        echo "<p>Original Path: $value</p>";
        $parsedPath = $this->pathMap->parse($value);
        echo "<p>Parsed Path: $parsedPath</p><hr>";
        return ini_set($key, $parsedPath) !== false;
    }
}