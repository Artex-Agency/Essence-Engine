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
class Variables
{
    /**
     * Use the $_ENV superglobal to contain environment variables.
     * If enabled, application environment variables will be injected
     * directly into the $_ENV superglobal.
     *
     * @var boolean
     */
    protected bool $useENV = true;

    /**
     * Use PHP built-in functions to modify environment variables. If 
     * enabled, application environment variables will be added, read, 
     * and removed using PHP's 'putenv' and 'setenv' functions.
     *
     * @var boolean
     */
    protected bool $usePHP = false;

    /**
     * Environment variables
     *
     * @var array|null $variables
     */
    protected array|null $variables = null;

    /**
     * Env file regex expression
     * 
     * Match key-value pairs, expecting keys and values separated by 
     * space, colon, or equals sign.
     *
     * @var string $regex
     */
    protected string $regex = '/^(?P<key>[a-zA-Z._\-]+[a-zA-Z0-9]{1})\s*[:=]\s*(?P<value>[^\r\n]*)$/m';

    /**
     * Environment Variables Constructor
     * 
     * Construct, configure, and optionally load a .env file.
     *
     * @param string $file Optional .env file path.
     * @param string $method The variable handling method, `env` will 
     *                       directly inject and manage the variables 
     *                       within the $_ENV superglobal, and `php`
     *                       will use the PHP built-in functionality 
     *                       such as `putenv` to store and manage the 
     *                       variables.
     */
    public function __construct(string $file='', string $method="env")
    {
        $method = strtolower(trim($method));
        switch($method){
            case 'php':
                $this->usePHP(true);
                $this->variables = [];
                break;
            case 'env':
            default:
                $this->useENV(true);
                $this->variables = ($_ENV ?? []);
                break;
        }
        if($file){
            $this->load($file);
        }
    }


    /**
     * Loads environment variable file
     *
     * @param string $file The .env file to load and parse.
     * @return boolean True if the variables were successfully loaded 
     *                 and parsed; otherwise, if the file is missing, 
     *                 corrupted, its contents formatting is invalid, 
     *                 or in the event of an error false will be 
     *                 returned.
     */
    public function load(string $file=''): bool
    {
        // Abort if file missing or corrupted
        if((!is_file($file)) || !$data = file_get_contents($file)){
            return false;
        }
        return $this->parse($data);
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
     * Get environment variable value
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
     * Get environment variable boolean value
     *
     * @param string $key The unique key to the variable.
     * @param bool $default The default value if no value is found.
     * @return boolean The boolean value of the variable if found; 
     *                 otherwise the default value is returned.
     */
    private function getBool(string $key, bool $default=false):bool
    {
        $value = $this->get($key, $default);
        return (('true' === $value || '1' === $value || 1 === $value) ? true : false);
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