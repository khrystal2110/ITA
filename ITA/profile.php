<?php
require_once 'core/init.php';

/*$user = DB::getInstance()->update('users', 3, array(
    
    'password' => 'newpassword',
    'name' => 'Amra Pezer'
    
));

if(!$user->count()) {
    echo 'No user';
}else{
    echo $user->first()->username;   
}
*/

/*if(Session::exists('success')) {
    //echo '<p></p>';
    echo Session::flash('success');
}
*/

if(Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

//echo Session::get(Config::get('session/session_name'));

$user = new User();
//echo $user->data()->username;
if($user->isLoggedIn()) {
    //echo 'Logged in';
?>
    <ul>
        <li><a href="logout.php">Log out</a></li>
        <li><a href="update.php">Update details</a></li>
        <li><a href="changepassword.php">Change password</a></li>
    </ul>
<?php

    if($user->hasPermission('administrator')) {
        echo '<p> You are an administrator! </p>';
    }

} else {
    echo '<p>You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>';
}




