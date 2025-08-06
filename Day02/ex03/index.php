<?php

require_once "TemplateEngine.php";
require_once "Elem.php";
try {
    $elem = new Elem("html");
    $head = new Elem("head");
    $head->pushElement(new Elem("title", "My Page Title"));
    $head->pushElement(new Elem("meta", ""));
    $elem->pushElement($head);
    $body = new Elem("body");
    $body->pushElement(new Elem("h1", "Welcome to My Page"));
    $body->pushElement(new Elem("p", "This is a paragraph on my page."));
    $body->pushElement(new Elem("img", "image.jpg"));
    // $body->pushElement(new Elem("ciao", ""));
    
    $elem->pushElement($body);
    $templateEngine = new TemplateEngine($elem);
}
catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}
$templateEngine->createFile("output.html");

?>