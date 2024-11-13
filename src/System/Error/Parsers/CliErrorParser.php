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

namespace Artex\Essence\Engine\System\Error\Parsers;

use Throwable;

/**
 * CLI Error Parser
 *
 * @package    Artex\Essence\Engine\System\Error\Parsers
 * @category   Error Management
 * @version    1.0.1
 * @since      1.0.0
 * @access     public
 */
class CliErrorParser implements ErrorParserInterface
{
    public function parse(Throwable $exception): string
    {
        return sprintf(
            "\n\033[1;31mError:\033[0m %s\n\033[1;33mFile:\033[0m %s \033[1;33mLine:\033[0m %d\n\033[1;34mTrace:\033[0m\n%s\n\n",
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
    }
}