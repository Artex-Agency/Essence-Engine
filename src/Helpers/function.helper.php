<?php declare(strict_types=1);

# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * Function Helpers
 * 
 * A collection of utility functions for managing and executing 
 * callables (functions, closures, methods) in a flexible and 
 * efficient manner.
 *
 * This file is part of the Artex Essence meta-framework.
 *
 * @package    Essence\Helpers
 * @category   Helpers
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */


/**
 * Executes a callable with provided parameters.
 *
 * A versatile handler for executing callables (*functions, closures, methods*) 
 * with support for both instance and static methods. This function is optimized 
 * for direct execution and avoids the overhead associated with `call_user_func`.
 *
 * Supported callables include:
 * - A function name as a string.
 * - A closure (anonymous function).
 * - An array representing an instance method (e.g., [$object, 'methodName']).
 * - An array representing a static method (e.g., ['ClassName', 'staticMethod']).
 *
 * @param callable $callback The callable to be executed. This can be a function name, 
 *                           a closure, or an array representing a method.
 * @param array    $params   An optional array of parameters to pass to the callable. Defaults to an empty array.
 *
 * @return mixed Returns the result of the executed callable.
 *
 * @throws InvalidArgumentException If the provided callable is invalid.
 *
 * @example
 * ```php
 * // Example 1: Invoke a global function
 * function myFunction($x) { return $x * 2; }
 * echo callback('myFunction', [5]); // Outputs: 10
 *
 * // Example 2: Invoke a closure
 * $closure = function ($x) { return $x + 10; };
 * echo callback($closure, [5]); // Outputs: 15
 *
 * // Example 3: Invoke an instance method
 * $object = new MyClass();
 * echo callback([$object, 'instanceMethod'], [5]);
 *
 * // Example 4: Invoke a static method
 * echo callback(['MyClass', 'staticMethod'], [5]);
 * ```
 */
function callback(callable $callback, array $params = []): mixed
{
    // Handle array-based callables (instance or static methods)
    if (is_array($callback)) {
        [$class, $method] = $callback;

        return is_object($class)
            ? $class->$method(...$params) // Instance method
            : $class::$method(...$params); // Static method
    }

    // Handle direct function or closure calls
    return $callback(...$params);
}