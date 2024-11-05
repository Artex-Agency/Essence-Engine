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

namespace Artex\Essence\Engine\Bootstrap;


/**
 * Bootstrap
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\Bootstrap
 * @category   Bootstrap
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
class Bootstrap
{
    /** @var integer ONLOAD [FLAG] The pre-boot initial loading state. */
    const ONLOAD = 0;

    /** @var integer SYSTEM [FLAG] The system environment and runtime boot state. */
    const SYSTEM = 1;

    /** @var integer CONFIG [FLAG] The system configuration state. */
    const CONFIG = 2;

    /** @var integer LOADER [FLAG] The application framework loading state. */
    const LOADER = 3;

    /** @var integer EXTEND [FLAG] The extended bootstrap collection state. */
    const EXTEND = 4;

    /** @var integer LOADED [FLAG] The bootstrap load state. */
    const LOADED = 5;

    /** @var integer BOOTED [FLAG] The bootstrap completed state. */
    const BOOTED =  6;

    /** @var integer $state The current state of the bootstrap process. */
    protected int $state = self::LOADING;

    /** @var boolean $enabled Bootstrap toggle to enable/disable the process. */
    protected int $enabled = true;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected array $bootstrappers = [];

    /**
     * Undocumented variable
     *
     * @var ?Runtime
     */
    protected ?Runtime $Runtime = null;


    /**
     * Constructor
     */
    public function __construct(?Runtime $Runtime=null)
    {
        echo '<h2>ESSENCE ENGINE: BOOTSTRAP</h2>';


        // Set runtime
        $this->Runtime = (($Runtime) ? $Runtime : null);

        // Advance the state.
        $this->state = $this->setNext();
    }


    public function loadSystem(): bool
    {
        // If runtime missing, advance state and abort.
        if(!$this->Runtime){
            $this->setNext();
            return false;
        }

        // TODO: DO RUNTIME SHIT HERE.

/*
        // Configure Runtime
        if(!$this->Runtime->configure()){
            return false;
        }

        // Load Environment
        if(!$this->Runtime->loadEnvironment()){
            return false;
        }

        // Load Environment
        if(!$this->Runtime->loadEnvironment()){
            return false;
        }
            
            $this->Runtime->loadEnvironment();
*/

        // Trigger runtime load event
        $this->Runtime->onLoad();

        // Advance the state.
        $this->setNext();
    }






    /**
     * Add a custom bootstrap extension
     * 
     * Adds a custom bootstrap extension class to the bootstrap loading 
     * protocol. 
     *
     * @param BootstrapInterface $bootstrap Custom bootstrap class.
     * @param mixed ...$args Optional class constructor arguments.
     * @return boolean True if class is valid, and bootstrap state is
     *                 ready; false otherwise.]
     */
    public function add(BootstrapInterface|string $bootstrap, ...$args): bool
    {
        // Check if the system is ready
        if (!$this->isReady()) {
            return false;
        }
    
        // Check if $bootstrap is an instance or class name
        if ($bootstrap instanceof BootstrapInterface) {
            // Already an instance
            $this->bootstrappers[] = $bootstrap;
            return true;
        }

        if (is_string($bootstrap) && class_exists($bootstrap)) {
            // Instantiate with provided arguments
            $this->bootstrappers[] = new $bootstrap(...$args);
            return true;
        }

        // Invalid class
        return false;        
    }

    /**
     * Run bootstrap
     *
     * Runs the bootstrap loading process.
     * 
     * @return void
     */
    public function run(): void
    {
        // Check ready state
        if(!$this->isReady()){
            return;
        }

        // Set loaded state.
        $this->seLoaded();

        // Loop through bootstrappers with exception handling
        foreach ($this->bootstrappers as $bootstrap) {
            try {
                $bootstrap->load();
                $bootstrap->loaded();
            } catch (\Throwable $e) {
                // Do custom error exception here..
            }
        }
    }



    /**
     * Checks if bootstrap is ready
     *
     * Checks if bootstrap is active, and is currently listening for 
     * custom bootstraps extension files.
     * 
     * @return boolean True if bootstrap is ready; otherwise false.
     */
    public function isReady(): bool
    {
        return (($this->hasStarted() && $this->isLoading() && $this->enabled) ? true : false);
    }

    /**
     * Checks if bootstrap is complete
     *
     * Checks if the bootstrap process has been completed.
     * 
     * @return boolean True if the bootstrap process has been completed; 
     *                 otherwise false.
     */
    public function isComplete(): bool
    {
        return $this->hasBooted();
    }

    /**
     * Get bootstrap state
     *
     * Gets the current bootstrap state.
     * 
     * @return integer The current bootstrap numeric state.
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * Stop bootstrap
     *
     * Stops the bootstrap process and completes the state.
     * 
     * @return void
     */
    public function stop(): void
    {
        $this->$this->enabled = false;
        $this->setComplete();
    }















    /**
     * Undocumented function
     *
     * @param BootstrapInterface $bootstrap
     * @param mixed ...$args
     * @return boolean
     */
    public function bootSystem(): bool
    {
        if($this->isReady() && ($this->state === self::STARTED)){
            $this->bootstrappers[] = new $bootstrap($args);
            return true;
        }
        return false;
    }







    public function call(callable $callback): bool
    {



    }




    /**
     * Advance to the next state
     *
     * Increments the current state to move the bootstrapping process 
     * one step forward towards completion.
     * 
     * @return void
     */
    protected function onBooted(): void
    {
        if($this->hasBooted() && $this->Runtime){
            $this->Runtime->onBoot();
            $this->Runtime = null;
        }
    }

    /**
     * Advance to the next state
     *
     * Increments the current state to move the bootstrapping process 
     * one step forward towards completion.
     * 
     * @return void
     */
    protected function nextState(): void
    {
        if(!$this->hasBooted()){
            $this->state++;
            $this->onBooted();
        }
    }

    /**
     * Set to complete
     *
     * Forcefully sets the bootstrap process state to complete.
     * 
     * @return void
     */
    protected function setComplete(): void
    {
        $this->state = self::BOOTED;
        $this->onBooted();
    }

    /**
     * Set to loaded
     *
     * Forcefully sets the bootstrap process state to loaded.
     * 
     * @return void
     */
    protected function seLoaded(): void
    {
        $this->state = self::LOADED;
    }

    /**
     * Bootstrap has started
     * 
     * Checks if the bootstrap process has started.
     *
     * @return boolean True if the bootstrap proess has started; 
     *                 otherwise false.
     */
    private function hasStarted(): bool
    {
        return $this->state > self::ONLOAD;
    }

    /**
     * Bootstrap is loading
     * 
     * Checks if the bootstrap process is still loading.
     *
     * @return boolean True if the bootstrap process is still loading;
     *                 otherwise false.
     */
    private function isLoading(): bool
    {
        return $this->state < self::LOADED;
    }

    /**
     * Bootstrap has loaded
     * 
     * Checks if the bootstrap process has loaded.
     *
     * @return boolean True if the bootstrap process has been loaded;
     *                 otherwise false.
     */
    private function hasLoaded(): bool
    {
        return $this->state >= self::LOADED;
    }

    /**
     * Bootstrap has booted
     * 
     * Checks if the bootstrap process has been completed.
     *
     * @return boolean True if the bootstrap process has been completed;
     *                 otherwise false.
     */
    private function hasBooted(): bool
    {
        return $this->state >= self::BOOTED;
    }

    /**
     * Reset
     * 
     * Resets the state and clears the custom bootstrap loader list 
     * collection.
     *
     * @return void
     */
    protected function reset(): void
    {
        $this->state = 0;
        $this->bootstrappers = [];
    }
}