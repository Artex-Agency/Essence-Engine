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
    /** @var integer STARTUP The pre-boot stage flag. */
    const STARTUP  = 0;

    /** @var integer LOADING [flag] The initial loading stage. */
    const LOADING  = 1;

    /** @var integer SYSTEM The system boot stage flag. */
    const SYSTEM   = 2;

    /** @var integer ENGINE The engine boot stage flag. */
    const ENGINE      = 3;

    /** @var integer FRAMEWORK The framework boot stage flag. */
    const FRAMEWORK   = 4;

    /** @var integer APPLICATION The application boot stage flag. */
    const BOOTING = 5;

    /** @var integer BOOTED [flag] Application boot completed. */
    const BOOTED    =  6;

    protected int $stage = self::STARTUP;

    protected bool $booted = false;

    protected array $bootstraps = [];

    /**
     * Undocumented function
     */
    public function __construct()
    {
        echo '<h2>ESSENCE ENGINE: BOOTSTRAP</h2>';




        $this->stage = self::LOADING;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isReady(): bool
    {
        return (($stage > self::STARTUP && $stage < self::BOOTING) ? true : false);
    }


    public function add(BootstrapInterface $bootstrap): bool
    {
        if(!$this->isReady()){
            return false;
        }

    }

    public function call(callable $callback): bool
    {



    }

    public function load(string $filePath): bool
    {

    }



    public function isBooted(): bool
    {
        return $this->stage === self::FINISHED;
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    protected function getStage(): int
    {
        return $this->$stage;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function nextStage(): void
    {
        if($this->stage < self::FINISHED){
            $this->stage++;
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function endStage(): void
    {
        $this->stage = self::FINISHED;
    }
}