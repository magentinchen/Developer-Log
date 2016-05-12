<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_Model_Observer
{

    public function initDevlog($observer)
    {
        // only for init function out()
    }
    
}

if (!function_exists('out')) {
    
    function out($message, $title = '', $level = null, $file = 'dev.log', $forceLog = false)
    {
        return Dev::log($message, $title, $level, $file, $forceLog);
    }
    
}