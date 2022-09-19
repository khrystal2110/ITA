<?php
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'db' => 'ita'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

/* Instead of using this:
require_once 'classes/Config.php';
require_once 'classes/Cookie.php';
require_once 'classes/DB.php';
We are gonna use function in PHP spl_autoload_register()*/

spl_autoload_register(function($class){
    // Instead of using require_once 'classes/DB.php'
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    //echo 'User asked to be remembered';
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if($hashCheck->count()) {
        //echo 'Hash matches, log user in.';
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}