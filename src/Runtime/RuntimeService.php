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
 */
declare(strict_types=1);

namespace Essence\Runtime;

use \is_null;
use \Essence\System\Benchmark;

/**
 * Runtime Service
 *
 *
 * @package    Essence\Runtime
 * @category   Configuration
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 */
abstract class RuntimeService
{
    protected bool $dev_mode = false;

    protected ?Benchmark $Benchmark = null;
    protected ?Benchmark $cycleTimer = null;

    protected array $cycles = [
        'init' => ['done': false, 'time': null, 'mark': 20],
        'boot' => ['done': false, 'time': null, 'mark': 20],
        'read' => ['done': false, 'time': null, 'mark': 20],
        'core' => ['done': false, 'time': null, 'mark': 20],
        'send' => ['done': false, 'time': null, 'mark': 20],
        'exit' => ['done': false, 'time': null, 'mark': 0]
    ];

    protected ?string $Current = '';

    protected string $Next = 'init';

    protected int $percent = 0;


    protected bool $expectBoot     = false;
    protected bool $expectOutput   = false;
    protected bool $expectShutdown = false;
    protected bool $expectExit     = false;


    protected function initCycle():bool
    {
        if(!is_null($this->Current)){
            return false;
        }
        $this->Current = $this->Next;
        $this->Current = $this->Next;
    }

    protected function nextCycle():bool
    {
        $this->exitCycle();

    }

    protected function skipCycle():bool
    {
        // Abort if not in development mode.
        if(!$this->dev_mode){
            return false;
        }


    }


    private function setNextCycle():string|false
    {


    }

    private function loadCycle():bool
    {
        timerStart()
        $this->cycles[$this->Current]['time'] = 
    }
    protected array $cycles = [
        'init' => ['done': false, 'time': null, 'mark': 20],
    private function exitCycle():bool
    {
        $this->cycles[];
        $this->cycleTime[];
    }


    private function timerStart():void
    {

    }


    private function timerStop():void
    {

    }

}