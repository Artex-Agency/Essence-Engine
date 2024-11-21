<?php
declare(strict_types=1);

namespace Essence\System\Error;

use \InvalidArgumentException;

/**
 * ErrorConfig
 * 
 * Handles configurations for the error handling system, allowing flexible
 * control over behavior and integration with different interfaces.
 * 
 * @package    Essence\System\Error
 * @category   Configuration
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 */
class ErrorConfig
{
    /** @var bool Whether to convert all errors into exceptions. */
    private bool $errorsAsExceptions = true;

    /** @var string The default rendering template. */
    private string $templatePath = '/path/to/default_error_template.php';

    /** @var string|null Interface for error display (e.g., CLI, web). */
    private ?string $interface = null;

    /** @var array Configurable display modes. */
    private array $displayModes = [
        'detailed' => true,
        'simple'   => false,
        'debugBar' => false,
    ];

    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    /**
     * Retrieve a configuration value by key.
     *
     * @param string $key     The key to retrieve.
     * @param mixed  $default Default value if the key doesn't exist.
     * @return mixed          The configuration value or default.
     */
    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Check if a configuration key exists.
     *
     * @param string $key The key to check.
     * @return bool       True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Sets whether errors are handled as exceptions.
     *
     * @param bool $value
     * @return void
     */
    public function setErrorsAsExceptions(bool $value): void
    {
        $this->errorsAsExceptions = $value;
    }

    /**
     * Get whether errors are handled as exceptions.
     *
     * @return bool
     */
    public function getErrorsAsExceptions(): bool
    {
        return $this->errorsAsExceptions;
    }

    /**
     * Set the template path for rendering errors.
     *
     * @param string $path
     * @throws InvalidArgumentException If the path is invalid.
     * @return void
     */
    public function setTemplatePath(string $path): void
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("Template path does not exist: $path");
        }
        $this->templatePath = $path;
    }

    /**
     * Get the current template path.
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * Set the interface for error display.
     *
     * @param string $interface
     * @return void
     */
    public function setInterface(string $interface): void
    {
        $this->interface = $interface;
    }

    /**
     * Get the current error display interface.
     *
     * @return string|null
     */
    public function getInterface(): ?string
    {
        return $this->interface;
    }

    /**
     * Enable a specific display mode.
     *
     * @param string $mode
     * @return void
     */
    public function enableDisplayMode(string $mode): void
    {
        if (array_key_exists($mode, $this->displayModes)) {
            $this->displayModes[$mode] = true;
        }
    }

    /**
     * Disable a specific display mode.
     *
     * @param string $mode
     * @return void
     */
    public function disableDisplayMode(string $mode): void
    {
        if (array_key_exists($mode, $this->displayModes)) {
            $this->displayModes[$mode] = false;
        }
    }

    /**
     * Get the current display modes.
     *
     * @return array
     */
    public function getDisplayModes(): array
    {
        return $this->displayModes;
    }
}