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

namespace Essence\Exceptions;

use \Exception;
use \Throwable;

/**
 * BaseException
 * 
 * @package    Essence\System\Exceptions
 * @category   Exceptions
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class BaseException extends Exception
{
    /**
     * Additional custom data for debugging or logging.
     *
     * @var mixed
     */
    protected mixed $customData;
    protected bool  $abort = false;
    
    /**
     * Constructor for BaseException
     *
     * @param string $message   Exception message.
     * @param int    $code      Exception code.
     * @param mixed $customData Additional custom data for the exception.
     * @param Throwable|null $previous Optional. The previous throwable used for the exception chaining.
     */
    public function __construct(string $message = "", int $code = 0, bool $abort = false, mixed $customData = null, Throwable $previous = null)
    {
        $this->customData = $customData;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Retrieves the custom data provided to the exception.
     *
     * @return mixed The custom data, if any.
     */
    public function getCustomData(): mixed
    {
        return $this->customData;
    }



    public function isFatal():bool
    {
        return $this->abort;
    }
}