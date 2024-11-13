<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Error;

use Throwable;
use \php_sapi_name;

/**
 * ErrorTemplateRenderer
 * 
 * A lightweight, customizable template rendering system for error handling.
 * Supports alias-based path resolution and fallback templates, making it flexible
 * and extendable.
 * 
 * @package   Artex\Essence\Engine\System\Error
 * @category  Rendering
 * @access    public
 * @version   1.1.0
 * @since     1.0.0
 */
class ErrorTemplateRenderer
{
    /**
     * @var string $templatePath Path to the error template file.
     */
    private string $templatePath;

    /**
     * ErrorTemplateRenderer constructor.
     *
     * Initializes the template renderer with a path to the error template.
     * Sets the rendering mode based on whether the application is running
     * in a CLI or web context.
     * 
     * @param string|null $templatePath Path to the custom error template file.
     */
    public function __construct(?string $templatePath = '@error/default_error_template.html')
    {
        $this->templatePath = AliasManager::resolve($templatePath);
    }

    /**
     * Renders the error template.
     * 
     * Attempts to render a custom template file if available, or defaults to 
     * a hardcoded fallback template. Populates template variables with data
     * from the provided exception.
     * 
     * @param Throwable $exception The exception to render.
     */
    public function render(Throwable $exception): void
    {
        ErrorAwareSystem::triggerError(); // Ensure system is ready for rendering
        $templateContent = $this->loadTemplate($exception);
        echo php_sapi_name() === 'cli' ? strip_tags($templateContent) : $templateContent;
    }

    /**
     * Loads and parses the error template.
     *
     * @param Throwable $exception The exception data to include.
     * @return string The parsed template content.
     */
    private function loadTemplate(Throwable $exception): string
    {
        if (file_exists($this->templatePath)) {
            $templateContent = file_get_contents($this->templatePath);
            return $this->parseTemplate($templateContent, $exception);
        }
        return $this->getFallbackTemplate($exception);
    }

    /**
     * Parses the template with variables from the exception.
     * 
     * @param string $templateContent The raw template content.
     * @param Throwable $exception The exception data.
     * @return string The parsed template content.
     */
    private function parseTemplate(string $templateContent, Throwable $exception): string
    {
        $replacements = [
            '{{errorMessage}}' => htmlspecialchars($exception->getMessage(), ENT_QUOTES),
            '{{errorFile}}' => $exception->getFile(),
            '{{errorLine}}' => (string) $exception->getLine(),
            '{{errorTrace}}' => nl2br(htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES)),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $templateContent);
    }

    /**
     * Provides a hardcoded fallback template.
     *
     * Generates a simple HTML or text-based template when no custom template
     * file is available.
     * 
     * @param Throwable $exception The exception data.
     * @return string The fallback template content.
     */
    private function getFallbackTemplate(Throwable $exception): string
    {
        return "Error: {$exception->getMessage()}\nFile: {$exception->getFile()}\nLine: {$exception->getLine()}";
    }
}