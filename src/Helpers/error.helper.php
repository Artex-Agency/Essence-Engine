<?php
    function ess_deprecated( string $message)
    {
        trigger_error($message, E_USER_DEPRECATED);
    }

function ess_warning( string $message)
{
    trigger_error($message, E_USER_WARNING);
}
function ess_notice( string $message)
{
    trigger_error($message, E_USER_NOTICE);
}



function ess_system_error( string $message)
{

    trigger_error($message, E_USER_ERROR);
}


function getLocation( string $file='', int $line = 0 )
{
    // Get debug backtrace
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);
    if((!$depth = count($backtrace)) || ($depth <= 1)) {
        // Return default path data
        return [ 'file' => $file, 'line' => $line ];
    }

    // Loop backtrace
    for($x = 1; $x < $depth; $x++) {

        // Get function
        $function = ($backtrace[$x]['function'] ?? '');
        if(!$function) { 
            continue; 
        }

        // Check custom alert triggers 
        if (in_array($function, ['ess_warning', 'ess_notice', 'ess_deprecated'])) {
            // Return adjusted path data
            return [
                'file' => $backtrace[$x]['file'],
                'line' => $backtrace[$x]['line']
            ];
        } 
        // Check custom error triggers 
        if( (substr($function, 0, 4) === 'ess_') && 
            (substr($function, -6) === '_error') &&
            (strlen($function) > 15)) {
            // Return adjusted path data
            return [
                'file' => $backtrace[$x]['file'],
                'line' => $backtrace[$x]['line']
            ];
        }
    }
    // Return default
    return [ 'file' => $file, 'line' => $line ];
}