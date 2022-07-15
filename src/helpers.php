<?php

if (!function_exists('array_map_recursive')) {
	function array_map_recursive($callback, $array)
	{
		$func = function ($item) use (&$func, &$callback) {
			return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
		};

		return array_map($func, $array);
	}
}
