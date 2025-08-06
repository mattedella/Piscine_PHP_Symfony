<?php

function array2hash_sorted($arrays) {
	$hash = [];
	foreach ($arrays as $array) {
		if (is_array($array) && isset($array[0])) 
			$hash[$array[0]] = $array[1];
	}
    krsort($hash);
	print_r($hash);
}

?>