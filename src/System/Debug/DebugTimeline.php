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

namespace Essence\System\Debug;

use \Essence\System\Debug\DebugService;
use \Essence\System\Debug\DebugInterface;

/**
 * Debug Timeline
 * 
 * @package    Essence\System\Debug
 * @category   Debug
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class DebugTimeline
{
    /**
     * Stores each checkpoint with timing and location details.
     *
     * @var array
     */
    private array $timeline = [];


    /**
     * Starts a new checkpoint.
     *
     * @param string $label Optional label for the checkpoint.
     * @return void
     */
    public function startCheckpoint(string $label = 'Unnamed'): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

        $this->timeline[$label] = [
            'start'     => hrtime(true),
            'file'      => $backtrace['file'] ?? 'unknown file',
            'line'      => $backtrace['line'] ?? 0,
            'end'       => null,
            'duration'  => null,
        ];
    }

    /**
     * Ends the checkpoint and calculates its duration.
     *
     * @param string $label The label for the checkpoint to stop.
     * @return void
     */
    public function endCheckpoint(string $label): void
    {
        if (!isset($this->timeline[$label])) {
            throw new InvalidArgumentException("Checkpoint '{$label}' does not exist.");
        }

        $this->timeline[$label]['end'] = hrtime(true);
        $this->timeline[$label]['duration'] = ($this->timeline[$label]['end'] - $this->timeline[$label]['start']) / 1e6; // Convert to ms
    }

    /**
     * Returns the timeline data for all checkpoints.
     *
     * @return array
     */
    public function getTimeline(): array
    {
        return $this->timeline;
    }
}