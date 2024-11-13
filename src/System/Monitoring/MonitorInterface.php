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

/**
 * Interface for monitoring components.
 * 
 * @package    Artex\Essence\Engine\System\Monitoring
 * @category   Monitoring
 * @access     public
 * @version    1.0.1
 * @since      1.0.0
 */
interface MonitorInterface
{
    /**
     * Start monitoring
     *
     * @param  integer $input
     * @return void
     */
    public function start(int $input=0): void;

    /**
     * Stop monitoring
     *
     * @param  integer $input
     * @return void
     */
    public function stop(): void;

    /**
     * Calculate results
     *
     * @param  integer $input
     * @return void
     */
    public function getResult(): mixed;
}