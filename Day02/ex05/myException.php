<?php

class MyException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }

    public function errorMessage() {
        return "MyException: " . $this->message;
    }
}

?>