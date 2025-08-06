<?php

$input = file('ex06.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$elements = array();

foreach ($input as $line) {
    $parts = explode(' = ', $line, 2);
    $name = $parts[0];
    $info = $parts[1];
    $attributes = explode(', ', $info);

    $element = array('name' => $name);
    foreach ($attributes as $attr) {
        $kv = explode(':', $attr, 2);
        $key = trim($kv[0]);
        $value = trim($kv[1]);
        $element[$key] = $value;
    }

    $position = (int)$element['position'];
    $number = (int)$element['number'];

    $elements[] = array('pos' => $position, 'num' => $number, 'data' => $element);
}

usort($elements, function($a, $b) {
    return $a['num'] - $b['num'];
});

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Periodic Table</title>
    <style>
        table { border-collapse: collapse; }
        td {
            border: 1px solid #ccc;
            width: 150px;
            height: 120px;
            vertical-align: top;
            padding: 6px;
        }
        h4 { margin: 0 0 5px; font-size: 14px; }
        ul { margin: 0; padding-left: 16px; font-size: 12px; }
    </style>
</head>
<body>
    <table>
HTML;

$col = 0;
$html .= "<tr>";
foreach ($elements as $el) {
    $pos = $el['pos'];

    while ($col < $pos) {
        $html .= "<td></td>";
        $col++;
    }

    $e = $el['data'];
    $html .= "<td>";
    $html .= "<h4>" . $e['name'] . "</h4>";
    $html .= "<ul>";
    $html .= "<li>" . $e['number'] . "</li>";
    $html .= "<li>" . $e['small'] . "</li>";
    $html .= "<li>" . $e['molar'] . "</li>";
    $html .= "<li>" . $e['electron'] . "</li>";
    $html .= "</ul>";
    $html .= "</td>";

    $col = $pos + 1;

    if ($pos === 17) {
        $html .= "</tr><tr>";
        $col = 0;
    }
}
$html .= "</tr></table></body></html>";

// Save to file
file_put_contents('mendeleiev.html', $html);