<?php
require_once 'core/init.php';

$user = new User();
//echo $user->data()->username;
if($user->isLoggedIn()) {
    //echo 'Logged in';
?>
    <ul>
        <li><a href="profile.php">Profile</a></li> 
    </ul>
<?php

} else {
    echo '<p>You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>';
}