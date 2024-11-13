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

use \hrtime;
use \number_format;
use \Artex\Essence\Engine\System\Monitoring\MonitorInterface;

/**
 * Timer
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
class Timer implements MonitorInterface
{
    /**
     * @var int The time when the timer was started.
     */
    private int $startTime = 0;

    /**
     * @var int The calculated timer duration.
     */
    private int $endTime = 0;

    /**
     * @var int The time when the timer was stopped.
     */
    private int $duration = 0;

    /**
     * @var int The time when the timer was stopped.
     */
    private int $formatted = 0;


    /**
     * Starts the timer by setting the start time.
     *
     * @param integer $input Sets the start time. (optional)
     * @return void
     */
    public function start(int $input=0): void
    {
        if($this->startTime === 0){
            $this->startTime = (($input > 0) ? $input : hrtime(true));
        }
    }

    /**
     * Stops the timer by setting the end time.
     *
     * @return void
     */
    public function stop(): void
    {
        if($this->startTime > 0 && $this->endTime === 0){
            $this->endTime = hrtime(true);
        }
    }

    /**
     * Calculates and returns the duration of the timer.
     *
     * @return mixed The duration in nano seconds.
     */
    public function getResult(): ?mixed
    {
        if($this->endTime > 0 && $this->duration === 0){
            $this->duration = (($this->endTime - $this->startTime));
            return $this->duration;
        }
    }

    /**
     * Calculates and returns the duration of the timer.
     *
     * @param int $accuracy The decimal precision for formatting (default 2).
     * @return mixed The formatted duration.
     */
    public function getFormatted(int $accuracy = 2): ?mixed
    {
        if(($this->duration === 0) && (!$this->duration = $this->getResult())){
            return null;
        }
        $accuracy = max(0, min(6, $accuracy));
        $duration = ($this->duration / 1e6);

        // time units
        $units = [
            'ms'    => 1000,
            'secs'  => 60,
            'mins'  => 60,
            'hours' => 24,
            'days'  => 0
        ]; d
        foreach ($units as $unit => $max) {
            if (($max === 0) || ($duration < $max)) {
                return (number_format($duration, $accuracy, '.') . ' ' . $unit);
            }
            $duration /= $max;

        }
        return number_format($duration, $accuracy, '.') . ' days';
    }
}