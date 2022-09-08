<?php

/* Database class using singleton pattern 
Allowing us to get the an instance of uur database if its already been instantiated
We dont have to keep connecting to our database again and again on each page
We can just use the database on the fly as we want */

/* 
$users = DB::getInstance()->query('SELECT username FROM users');
if($users->count()){
    foreach($users as $user){
        echo $user->username;
    }
} */


class DB{
    private static $_instance = null;
    private $_pdo,
            $_query, 
            $_error = false, 
            $_results, 
            $_count = 0;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
            //echo 'Connected';
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array()) {
        $this->_error = false; // so we know that we're not returning an error for a previous query
        if($this->_query = $this->_pdo->prepare($sql)) { //check if query has been prepared properly
            // echo " Success";

            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    // echo $param;
                    $this->_query->bindValue($x, $param);
                    $x++;
                    // echo $x."<br>";
                }
            }
            
            if($this->_query->execute()) {
                // echo "Success";
                //if the query has executed successfully we want to store the result set:
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        return $this; // return current object that we are working on 
    }

    /* --public function action()--
    Allows us to perform specific action like select or delete and we are gonna define a table and then define a specific field with a specific value,
    this can be: does it equal, is it greater then ...
    Instead of this:
        $user = DB::getInstance()->query("SELECT * FROM users WHERE username= ?", array('demir'));
    We can do this:
        $user = DB::getInstance()->get('users', array('username','=','demir'));
    */

    public function action($action, $table, $where = array()) {
        if(count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)){
                // Instead of $sql = "SELECT * FROM users WHERE username='demir'";
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where){
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where){
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields = array()) {
        // Checking if fields has any data
        if(count($fields)) {
            $keys = array_keys($fields); // keys of the array('username', 'password' ...)
            $values = null; // variable thats going to keep track of the question marks that we want put inside of our query

            foreach($fields as $field){
                $values .= '?,';
            }
            $values = rtrim($values,',');

            //die($values);
            // Result of this is INSERT INTO users(`username`,`password`,`email`) VALUES (?,?,?,?)

            $sql = "INSERT INTO {$table} (`".implode('`, `', $keys)."`) VALUES ({$values})";

            /*There us always gonna be backtick at start(`") and at the end ("`), because we define 
            the fields that we want to insert usually with backticks.
            Implode will take the keys of the array and create it into a string with a seperator.
            This seperator is going to be a `,`
            Now when we do echo $sql the result will be this INSERT INTO users(`username`,`password`,`email`)
            */ 

            if(!$this->query($sql, $fields)->error()) {
                return true;
            }
            // binding ?,?,? as actual values that we want to insert

            //echo $sql;
        }
        return false;
    }

    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        //die($set);

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        //echo $sql;
        if(!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        return $this->results()[0];
    }

    public function error() { 
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }
}
