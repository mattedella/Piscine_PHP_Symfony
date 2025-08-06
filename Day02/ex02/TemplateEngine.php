<?php

require_once "Coffee.php";
require_once "Tea.php";

class TemplateEngine {
    function createFile(HotBeverage $text) {
        $htmlFile = file_get_contents("template.html");

        $rc = new ReflectionClass($text);
        $proprerties = $rc->getProperties();
        $parameters = [];

        foreach ($proprerties as $property) {
            $property->setAccessible(true);
            $parameters[$property->getName()] = $property->getValue($text);
        }

        foreach ($parameters as $key => $value) {
            $htmlFile = str_replace("{" . $key . "}", htmlspecialchars($value), $htmlFile);
        }

        file_put_contents($text->getName() . ".html", $htmlFile);
    }
}

?>