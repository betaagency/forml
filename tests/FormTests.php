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
	function test_form_radio_filling(){
		$this->assertEquals((string)f\form(f\radio('age', 15),
						   f\radio('age', 20),
						   f\radio('age', 25))
				    ->fill(array('age'=>'20')),
				    '<form>'.
				    '<input type="radio" name="age" value="15">'.
				    '<input type="radio" name="age" value="20" checked="checked">'.
				    '<input type="radio" name="age" value="25">'.
				    '</form>');
	}

	function test_all_radios_registered_in_fields(){
		$this->assertEquals(count(f\form(f\radio('age', 15),
						 f\radio('age', 20),
						 f\radio('age', 25))->fields),
				    3);
	}

	function test_recursive_finding_fields(){
		$form = f\form(f\line(f\Tag::div(f\text('firstname'))), '<hr>', f\text('lastname'));
		$this->assertEquals(array_values(array_map(function($v){return $v->name;},
							   $form->fields)),
				    array('firstname', 'lastname'));
	}

	function test_form_with_file(){
		$this->assertEquals((string)f\form(f\file('img')),
				    '<form enctype="multipart/form-data"><input type="file" name="img"></form>');
	}

	function test_errors_in_form(){
		$form = f\form(f\text('firstname')->required('Имя не заполнено!'));
		$this->assertEquals($form->errors(array()),
				    array('Имя не заполнено!'));
	}

	function test_no_errors_in_form(){
		$form = f\form(f\text('firstname')->required('Имя не заполнено!'));
		$this->assertEquals($form->errors(array('firstname'=>"qwe")),
				    array());
	}
	function test_validators_is_correct(){
		$form = f\form()->validators(function($r){ if($r['age']<18) return 'Возраст неправильный'; },
					     function($r){ if(!$r['agreed'])
							     return 'Надо согласиться с соглашением'; });
		$this->assertTrue($form->is_correct(array('age'=>19, 'agreed'=>1)));

		$this->assertFalse($form->is_correct(array('age'=>17, 'agreed'=>1)));
		$this->assertFalse($form->is_correct(array('age'=>19, 'agreed'=>0)));
		$this->assertFalse($form->is_correct(array('age'=>17, 'agreed'=>0)));
	}
	function test_validators_errors(){
		$form = f\form()->validators(function($r){ if($r['age']<18) return 'Возраст неправильный'; },
					     function($r){ if(!$r['agreed'])
							     return 'Надо согласиться с соглашением'; });
		$this->assertEquals($form->errors(array('age'=>19, 'agreed'=>1)), array());
		$this->assertEquals($form->errors(array('age'=>17, 'agreed'=>1)),
				    array('Возраст неправильный'));
		$this->assertEquals($form->errors(array('age'=>17, 'agreed'=>0)),
				    array('Возраст неправильный',
					  'Надо согласиться с соглашением'));
	}

	function test_validators_errors_merge(){
		$form = f\form()->validators(function($r){ return array('one','two');},
					     function($r){ return 'three';});
		$this->assertEquals($form->errors(array()),
				    array('one','two','three'));
	}

}