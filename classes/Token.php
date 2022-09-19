<?php

/* Token class allows us to generate a token and check if a token is valid and exists and then delete that token
   Generating a token for each refresh of the page which only that page then knows so another user somewhere else
   can't direct into that page because the token will always be checked */

class Token {

    public static function generate() {
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    // Method for getting a token from our session and check if it is the same as the token that's been defined in the form
    public static function check($token) {
        $tokenName = Config::get('session/token_name');

        /*Checking if the session exists with the token name that we've defined in our config
        Then checking whether the token that's applied to our check method equals the session that's been stored by the user */

        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}