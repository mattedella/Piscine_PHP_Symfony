<?php

function array2hash($arrays) {
	$hash = [];
	foreach ($arrays as $array) {
		if (is_array($array) && isset($array[1])) 
			$hash[$array[1]] = $array[0];
	}
	print_r($hash);
}
    
?>