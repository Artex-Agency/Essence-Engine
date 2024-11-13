<?php declare(strict_types=1);

namespace Artex\Essence\Engine\System\Config;

class ConfigLoader
{
    protected string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Load configuration by filename, without requiring the extension.
     *
     * @param string $filename
     * @return array
     */
    public function load(string $filename): array
    {
        $path = "{$this->basePath}/{$filename}.php";
        
        if (!is_file($path)) {
            throw new \RuntimeException("Config file '{$filename}' not found.");
        }

        return require $path;
    }
}