<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Core framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\System\Environment;

use \trim;

use \putenv;
use \is_file;
use \array_keys;
use \preg_match_all;
use \PREG_SET_ORDER;
use \file_get_contents;
use \Artex\Essence\Engine\System\Environment\Variables;

/**
 * Environment Variables
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\System\Environment
 * @category   System
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  © 2024 Artex Agency Inc.
 */
class Environment
{
    /**
     * The environment mode.
     *
     * @var string
     */
    protected bool $mode = 'dev';

    /**
     * Is developer mode?
     *
     * @var boolean
     */
    protected bool $devMode = true;

    /**
     * Is live environment?
     *
     * @var boolean
     */
    protected bool $isLive = false;

    /**
     * Enable Debug?
     *
     * @var boolean
     */
    protected bool $useDebug = false;

    /**
     * Is maintenance?
     *
     * @var boolean
     */
    protected bool $maintenance = false;

    /**
     * Is localhost?
     *
     * @var boolean
     */
    protected bool $isLocalhost = false;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected array $modeAlias = [
        'testing'     => ['test', 'tester', 'testing'],
        'development' => ['dev', 'developer', 'development'],
        'production'  => ['live', 'online', 'public', 'production'],
    ];



    /**
     * Environment Mode Constructor
     * 
     * Sets the environment modes
     */
    public function __construct(?Variables $Variables)
    {
        // Set defaults
        $this->defaults();

        // Set environment mode
        $this->setMode(
            $Variables->get(
                'ENVIRONMENT_MODE', 
                'development'
            )
        );

        // Set debug mode
        $this->useDebug = $Variables->getBool(
            'DEBUG_ENABLE', 
            false
        );

        // Set maintenance mode
        $this->maintenance = $Variables->getBool(
            'MAINTENANCE_MODE', 
            false
        );

        // Check if localhost
        $this->isLocalhost = false;
        if(isset($_SERVER['SERVER_NAME'])){
            $this->isLocalhost = in_array(
                $_SERVER['SERVER_NAME'], 
                ['localhost', '127.0.0.1']
            );
        }

    }

    /**
     * Reset environment properties
     *
     * @return void
     */
    public function reset():void
    {
        $this->mode = 'development';
        $this->devMode = true;
        $this->isLive = false;
        $this->useDebug = false;
        $this->maintenance = false;
        $this->isLocalhost = false;
        $this->modeAlias = [
            'testing'     => ['test', 'tester', 'testing'],
            'development' => ['dev', 'developer', 'development'],
            'production'  => ['live', 'online', 'public', 'production'],
        ];
    }



    public function setMode(string $mode)
    {
        // Sanitize mode
        $mode = strtolower(trim($mode));

        // Canonical name detected.
        if(array_key_exists($this->modeAlias[$mode])){
            $this->mode = $mode;
        }

        // Get canonical name from possible alias.
        if(!array_key_exists($this->modeAlias[$mode])){
            foreach ($this->modeAlias as $canonical => $alias) {
                if (in_array($mode, $aliases, true)) {
                    $this->mode = $canonical;
                }
            }
            unset($canonical, $alias);
        }

        // Set dev mode
        $this->devMode = (($this->mode === 'development') ? true : false);
    }

    /**
     * Parse vars from env file contents.
     * 
     * Parses the file data contents from the .env file into an array 
     * of key => value variables and sets them 
     *
     * @param string $data The data contents from the environment file.
     * @return boolean True if valid env format parsed; otherwise false.
     */
    private function parse(string $data):bool
    {
        // Abort if invalid data or no matches.
        if (!$data || !preg_match_all($this->regex, $data, $matches, PREG_SET_ORDER)) {
            return false;
        }

        // Loop variable matches
        foreach ($matches as $match) {

            // Attempt to add variable
            $this->addVar(
                $match['key'],
                trim($match['value'])
            );
        }
        return true;
    }

    /**
     * Get environment variable
     *
     * @param string $key The unique key to the variable.
     * @param mixed $default The default value if no value is found.
     * @return mixed The value of the variable if found; otherwise the 
     *               default value is returned.
     */
    private function get(string $key, mixed $default=null):mixed
    {
        if($this->usePHP && isset($this->variables[$key])){
            return ($this->variables[$key] ?? $default);
        }
        return ($_ENV[$key] ?? default);
    }

    /**
     * Add an environment variable
     *
     * @param string $key The unique key to the variable.
     * @param mixed $value The variable value.
     * @return void
     */
    private function addVar(string $key, mixed $value):void
    {
        // Require key and value
        if ($key && $value === '') {
            return;
        }

        // Add direct to ENV
        if($this->useENV){
            $_ENV[$key] = $value;
            return;
        }

        // Adding to collection
        $this->variables[$key] = $value;
        putenv("$key=$value");
    }

    /**
     * Remove environment variable by Key
     *
     * @param string $key The unique key to the variable.
     * @return void
     */
    private function remVar(string $key):void
    {
        // Remove with PHP
        if($this->usePHP && isset($this->variables[$key])){
            putenv($key);
            unset($this->variables[$key]);
            return;
        }

        // Remove from ENV
        if(isset($_ENV[$key])){
            unset($_ENV[$key]);
            return;
        }
    }

    /**
     * Clear Environment Variables
     *
     * @return void
     */
    private function clear():void
    {
        // Clear ENV
        if($this->useENV){
            $_ENV[$key] = [];
            return;
        }

        // Loop variable keys and remove using putenv
        $keys = array_keys($this->variables);
        foreach($keys as $key){
            putenv($key);
        }

        // Reset collection
        $this->variables = [];
    }

    /**
     * Use the $_ENV superglobal setting
     * 
     * Enables/disables the direct injection of environment variables 
     * into the $_ENV superglobal.
     *
     * @param boolean $enabled
     * @return void
     */
    public function useENV(bool $enabled=true):void
    {
        $this->useENV = $enabled;
        $this->usePHP = (($enabled === true) ? false : true);
    }

    /**
     * Use the PHP Environment superglobal setting
     * 
     * Enables/disables the direct injection of environment variables 
     * into the $_ENV superglobal.
     *
     * @param boolean $enabled
     * @return void
     */
    public function usePHP(bool $enabled=true):void
    {
        $this->usePHP = $enabled;
        $this->useENV = (($enabled === true) ? false : true);
    }

    /**
     * Reset ENV variables
     *
     * @return void
     */
    public function reset():void
    {
        $this->clear();
        if($this->useENV){
            $_ENV = $this->variables;
            $this->variables = [];
        }
    }

    /**
     * Destructor on close
     * 
     * Reset ENV variables.
     */
    public function __destruct()
    {
        $this->reset();
    }
}