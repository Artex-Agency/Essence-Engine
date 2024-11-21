<?php
declare(strict_types=1);

namespace Essence\System\Error;


use \Throwable;
use \Essence\Utils\Interpolate;

/**
 * ErrorRenderer
 *
 * Responsible for rendering error outputs. Supports template rendering,
 * code snippet extraction, debug bar integration, and CLI-friendly outputs.
 *
 * @package    Essence\System\Error
 * @category   Error Management
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober
 * @link       https://artexessence.com/ Project Website
 */
class ErrorRenderer
{
    /** @var string Default error template path */
    private string $defaultTemplate;

    /** @var ErrorConfig Error configuration settings */
    private ErrorConfig $config;

    /** @var Interpolate Template interpolation engine */
    private Interpolate $interpolator;


    /**
     * Constructor.
     *
     * @param string      $defaultTemplate Path to the default error template.
     * @param ErrorConfig $config          Configuration settings for error rendering.
     */
    public function __construct(string $defaultTemplate, ErrorConfig $config)
    {
        $this->defaultTemplate = $defaultTemplate;
        $this->config = $config;
        $this->interpolator = new Interpolate();
    }


    public function renderError(array $data): void
    {
        $templateContent = file_get_contents($this->defaultTemplate);
        $this->interpolator->addStaticTokens($data);
        $renderedContent = $this->interpolator->parse($templateContent);

        $mode = $this->config->get('error_rendering_mode', 'full');

        switch ($mode) {
            case 'full':
                $this->clearSlate($renderedContent);
                break;
            case 'overlay':
                $this->renderOverlay($renderedContent);
                break;
            case 'append':
                $this->renderAppend($renderedContent);
                break;
            default:
                throw new \InvalidArgumentException("Unknown rendering mode: $mode");
        }
    }

    private function clearSlate(string $content): void
    {
        while (ob_get_level()) {
            ob_end_clean(); // Clear all output buffers
        }
        echo $content; // Render error as sole content
    }

    private function renderOverlay(string $content): void
    {
        echo '<div id="error-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); z-index: 9999;">';
        echo $content;
        echo '</div>';
    }

    private function renderAppend(string $content): void
    {
        echo '<div id="error-append" style="margin-top: 20px;">';
        echo $content;
        echo '</div>';
    }

    /**
     * Get the default error template path.
     *
     * @return string The path to the default error template.
     */
    public function getDefaultTemplate(): string
    {
        return $this->defaultTemplate;
    }

    /**
     * Render an error template with interpolated data.
     *
     * This method takes a file path to an HTML template and interpolates the provided data into it.
     * Placeholders in the template should be wrapped in double curly braces, e.g., `{{key}}`.
     *
     * @param string $template Path to the template file.
     * @param array  $data     Associative array of data to interpolate into the template.
     * @return string Rendered error output.
     *
     * @throws \RuntimeException If the template file does not exist or is not readable.
     */
    public function render(string $template, array $data = []): string
    {
        if (!file_exists($template)) {
            throw new \RuntimeException("Error template not found: $template");
        }    
        $content = file_get_contents($template);
        // Inject error-specific tokens into the interpolator
        $this->interpolator->addStaticTokens($data);

        // Perform the token replacement and return the final rendered output
        return $this->interpolator->parse($content);

        //$this->interpolator->addArray([]); // Clear previous tokens
        //$this->interpolator->addArray($data); // Add current tokens
        //return $this->interpolator->translate($content);
    }

    /**
     * Render the default error template with data.
     *
     * @param array $data Data to interpolate into the default template.
     * @return string Rendered error output.
     */
    public function renderDefault(array $data = []): string
    {
        return $this->render($this->defaultTemplate, $data);
    }

    /**
     * Render a Throwable (exception or error) using the configured template or fallback.
     *
     * @param Throwable $exception The exception or error to render.
     * @return string Rendered error output.
     */
    public function renderException(Throwable $exception): string
    {
        $mode = $this->config->get('error_display_mode', 'detailed');
        $templatePath = $this->getTemplatePath($mode);
        $template = $this->config->get('detailed_error_template', $this->defaultTemplate);

        $data = [
            'message'   => htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8'),
            'file'      => htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8'),
            'line'      => $exception->getLine(),
            'timestamp' => date('Y-m-d H:i:s'),
            'trace'     => htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8'),
            'code'      => $this->extractCodeSnippet($exception->getFile(), $exception->getLine()),
        ];

        if (file_exists($templatePath)) {
            return $this->render($templatePath, $data);
        }
        return $this->renderFallbackHtml($data);
    }

    /**
     * Determine the template path based on the display mode.
     *
     * @param string $mode Error display mode (e.g., "detailed", "simple").
     * @return string Path to the appropriate template.
     */
    private function getTemplatePath(string $mode): string
    {
        $basePath = ESS_ERROR_VIEW;
        return match ($mode) {
            'detailed' => $basePath . 'detailed_error.php',
            'simple'   => $basePath . 'simple_error.php',
            default    => $this->defaultTemplate,
        };
    }

    /**
     * Render a fallback HTML output for exceptions.
     *
     * @param array $data Associative array with error details.
     * @return string Rendered fallback HTML output.
     */
    private function renderFallbackHtml(array $data): string
    {
        return sprintf(
            "<h1>Exception Caught</h1>
            <p><strong>Message:</strong> %s</p>
            <p><strong>File:</strong> %s</p>
            <p><strong>Line:</strong> %d</p>
            <pre>%s</pre>",
            $data['message'],
            $data['file'],
            $data['line'],
            $data['trace']
        );
    }
    /**
     * Extract a code snippet surrounding a specific line from a file.
     *
     * @param string $file    Path to the file.
     * @param int    $line    Line number of the error.
     * @param int    $padding Number of lines of context before and after.
     * @return string Rendered code snippet.
     */
    private function extractCodeSnippet(string $file, int $line, int $padding = 5): string
    {
        if (!file_exists($file)) {
            return 'Code file not found.';
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $start = max($line - $padding - 1, 0);
        $end = min($line + $padding - 1, count($lines) - 1);

        $snippet = '';
        for ($i = $start; $i <= $end; $i++) {
            $lineNumber = str_pad((string)($i + 1), 4, ' ', STR_PAD_LEFT);
            $highlight = $i + 1 === $line ? 'background: #ffcccb;' : '';
            $snippet .= sprintf(
                '<span style="display: block; %s">%s %s</span>',
                $highlight,
                $lineNumber,
                htmlspecialchars($lines[$i], ENT_QUOTES, 'UTF-8')
            );
        }
        return $snippet;
    }

    /**
     * Handle an exception based on the configured error display mode.
     *
     * @param Throwable $exception The exception to handle.
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        $mode = $this->config->get('error_display_mode', 'detailed');

        switch ($mode) {
            case 'debug_bar':
                // Assuming integration with a Debug Bar instance
                $debugBar = $this->config->get('debug_bar');
                if ($debugBar) {
                    $debugBar->addError($exception);
                }
                break;

            case 'simple':
                echo sprintf(
                    "An error occurred: %s in %s on line %d",
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine()
                );
                break;

            case 'detailed':
            default:
                echo $this->renderException($exception);
                break;
        }
    }
}