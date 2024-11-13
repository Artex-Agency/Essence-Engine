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

namespace Artex\Essence\Engine\System\Monitoring;

use \number_format;
use \memory_get_usage;
use \Artex\Essence\Engine\System\Monitoring\MonitorInterface;

/**
 * Memory
 * 
 * Represents an individual timer used within the Benchmark class.
 * Each Timer tracks its own start and end times, providing duration
 * calculations on request.
 * 
 * @package    Artex\Essence\Engine\System\Monitoring
 * @category   Monitoring
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 */
class Memory implements MonitorInterface
{
    /**
     * @var int The memory used on start.
     */
    private int $startMemory = 0;

    /**
     * @var int The memory used on finish.
     */
    private int $endMemory = 0;

    /**
     * @var int The calculated difference in memory.
     */
    private int $difference = 0;

    /**
     * Capture memory used on start.
     *
     * @return void
     */
    public function start(int $input=0): void
    {
        if($this->startMemory === 0){
            $this->startMemory = memory_get_usage();
        }
    }

    /**
     * Capture memory used on stop.
     *
     * @return void
     */
    public function stop(): void
    {
        if($this->startMemory > 0 && $this->endMemory === 0){
            $this->endMemory = memory_get_usage();
        }
    }

    /**
     * Calculates and returns the difference in memory.
     *
     * @return mixed The difference in memory used.
     */
    public function getResult(): ?mixed
    {
        if($this->endMemory > 0 && $this->difference === 0){
            $this->difference = (($this->endMemory - $this->startMemory) / 1e6);
            return $this->difference;
        }
    }

    /**
     * Formats and raw calculation.
     *
     * @param int $accuracy The decimal precision for formatting (default 2).
     * @return mixed The formatted result.
     */
    public function getFormatted(int $accuracy = 2): ?mixed
    {
        if(($this->difference === 0) && (!$this->difference = $this->getResult())){
            return null;
        }
        $accuracy = max(0, min(6, $accuracy));
        $memory = $this->difference;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        foreach ($units as $unit) {
            if ($memory < 1024) {
                return number_format($memory, $accuracy) . ' ' . $unit;
            }
            $memory /= 1024;
        }
        return number_format($memory, $accuracy) . ' TB';
    }
}