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

namespace Essence\System\Event\Hooks;

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
class Filter extends HookServices
{
    /** @var Filter Contains the singleton instance.  */
    private static ?Filter $instance = null;

    /**
     * Invoke
     * A singleton method to retrieve the Essence master class instance.
     * 
     * @access public
     * @return Essence
     */
    public static function invoke(): Essence
    {
        return self::$instance ??= new Essence();
    }


    /**
     * Add a filter to the specified hook.
     *
     * @param string $hook     The name of the hook to which the filter should be added.
     * @param callable $callback The callback function to be executed when the hook is triggered.
     * @param int $priority    (Optional) The priority of the filter. Default is 10.
     * @return void
     */
    public function add(string $hook, callable $callback, int $priority = 10): void 
    {
        $this->filters[$hook][$priority][] = $callback;
    }

    /**
     * Apply filters attached to a specific hook.
     *
     * @param string $hook The name of the hook whose filters should be applied.
     * @param mixed $value The value to be filtered.
     * @param mixed ...$args (Optional) Additional arguments passed to the callback functions.
     * @return mixed The filtered value.
     */
    public function apply(string $hook, $value, ...$args) 
    {
        if (!isset($this->$filters[$hook])) {
            return $value;
        }
        ksort($this->filters[$hook]);
        foreach ($this->filters[$hook] as $callbacks) {
            foreach ($callbacks as $callback) {
                $value = callback($callback, array_merge([$value], $args));
            }
        }
        return $value;
    }





    public static function setInstances(string $key, $value): void
    {
        self::$instances[$key] = $value;
    }



    protected static function hookService_update(): void
    {

    }


}