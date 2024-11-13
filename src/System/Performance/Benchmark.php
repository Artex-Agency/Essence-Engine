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

namespace Artex\Essence\Engine\System\Performance;

use \hrtime;
use \number_format;
use \memory_get_usage;
use \Artex\Essence\Engine\System\Monitoring\Timer;
use \Artex\Essence\Engine\System\Monitoring\Memory;

/**
 * Benchmark Timer and Memory Usage Monitor
 *
 * Manages and aggregates multiple monitors for benchmarking.
 * Automatically includes Timer and optional monitors like memory.
 *
 * @package    Artex\Essence\Engine\System\Performance
 * @category   Performance Monitoring
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Benchmark
{
    /** @var Timer Core timer instance for benchmarking. */
    private ?Timer $timer = null;

    /** @var Timer Core timer instance for benchmarking. */
    private ?Memory $Memory = null;

    /** @var array monitoring servicess. */
    protected array $services = [
        'timer'  => \Artex\Essence\Engine\System\Monitoring\Timer::class,
        'memory' => \Artex\Essence\Engine\System\Monitoring\Memory::class
    ];

    /** @var array benchmark results by monitor. */
    protected array $results = [];

    /** @var MonitorInterface[] List of optional registered monitors. */
    private array $monitors = [];


    /**
     * Bootstrap init
     */
    public function __construct()
    {
        $this->timer = new Timer();
    }

    /**
     * Register an additional monitor to the manager.
     *
     * @param MonitorInterface $monitor
     */
    public function addMonitor(MonitorInterface $monitor): void
    {
        $name = basename(str_replace('\\', '/', get_class($monitor)));
        $this->monitors[$name] = $monitor;
    }

    /**
     * Start the benchmark.
     * 
     * @param integer $time
     * @return void
     */
    public function start(int $time = 0): void
    {
        // Start Timer
        $this->timer->start($time);

        if($this->monitors){
            foreach ($this->monitors as $name => $monitor) {
                $monitor[$name]->start();
            }
        }
    }

    /**
     * Stop the benchmark.
     * 
     * @return void
     */
    public function stop(): void
    {
        if ($this->checkState(self::BENCH_FINISHED)) {
            return;
        }
        // Record Elapsed Time and Memory Difference
        $this->time   = (hrtime(true) - ($this->time));
        $this->memory = (memory_get_usage() - ($this->memory));

        // Set next state
        $this->nextState();
    }

    /**
     * Get results from all monitors, with timer data included.
     *
     * @return array
     */
    public function getResults(): array
    {
        $results = [
            'timer' => $this->timer->getElapsedTime(),
        ];
        foreach ($this->monitors as $monitor) {
            $results[] = $monitor->getResult();
        }
        return $results;
    }
    /**
     * Gets the elapsed benchmark time in a formatted string.
     *
     * Converts the benchmark time from nanoseconds to a human-readable format.
     * 
     * @param int $accuracy The decimal precision for formatting (default 2).
     * @return string The formatted time or an empty string if not finished.
     */
    public function getTime(int $accuracy = 2): string
    {
        if (!$this->checkState(self::BENCH_FINISHED)) {
            return '';
        }

        // Return unformatted if accuracy is 0
        if ($accuracy === 0) {
            return (string)$this->time;
        }

        $milliseconds = $this->time / 1e6;
        $accuracy = max(0, min(6, $accuracy));

        $units = [ 
            'ms'    => 1000,
            'secs'  => 60, 
            'mins'  => 60, 
            'hours' => 24, 
            'days'  => 7
        ];

        foreach ($units as $unit => $max) {
            if ($milliseconds < $max || $max === 0) {
                return number_format($milliseconds, $accuracy) . ' ' . $unit;
            }
            $milliseconds /= $max;
        }

        return number_format($milliseconds, $accuracy) . ' weeks';
    }

    /**
     * Gets the benchmark memory usage formatted into appropriate units.
     *
     * @param int $accuracy Decimal precision for formatting (default 2).
     * @return string The formatted memory usage.
     */
    public function getMemory(int $accuracy = 2): string
    {
        if (!$this->checkState(self::BENCH_FINISHED)) {
            return '';
        }

        if (($accuracy === 0)) {
            return (string)$this->memory;
        }

        if((!$this->memory) || (null === $this->memory)){
            return '0.00 KB';
        }

        $memory = $this->memory;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        foreach ($units as $unit) {
            if ($memory < 1024) {
                return number_format($memory, $accuracy) . ' ' . $unit;
            }
            $memory /= 1024;
        }
        // memory_get_usage()
        return number_format($memory, $accuracy) . ' TB';
    }

    /**
     * Benchmark the performance of a callback function over multiple iterations.
     *
     * @param  callable $callback The callback function to test.
     * @param  int      $iterations The number of iterations (default 1000).
     * @return bool Returns true on success; false otherwise.
     */
    public function test(callable $callback, int $iterations = 1000): bool
    {
        if ($this->checkState(self::BENCH_STARTED)) {
            return false;
        }
        $this->start();
        for ($i = 0; $i < $iterations; $callback(), $i++);
        $this->stop();
        return true;
    }

    /**
     * Reset the benchmark.
     *
     * This method resets the benchmark's internal state, clearing 
     * the stored time and memory usage. It also sets the state back 
     * to the initial "ready" state, allowing the benchmark to be 
     * started again from the beginning.
     *
     * @return void
     */
    public function reset(): void
    {
        // Reset time
        $this->time = null;
    
        // Reset memory
        $this->memory = null;

        // Reset state to ready.
        self::$state = self::BENCH_READY;
    }

    /**
     * Check if the current state is valid.
     *
     * @param int $flag The state flag to check.
     * @return bool True if the current state is valid; false otherwise.
     */
    private function checkState(int $flag = 1): bool
    {
        return self::$state >= $flag;
    }

    /**
     * Move to the next benchmark state.
     *
     * Advances the internal state if the benchmark is not finished.
     *
     * @return void
     */
    private function nextState(): void
    {
        if (self::$state < self::BENCH_FINISHED) {
            self::$state++;
        }
    }
}