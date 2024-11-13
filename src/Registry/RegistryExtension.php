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

namespace Artex\Essence\Engine\Registry;

/**
 * Registry Extension Interface
 *
 * Description
 * 
 * @package    Artex\Essence\Engine\Registry
 * @category   Registry
 * @access     public
 * @version    1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
interface RegistryExtension
{
    /**
     * Extension toggle to enable/disable the extension.
     *
     * @var boolean 
     */
    private static bool $enabled;

    /**
     * Checks if the extension is enabled
     *
     * @return bool
     */
    private static function checkExtesion():bool;

    /**
     * Disables the extension
     *
     * @return void
     */
    public static function disable():void;

    /**
     * Reset extension properties
     *
     * @return void
     */
    protected static function resetExtesion():void
}