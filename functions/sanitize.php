<?php
/* escape function for outputting data that's been stored in a database*/
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

/* You can use it globally without having to initialize a class,
therefore you can escape any data in any situation just by doing escape($string);
rather that $sanitize = new Sanitize; $escaped = $sanitize->escape($string); */