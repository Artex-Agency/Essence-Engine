<?php
namespace Essence\System\Debug;

/**
 * Debug Bar Class
 *
 * Collects and renders debugging information, including errors.
 */
class DebugBar
{
    /** @var array Accumulated debug messages */
    private array $messages = [];

    /** @var DebugBar|null Singleton instance */
    private static ?DebugBar $instance = null;

    private array $metrics = [];


    /**
     * Get or create a singleton instance of DebugBar.
     *
     * @return DebugBar
     */
    public static function instance(): DebugBar
    {
        if (!self::$instance) {
            self::$instance = new DebugBar();
        }
        return self::$instance;
    }

    /**
     * Add an error to the debug bar.
     *
     * @param array $errorDetails The error details to add.
     */
    public function addError(array $errorDetails): void
    {
        $this->messages[] = [
            'type' => 'error',
            'details' => $errorDetails,
        ];
    }

    /**
     * Add a dynamic metric to the debug bar.
     *
     * @param string $name Name of the metric.
     * @param callable $callback Callback function to calculate the metric value.
     * @return void
     */
    public function addDynamicMetric(string $name, callable $callback): void
    {
        $this->metrics[$name] = $callback;
    }

    /**
     * Get all dynamic metrics with resolved values.
     *
     * @return array
     */
    public function getMetrics(): array
    {
        $resolvedMetrics = [];
        foreach ($this->metrics as $name => $callback) {
            $resolvedMetrics[$name] = $callback();
        }
        return $resolvedMetrics;
    }


    /**
     * Render the collected debug information.
     *
     * @return string Rendered HTML or JSON for display.
     */
    public function render(): string
    {
        ob_start();

        echo "<div class='debug-bar'>";
        foreach ($this->messages as $message) {
            echo "<div class='debug-message {$message['type']}'>";
            echo "<strong>Error:</strong> {$message['details']['message']}<br>";
            echo "<strong>File:</strong> {$message['details']['file']}<br>";
            echo "<strong>Line:</strong> {$message['details']['line']}<br>";
            echo "<strong>Trace:</strong> <pre>{$message['details']['trace']}</pre>";
            echo "</div>";
        }
        echo "</div>";

        return ob_get_clean();
    }
}