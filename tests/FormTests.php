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
		$this->assertEquals(array_values(array_map(function($v){return $v->name;},
							   f\form(f\text('firstname'), '<hr>',
								  f\text('lastname'))->fields)),
				    array('firstname', 'lastname'));
	}

	function test_form_process_array(){
		$this->assertEquals(f\form(f\text('firstname'),'<hr>',f\text('lastname'))
				    ->process(array('lastname'=>'foo',
						    'age'=>'15',
						    'firstname'=>'bar')),
				    array('firstname'=>'bar', 'lastname'=>'foo'));
	}

	function test_form_field_required(){
		$form = f\form(f\text('firstname')->required(),'<hr>',f\text('lastname'));
		$this->assertTrue($form->is_correct(array('lastname'=>'foo',
							  'age'=>'15',
							  'firstname'=>'bar')));
		$this->assertFalse($form->is_correct(array('lastname'=>'foo',
							   'age'=>'15')));
	}

	function test_form_value_filling(){
		$this->assertEquals((string)f\form(f\text('firstname'),'<hr>',f\text('lastname'))
				    ->fill(array('firstname'=>'bar', 'lastname'=>'foo')),
				    '<form><input type="text" name="firstname" value="bar"><hr>'.
				    '<input type="text" name="lastname" value="foo"></form>');
	}

	function test_recursive_finding_fields(){
		$form = f\form(f\line(f\Tag::div(f\text('firstname'))), '<hr>', f\text('lastname'));
		$this->assertEquals(array_values(array_map(function($v){return $v->name;},
							   $form->fields)),
				    array('firstname', 'lastname'));
	}
}