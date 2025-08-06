<?php

require_once "TemplateEngine.php";

$fileName = "test.html";
$strings = [
    "Darth Bane",
    "Darth Bane is the sith'ari",
    "Author: Sith",
    "Price: 20",
    "ISBN: 1234567890",
    "Darth Bane is a fictional character in the Star Wars universe"
];
$text = new Text($strings);
$text->addString("Darth Bane is a powerful Sith Lord");
$engine = new TemplateEngine();
$engine->createFile($fileName, $text);

?>