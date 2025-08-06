<?php

class Text {
    private $texts = [];

    function __construct($text) {
        if ($text !== "") {
            $this->texts = $text;
        }
    }

    function addString($string) {
        $this->texts[] = $string;
    }

    function renderParagraphs() {
        $html = "";
        foreach ($this->texts as $t) {
            if (is_string($t)) {
                $html .= '<p>' . htmlspecialchars($t) . '</p>';
            }
        }
        return $html;
    }
}

?>