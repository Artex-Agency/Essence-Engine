<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\System\Events\Hooks;

use \Essence\System\Events\Hooks\HookServices;

/**
 * Filter
 *
 * Description
 * 
 * @package    Essence\System\Events\Hooks
 * @category   Events
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class Actions extends HookServices
{
    /** @var Actions Contains the singleton instance.  */
    private static ?Actions $instance = null;

    /**
     * Invoke
     * A singleton method to retrieve the Essence master class instance.
     * 
     * @access public
     * @return Actions
     */
    public static function invoke(): Actions
    {
        return self::$instance ??= new Actions();
    }


    /**
    * Add an action to the specified hook.
    *
    * @param  string   $hook     The name of the hook to which the action should be added.
    * @param  callable $callback The callback function to be executed when the hook is triggered.
    * @param  int      $priority (Optional) The priority of the action. Default is 3.
    * @return void
    */
   public function add(string $hook, callable $callback, int $priority = 3): void 
   {
       $this->actions[$hook][$priority][] = $callback;
   }

   /**
    * Execute actions attached to a specific hook.
    *
    * @param string $hook The name of the hook whose actions should be executed.
    * @param mixed ...$args (Optional) Arguments passed to the callback functions.
    * @return void
    */
   public function doAction(string $hook, ...$args): void 
   {
       if (!isset($this->actions[$hook])) {
           return;
       }
       ksort($this->actions[$hook]);
       foreach ($this->actions[$hook] as $callbacks) {
           foreach ($callbacks as $callback) {
               callback($callback, $args);
           }
       }
   }

   /**
    * Delete an action from the specified hook.
    *
    * @param string $hook The name of the hook from which the action should be removed.
    * @param callable $callback The callback function to be removed.
    * @param int $priority    (Optional) The priority of the action. Default is null.
    * @return bool True if the action was found and removed, false otherwise.
    */
   public function deleteAction(string $hook, callable $callback, int $priority = null): bool 
   {
       if (!isset(self::$actions[$hook])) {
           return false;
       }
       if ($priority !== null && isset(self::$actions[$hook][$priority])) {
           $key = array_search($callback, self::$actions[$hook][$priority], true);
           if ($key !== false) {
               unset(self::$actions[$hook][$priority][$key]);
               return true;
           }
       } else {
           foreach (self::$actions[$hook] as $priority => $callbacks) {
               $key = array_search($callback, $callbacks, true);
               if ($key !== false) {
                   unset(self::$actions[$hook][$priority][$key]);
                   return true;
               }
           }
       }
       return false;
   }




    protected static function hookService_update(): void
    {

    }

    private function __construct(){}
}