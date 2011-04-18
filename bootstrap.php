<?php
preload_libs();
function preload_libs(){
	foreach(glob('lib/*.php') as $f)
		require_once $f;
}
