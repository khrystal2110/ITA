<?php

/* 
    *Class for Config*
-Essentially it's going to allow us to access our config in easy way using forward slash
-End result is we want to say echo Config::get('mysql/host'); thats going to output localhost
-Whatever you add in your config you'll be able to access
*/

class Config{
    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach($path as $bit) {
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config;
        }

        return false;
    }
}


/* For the first loop we're going to say does mysql exists inside $GLOBALS['config']
if it does we're setting 'config' to mysql so then for the next loop does host exist in config
because now config equals mysql and then we end up with localhost('host' => 'localhost') */
