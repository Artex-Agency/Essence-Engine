<?php

namespace App\Core;

class Bootstrapper
{
    private array $config;
    private array $instances = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Bootstraps and initializes all services.
     */
    public function init(): void
    {
        foreach ($this->config['services'] as $name => $service) {
            $this->instances[$name] = $this->initService($service);
        }
    }

    /**
     * Retrieves an initialized instance.
     */
    public function get(string $name): object
    {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->initService($this->config['services'][$name]);
        }
        return $this->instances[$name];
    }

    /**
     * Initializes a single service.
     */
    private function initService(array $service): object
    {
        $className = $service['class'];
        $dependencies = $this->resolveDependencies($service['dependencies'] ?? []);
        return new $className(...$dependencies);
    }

    /**
     * Resolves dependencies for a service.
     */
    private function resolveDependencies(array $dependencies): array
    {
        $resolved = [];
        foreach ($dependencies as $key => $value) {
            if (str_starts_with($value, 'config.')) {
                $configKey = substr($value, 7); // Remove 'config.' prefix
                $resolved[] = $this->config[$configKey] ?? null;
            } elseif (isset($this->instances[$value])) {
                $resolved[] = $this->instances[$value];
            } else {
                throw new \RuntimeException("Dependency {$value} not resolved.");
            }
        }
        return $resolved;
    }
}