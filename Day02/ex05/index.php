<?php

require_once "TemplateEngine.php";
require_once "Elem.php";
require_once "myException.php";

try {
    
    $elem = new Elem("html");
    $head = new Elem("head");
    $head->pushElement(new Elem("title", "My Page Title"));
    $head->pushElement(new Elem("meta", "",["charset" => "UTF-8"]));
    $elem->pushElement($head);
    $body = new Elem("body");
    $body->pushElement(new Elem("h1", "Welcome to My Page"));
    $body->pushElement(new Elem("p", "This is a paragraph on my page", ["class" => "text"]));
    $body->pushElement(new Elem("img", "", ["src" => "image.jpg", "alt" => "An image"]));
    // $body->pushElement(new Elem("ciao", ""));
    $ul = new Elem("ul", "", ["class" => "list"]);
    $ul->pushElement(new Elem("li", "Item 1"));
    $ul->pushElement(new Elem("li", "Item 2"));
    $ul->pushElement(new Elem("li", "Item 3"));
    $body->pushElement($ul);

    $ol = new Elem("ol", "", ["class" => "list"]);
    $ol->pushElement(new Elem("li", "Item 1"));
    $ol->pushElement(new Elem("li", "Item 2"));
    $ol->pushElement(new Elem("li", "Item 3"));
    $body->pushElement($ol);
    $table = new Elem("table", "", ["class" => "data-table"]);
    $tr = new Elem("tr");
    $th1 = new Elem("th", "Header 1");
    $th2 = new Elem("th", "Header 2");
    $tr->pushElement($th1);
    $tr->pushElement($th2);
    $table->pushElement($tr);
    $tr2 = new Elem("tr");
    $td1 = new Elem("td", "Data 1");
    $td2 = new Elem("td", "Data 2");
    $tr2->pushElement($td1);
    $tr2->pushElement($td2);
    $table->pushElement($tr2);
    $body->pushElement($table);
    $body->pushElement(new Elem("div", "This is a div element", ["class" => "container"]));
    $body->pushElement(new Elem("span", "This is a span element", ["class" => "highlight"]));
    $body->pushElement(new Elem("hr"));
    $body->pushElement(new Elem("br"));
    $body->pushElement(new Elem("h2", "Subheading"));
    $body->pushElement(new Elem("h3", "Another Subheading"));
    $body->pushElement(new Elem("h4", "Yet Another Subheading"));
    $body->pushElement(new Elem("h5", "More Subheadings"));
    $body->pushElement(new Elem("h6", "Final Subheading"));
    
    $elem->pushElement($body);
    $validPage = $elem->validPage();
	if (!$validPage) {
        throw new myException("Invalid HTML structure detected.");
	}
    $templateEngine = new TemplateEngine($elem);
    $templateEngine->createFile("output.html");
}
catch (myException $e) {
    echo "Error: " . $e->errorMessage() . "\n";
    exit;
}


?>