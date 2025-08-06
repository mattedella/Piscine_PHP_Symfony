<?php

function search_by_states($state_string) {
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

    $arrayState = explode(",", $state_string);
    $ret = [];

    foreach ($arrayState as $input) {
        $input = trim($input, " ");
        if (isset($states[$input])) {
            $abbr = $states[$input];
            if (isset($capitals[$abbr])) {
                $ret[] = $capitals[$abbr] . " is the capital of $input.";
            } else {
                $ret[] = "$input is neither a capital nor a state.";
            }
        }

        elseif (in_array($input, $capitals)) {
            $abbr = array_search($input, $capitals, true);
            $state_name = array_search($abbr, $states, true);
            if ($state_name) {
                $ret[] = "$input is the capital of $state_name.";
            } else {
                $ret[] = "$input is neither a capital nor a state.";
            }
        }

        else {
            $ret[] = "$input is neither a capital nor a state.";
        }
    }

    return $ret;
}
?>