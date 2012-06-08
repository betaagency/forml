<?php
preload_libs();
function preload_libs(){
	foreach(glob(__DIR__.'/lib/*.php') as $f)
		require_once $f;
}
