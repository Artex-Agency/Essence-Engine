<?php 
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * Directory Helpers
 * 
 * Description
 *
 * This file is part of the Artex Essence Engine and meta-framework.
 *
 * @package    Artex\Essence\Engine\Helpers
 * @category   Helpers
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/engine/ Project Website
 * @link       https://artexsoftware.com/ Artex Software
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
declare(strict_types=1);


function isDir(string $path):bool
{
    return is_dir($path);
}

function listFilesByPath(string $path, array $disclude=['.','..']):array
{
    return array_diff(scandir($path), $disclude);
}

function listFolders(string $path, array $disclude=['.','..']):array
{
    $paths = [];
    $files = listFilesByPath($path, $disclude);
    foreach($files as $file){
        if(is_dir($path.$file)){
            $paths[$file] = $path.$file;
        }
    }
    return ($paths ?? []);
}