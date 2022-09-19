<?php
class Input {

    // Method that checks if any data actually exists, so if input has been provided or not
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
            break;
            case 'get':
                return (!empty($_GET)) ? true : false;
            break;
            default:
                return false; // we want to assume that we should always get data
            break;
        }
    }
    

    // Method that is just going to retrieve an item
    // Instead of saying echo $_POST['username']; we can say this Input::get('username');
    public static function get($item) {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        }else if(isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }

}