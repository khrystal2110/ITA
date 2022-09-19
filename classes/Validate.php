<?php
class Validate {
    private $_passed = false, // checking whether it's been passed or not
            $_errors = array(), // for checking if there have been any errors and store these errors so we can output them
            $_db = null; // for creating instance of our database

    public function __construct() { // this will be called when the validate class is instantiated
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()) { // passing in the data that we want to loop through and check and an array of rules
        foreach($items as $item => $rules) { // list through rules that we've defined
            foreach($rules as $rule => $rule_value) { // for each of the rules list through the rules inside of them and check it against the source that we've provided
                //echo "{$item} {$rule} must be {$rule_value}<br>";
                $value = trim($source[$item]); // value of each of the items 
                //echo $value;
                $item = escape($item);
                if($rule === 'required' && empty($value)) { // checking if the value is required or not, if the value is missing there's no point to validate anything
                    $this->addError("{$item} is required");
                } else if(!empty($value)){
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                        break;
                    }
                }
            }
        }

        if(empty($this->_errors)){
            $this->_passed = true;
        }
        return $this;
    }

    // Method for adding error to the errors array
    private function addError($error) {
        return $this->_errors[] = str_replace("_"," ",ucfirst($error));
        // This will make the first letter of the string uppercase, and will replace _ with a space.
        // So, if naming one field first_name, it's error will output like this "First name [...]"
    }

    // Method that returns the list of errors that we have
    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}