<?php
class Redirect {
    public static function to($location = null) {
        if($location) {
            if(is_numeric($location)) { // numeric function because if we redirect into a path this is never going to be numeric
                switch($location) {
                    /*Instead of redirecting to 404.php we're going to stay on the same page(register.php),
                    set the HTTP headers to 404 not found and include the error file and exit*/
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        exit();
                    break;
                }
            }
            header('Location: ' . $location); // if location has been defined 
            exit();
        }
    }
}