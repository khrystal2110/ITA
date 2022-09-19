<?php
class Session {

    // Method for checking if the session exists
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    // Method for getting a particular value
    public static function get($name) {
        return $_SESSION[$name];
    }

    // Method to delete the token 
    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]); // unseting the token if it exists
        }
    }

    public static function flash($name, $string = '') {
        if(self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
        
    }
    
    
}