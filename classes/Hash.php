<?php
class Hash {

    // Method for making a hash
    public static function make($string, $salt = '') {
       return hash('sha256', $string . $salt); 
    }

    // Method for making a salt
    public static function salt($length) {
        return bin2hex(random_bytes($length));
    }

    // Method for making a unique hash
    public static function unique() {
        return self::make(uniqid());
    }
}