<?php

class TemplateEngine {
    function createFile($fileName, $templateName, $parameters) {
        $htmlFile = file_get_contents($templateName);

        foreach ($parameters as $key => $value) {
            $htmlFile = str_replace("{" . $key . "}", $value, $htmlFile);
        }
        
        file_put_contents($fileName, $htmlFile);
    }
}

?>