<?php

/**
 * Allows to print any var properly.<br>
 * Can also stop PHP execution script if required.
 *
 * @param mixed $var variable to print
 * @param bool $die stop php script (false by default)
 */
function debug($var, $die = false) {
	echo "<pre>" . print_r($var, true) . "</pre>";
	if ($die) {
		die;
	}
}
