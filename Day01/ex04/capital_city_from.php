<?php

function capital_city_from($state) {

    $states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
    ];
    $capitals = [
    'OR' => 'Salem',
    'AL' => 'Montgomery',
    'NJ' => 'trenton',
    'KS' => 'Topeka',
    ];
    $initial = "";
    $city = "";

    if(isset($states[$state]))
        $initial = $states[$state];
    else
        $initial = "Unknown";

    if (isset($capitals[$initial]))
        $city = $capitals[$initial];
    else
        $city = $initial = "Unknown";
    $city = $city . "\n";
    return $city;
}
?>
