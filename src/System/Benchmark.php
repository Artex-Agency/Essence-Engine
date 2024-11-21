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
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\System;

use \hrtime;
use \number_format;
use \memory_get_usage;

/**
 * Benchmark
 *
 * Measures the execution time and memory usage of code blocks.
 * Provides methods to start, stop, reset, and retrieve benchmark 
 * datain human-readable formats.
 *
 * @package    Essence\System
 * @category   Performance
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 * @link       https://artexessence.com/engine/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Benchmark
{
    /** @var int Timestamp when the benchmark started in nanoseconds */
    private int $startTime = 0;

    /** @var int Timestamp when the benchmark ended in nanoseconds */
    private int $endTime = 0;

    /** @var int Memory usage in bytes when the benchmark started */
    private int $startMemory = 0;

    /** @var int Memory usage in bytes when the benchmark ended */
    private int $endMemory = 0;

    /**
     * Starts the benchmark, capturing the current time and memory 
     * usage. If a specific start time is provided, it will override 
     * the default high-resolution time obtained from `hrtime(true)`.
     *
     * @param int|null $start Optional start time in nanoseconds. 
     *                        Defaults to `hrtime(true)` if not 
     *                        provided.
     * @return void
     */
    public function start(?int $start = null): void
    {
        if ($this->startTime === 0) {
            $this->startTime = $start ?? hrtime(true);
            $this->startMemory = memory_get_usage();
        }
    }

    /**
     * Stops the benchmark, capturing time and memory at the end.
     *
     * @return void
     */
    public function stop(): void
    {
        if ($this->startTime > 0 && $this->endTime === 0) {
            $this->endTime = hrtime(true);
            $this->endMemory = memory_get_usage();
        }
    }

    /**
     * Returns the raw elapsed time in nanoseconds.
     *
     * @return int The elapsed time in nanoseconds.
     */
    public function getRawTime(): int
    {
        return ($this->endTime > 0) ? ($this->endTime - $this->startTime) : 0;
    }

    /**
     * Returns the formatted elapsed time.
     *
     * @param int $accuracy Decimal precision for formatting (default is 2).
     * @return string Formatted elapsed time with units.
     */
    public function getTime(int $accuracy = 2): string
    {
        $duration = $this->getRawTime() / 1e6; // Convert nanoseconds to milliseconds
        $units = ['ms', 'secs', 'mins', 'hours', 'days'];
        $scales = [1000, 60, 60, 24];

        foreach ($scales as $i => $scale) {
            if ($duration < $scale) {
                return number_format($duration, $accuracy) . ' ' . $units[$i];
            }
            $duration /= $scale;
        }
        return number_format($duration, $accuracy) . ' ' . $units[count($units) - 1];
    }

    /**
     * Returns the raw memory used in bytes.
     *
     * @return int The raw memory usage in bytes.
     */
    public function getRawMemory(): int
    {
        return ($this->endMemory > 0) ? ($this->endMemory - $this->startMemory) : 0;
    }

    /**
     * Returns the formatted memory usage.
     *
     * @param int $accuracy Decimal precision for formatting (default is 2).
     * @return string Formatted memory usage with units.
     */
    public function getMemory(int $accuracy = 2): string
    {
        $memory = $this->getRawMemory();
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        foreach ($units as $unit) {
            if ($memory < 1024) {
                return number_format($memory, $accuracy) . ' ' . $unit;
            }
            $memory /= 1024;
        }
        return number_format($memory, $accuracy) . ' TB';
    }

    /**
     * Runs a callback function for a given number of iterations and 
     * benchmarks it.
     *
     * @param callable $callback The callback function to test.
     * @param int $iterations Number of iterations to run (default 1000).
     * @return bool True if the benchmark is completed; false otherwise.
     */
    public function test(callable $callback, int $iterations = 1000): bool
    {
        if ($this->startTime === 0) {
            $this->start();
            for ($i = 0; $i < $iterations; $i++) {
                call_user_func($callback);
            }
            $this->stop();
            return true;
        }
        return false;
    }

    /**
     * Resets the benchmark, clearing all captured time and memory data.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->startTime = 0;
        $this->endTime = 0;
        $this->startMemory = 0;
        $this->endMemory = 0;
    }
}