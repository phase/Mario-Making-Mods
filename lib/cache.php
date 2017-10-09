<?php
function cachePut($key, $value, $ttl = 120) {
	$st = microtime();

	$key = md5('mariomods.net' . ' - Blargboard') . '-BB-' . strtr($key, ':/', '-_');
	$value = $value === null ? null : serialize($value);

	// Storing this in APC
	if (function_exists('apc_store')) {
		// An extended key is needed to counteract a bug in APC.
		if ($value === null) {
			apc_delete($key . 'BB');
		} else {
			apc_store($key . 'BB', $value, $ttl);
		}
	}
	// Otherwise by ourselves
	else {
		if ($value === null) {
			@unlink(BOARD_ROOT . 'cache/data_' . $key . '.php');
		} else {
			$cache_data = '<' . '?' . 'php if (!defined(\'BLARG\')) die; if (' . (time() + $ttl) . ' < time()) $expired = true; else{$expired = false; $value = \'' . addcslashes($value, '\\\'') . '\';}' . '?' . '>';

			// Write out the cache file, check that the cache write was successful; all the data must be written
			// If it fails due to low diskspace, or other, remove the cache file
			if (file_put_contents(BOARD_ROOT . 'cache/data_' . $key . '.php', $cache_data, LOCK_EX) !== strlen($cache_data)) {
				@unlink(BOARD_ROOT . 'cache/data_' . $key . '.php');
			} else {
			}
		}
	}

	if (function_exists('apc_delete_file'))
   		@apc_delete_file(BOARD_ROOT . 'cache/data_' . $key . '.php');
}

function cacheGet($key, $ttl = 120) {
	$st = microtime();

	$key = md5('mariomods.net' . ' - Blargboard') . '-BB-' . strtr($key, ':/', '-_');

	// Fetch caché data from APC.
	if (function_exists('apc_fetch')) {
		$value = apc_fetch($key . 'BB');
	// Otherwise it's our data.
	} elseif (file_exists(BOARD_ROOT . 'cache/data_' . $key . '.php') && filesize(BOARD_ROOT . 'cache/data_' . $key . '.php') > 10) {
		@include(BOARD_ROOT . 'cache/data_' . $key . '.php');
		if (!empty($expired) && isset($value)) {
			@unlink(BOARD_ROOT . 'cache/data_' . $key . '.php');
			unset($value);
		} else {
		}
	}

	// If it's broke, it's broke... so give up on it.
	return empty($value) ? null : @unserialize($value);
}