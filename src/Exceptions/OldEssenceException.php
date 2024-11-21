<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Exceptions;

use \Exception;
use \Throwable;


$ESS_ERRORS = [
    0   => [
        'flag' => 'ESS_ERROR_UNKNOWN',
        'name' => 'Unknown Error',
        'text' => 'An unregistered system error.'
    ],
    1   => [
        'flag' => 'ESS_ERROR_GENERAL',
        'name' => 'General Error',
        'text' => 'A low-level system error.'
    ],
    3   => [
        'flag' => 'ESS_ERROR_MISSING',
        'name' => 'Filepath Error',
        'text' => 'A required file or directory is missing.'
    ],
    6   => [
        'flag' => 'ESS_ERROR_CORRUPT',
        'name' => 'File Error',
        'text' => 'A required file is damaged or corrupted.'
    ],
    9   => [
        'flag' => 'ESS_ERROR_CONFIG',
        'name' => 'Config Error',
        'text' => 'description.'
    ],
    12  => [
        'flag' => 'ESS_ERROR_SYSTEM',
        'name' => 'System Error',
        'text' => 'description.'
    ],
    15  => [
        'flag' => 'ESS_ERROR_SERVER',
        'name' => 'Server Error',
        'text' => 'description.'
    ],
    18  => [
        'flag' => 'ESS_ERROR_FRAMEWORK',
        'name' => 'Core Error',
        'text' => 'description.'
    ],
    21  => [
        'flag' => 'ESS_ERROR_APPLICATION',
        'name' => 'Application Error',
        'text' => 'description.'
    ],
    24  => [
        'flag' => 'ESS_ERROR_APPLICATION',
        'name' => 'Application Error',
        'text' => 'description.'
    ],
    27  => [
        'flag' => 'ESS_ERROR_FORBIDDEN',
        'name' => 'Access Denied',
        'text' => 'Unauthorized access denied!.'
    ],
    30  => [
        'flag' => 'ESS_ERROR_SECURED',
        'name' => 'Illegal Attempt',
        'text' => 'Security protocols triggered!.'
    ],
    33  => [
        'flag' => 'ESS_ERROR_SECURED',
        'name' => 'Illegal Attempt',
        'text' => 'Security protocols triggered!.'
    ],
];

/** @var int ESS_ERROR_UNKNOWN Essence framework unknown error. */
define('ESS_ERROR_UNKNOWN', 0);

/** @var int ESS_ERROR_GENERAL Essence general framework error. */
define('ESS_ERROR_GENERAL', 1);

/** @var int ESS_ERROR_MISSING Essence framework missing file or directory. */
define('ESS_ERROR_MISSING', 3);

/** @var int ESS_ERROR_CORRUPT Essence framework file corrupted. */
define('ESS_ERROR_CORRUPT', 6);

/** @var int ESS_ERROR_CONFIG Essence framework system configuration error. */
define('ESS_ERROR_CONFIG',  9);

/** @var int ESS_ERROR_SYSTEM Essence framework internal system error. */
define('ESS_ERROR_SYSTEM',  11);

/** @var int ESS_ERROR_SERVER Essence framework server error. */
define('ESS_ERROR_SERVER', 13);

/** @var int ESS_ERROR_TIMEOUT Essence framework rate limit timeout. */
define('ESS_ERROR_TIMEOUT', 15);

/** @var int ESS_ERROR_ACTION Essence framework action denied. */
define('ESS_ERROR_ACTION', 1);

/** @var int ESS_ERROR_ACCESS Essence framework access denied. */
define('ESS_ERROR_ACCESS', 1);

/** @var int ESS_ERROR_SUSPECT Essence framework suspicious activity blocked! */
define('ESS_ERROR_SUSPECT', 93);

/** @var int ESS_ERROR_ILLEGAL Essence framework illegal attempt blocked! */
define('ESS_ERROR_ILLEGAL', 96);

/** @var int ESS_ERROR_SECURED Essence framework security protocols triggered! */
define('ESS_ERROR_SECURED', 99);





class EssenceException extends Exception
{

   /** @var integer Essence code */
   protected $essCode = 0;

   /** @var integer Essence code index */
   protected $essLevel = 0;


   /** @var string Essence error type */
   protected $essGroup  = 'Unknown';

   /** @var string Essence error name */
   protected $essLabel  = 'Unknown';

   /** @var boolean Essence fatal error */
   protected $essfatal  = false;

    /**
     * Captures framework core exceptions
     *
     * @param string  $errText
     * @param integer $errCode
     * @param string  $errFile
     * @param integer $errLine
     * @param Throwable|null $previous
     */
    public function __construct(string $errText='', int $errCode=0, bool $trace = false, string $errFile = (__FILE__), int $eLine = (__LINE__), Throwable $previous = null)
    {
        if(true === $trace) {
            $trace   = $this->getTrace();
            $trace   = $trace[0];
            $errFile = (($trace['file']) ?? 'Unknown file');
            $errLine = (($trace['line']) ?? 0);
            $this->file = $errFile;
            $this->line = $errLine;
        }

        parent::__construct($errText, $errCode, $previous);
    }

    /**
     * Get error level type
     *
     * @return string Returns the error type group name
     */
    public function getType():string
    {
        return 'System Error';
    }

    /**
     * Get error level name
     * 
     * @return string Returns the error level text name
     */
    public function getName():string
    {
        return 'Unknown';
    }

    /**
     * Get exception flag
     *
     * @return int Returns the exception logger flag code
     */
    public function getFlag():int
    {
        $code = (int)$this->getCode();
        for($i = 1; $i <= 9; $i++){
            $min = ($i * 100);
            $max = ($min + 100);
            if(($code >= $min) && ($code < $max)){
                return $i;
            }
            unset($min, $max);
        }
        return 0;
    }

}