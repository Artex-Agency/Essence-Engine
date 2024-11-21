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

use \register_shutdown_function;

/**
 * Runtime
 *
 *
 * @package    Essence\Runtime
 * @category   Configuration
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 */
class Runtime
{

/*
 * METHOD NAMING
 * ------------------
 *  do[method] : Runtime cycles. `example: doBoot()`.
 *  on[method] : Event handlers. `example: onShutdown()`.
 *  go[method] : Unexpected cycle break.
 *  in[method] : 
 *  at[method] :  
*/

    protected bool $expectBooted   = false;
    protected bool $expectOutput   = false;
    protected bool $expectOutput   = false;
    protected bool $expectShutdown = false;
    protected bool $expectExit     = false;


    protected function expect($flag)
    {
        switch(strtolower($flag)){
            case 'boot':
            case 'booted':
                $this->expectBooted = true;
                break;
            case 'output':
                $this->expectShutdown = true;
                break;
            case 'shutdown':
                $this->expectShutdown = true;
                break;
            case 'exit':
                $this->expectExit = true;
                break;
        }
    }

    public function expected(string $flag): bool
    {
        switch(strtolower($flag)){
            case 'boot':
            case 'booted':    return $this->expectBooted;
            case 'shutdown':  return $this->expectShutdown;
            case 'exit':      return $this->expectExit;
        }
    }

    /**
     * Constructor
     *
     */
    public function __construct()
    /* INIT: PROCESS FLOW
     * =========================
     * 1: Load Environment
     * 2: Error Handling
     * 3: Load Events
     * 4: System Monitoring
     * 5: Load Engine 
     * 6: Load Kernel
     * =========================
     */{

        // Register system shutdown override event
        register_shutdown_function([$this, 'onShutdown']);

        // Boot System
        $this->boot();
    }

    /**
     * Boot
     */
    protected function boot()
    /* BOOT: PROCESS FLOW
     * =========================
     * 1: Load Bootstrap
     * 2: Load Services
     * 3: Load Cycle :: (for the request/intferface type)
     * 4: Boot Framework
     * 5: Boot Application
     * 6: TBD
     * =========================
     */{

        return $this->Request();
    }

    /**
     * Request
     *
     */
    protected function Request()
    /* BOOT: PROCESS FLOW
     * =========================
     * 1: Handle Request.
     * 2: Handle Auth.
     * 3: Load Roles
     * 4: Boot Framework
     * 5: Boot Application
     * 6: System Monitor
     * 4: Hook Shutdown
     * =========================
     */{
    /* PROCESS
     * =========================
     * 1: Request
     * 2: Auth
     * 3: Roles
     * 3: Routes
     * 4: Gates
     * =========================
     */
    
    /* 
    ------------------------------


    ------------------------------
    */
        // ->RUN ESSENCE      :: RUNTIME %{waits}%
        return $this->startCycle();
    }

    /**
     * startCycle
     *
     */
    protected function startCycle()
    {
        // ->RUN ESSENCE      :: RUNTIME %{waits}%
        // ->ESS->RUN CORE    :: ESSENCE %{waits}%
        // ->CORE->RUN APP
        // %[CORE <==> APP ]% :  
        // ::APP->{response}->CORE
        // CORE->{response}->ESS
        // ESS->%{RESPONSE}%
        //
        // ### RETURN Respond(%{RESPONSE}%)
        return $this->Respond();
    }

    /**
     * Respond
     *
     */
    protected function Respond()
    {
        // HEADERS
        // REQUEST
        // ROUTES
        // GATES
        return $this->Respond();
    }

    /**
     * Terminate
     *
     */
    protected function beginExit()
    {
        // Check Last Error
        // If Error Run Pre-Error Events
        // ELSE 
        // Check if shutdown expected
        // (false) // Trigger Unepected Shutdown Event
        // --------
        // Trigger Shutdown Event
    }
/*
!#=> INIT
#
###=> BOOT
#
#=> REQUEST
#
#=> PEAK (APPLICATION)
#
#=> RESPOND
#
###=> EXIT
#
!#=> EXIT
*/








    /**
     * Terminate
     *
     */
    protected function onShutdown()
    {
        // Check Last Error
        // If Error Run Pre-Error Events
        // ELSE 
        // Check if shutdown expected
        // (false) // Trigger Unepected Shutdown Event
        // --------
        // Trigger Shutdown Event
    }

    /**
     * Terminate
     *
     */
    protected function Terminate()
    {

    }

    /**
     * Destructor
     *
     */
    public function __destruct()
    {

    }
}