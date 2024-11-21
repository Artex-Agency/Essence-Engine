<?php

namespace App\Themes;

class TemplateFactory
{
    private string $themePath;

    public function __construct(string $themePath)
    {
        $this->themePath = rtrim($themePath, '/') . '/';
    }

    public function render(string $template, array $data = []): void
    {
        $templatePath = $this->themePath . "templates/{$template}.php";

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template {$template} not found in {$templatePath}");
        }

        extract($data, EXTR_OVERWRITE);
        include $templatePath;
    }
}