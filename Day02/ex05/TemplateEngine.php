<?php

require_once "Elem.php";

class TemplateEngine {

    private $elem;

    public function __construct(Elem $elem) {
        $this->elem = $elem;
    }

    function createFile($fileName = "default.html") {
 
        $htmlFile = $this->elem->getHTML();

        file_put_contents($fileName, $htmlFile);
    }
}

?>