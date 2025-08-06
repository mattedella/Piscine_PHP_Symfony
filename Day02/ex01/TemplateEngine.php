<?php

require_once "Text.php";

class TemplateEngine {
    function createFile($fileName, Text $text) {

        $htmlFile = "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Document</title>
                </head>
                <body>
                ";
        $htmlFile .= $text->renderParagraphs();
        
        $htmlFile .= "
                </body>
                </html>";

        file_put_contents($fileName, $htmlFile);
    }
}

?>