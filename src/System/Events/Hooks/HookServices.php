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

use \Essence\System\Events\EventServices;

/**
 * Hook Services
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
abstract class HookServices extends EventServices
{

    /** @var array<callable> $actions Custom user-defined error handlers. */
    protected array $actions = [];

    /** @var array<callable> $actions Custom user-defined error handlers. */
    protected array $filters = [];





    /**
     * Delete a hook and all its associated actions and filters.
     *
     * @param string $hook The name of the hook to be deleted.
     * @return void
     */
    public function deleteHook(string $hook): void 
    {
        unset($this->actions[$hook]);
        unset($this->filters[$hook]);
    }


    protected static function eventService_update(): void
    {

    }


    /**
     * Update services when language or locale changes.
     *
     * @return void
     */
    abstract protected function hookService_update(): void;

}