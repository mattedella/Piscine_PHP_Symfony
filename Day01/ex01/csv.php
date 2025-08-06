<?php

    $file = fopen("ex01.txt", "r") or die("Unable to open file!");

    $words = explode(",", fgets($file));

    foreach ($words as $word) {
        print($word);
        print("\n");
    }
?>