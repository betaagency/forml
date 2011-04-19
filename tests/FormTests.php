<?php
use forml as f;
class FormTests extends PHPUnit_Framework_TestCase{

	function test_empty_form(){
		$this->assertEquals((string)f\form(), '<form></form>');
	}

	function test_form_with_string_content(){
		$this->assertEquals((string)f\form("qwe","asd"), '<form>qweasd</form>');
	}

	function test_form_with_action_and_method(){
		$this->assertEquals((string)f\form()->action('/register/process')->method('post'),
				    '<form action="/register/process" method="post"></form>');
	}

	function test_form_field_serialisation(){
		$this->assertEquals((string)f\form(f\text('firstname'),'<hr>',f\text('lastname')),
				    '<form><input type="text" name="firstname"><hr>'.
				    '<input type="text" name="lastname"></form>');
	}

	function test_form_fields_list(){
		$this->assertEquals(array_map(function($v){return $v->name;},
					      f\form(f\text('firstname'),'<hr>',f\text('lastname'))->fields),
				    array('firstname', 'lastname'));
	}
}