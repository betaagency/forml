<?php

foreach (glob('tests/*.php') as $file){
	if($file != 'tests/AllTests.php')
		require $file;
}

class AllTests{
	public static function suite(){
		$suite = new PHPUnit_Framework_TestSuite("PHPUnit");
		foreach (glob('tests/*.php') as $file){
			if($file != 'tests/AllTests.php')
				$suite->addTestSuite(basename(substr($file,0,-4)));
		}
		return $suite;
	}
}
