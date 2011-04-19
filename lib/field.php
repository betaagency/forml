<?php
namespace forml;
require_once 'tag.php';
class Field extends Tag{
	var $label;

	static function text($name){
		$obj = new Field('input');
		$obj->type('text')->name($name);
		return $obj;
	}

	static function text_labeled($name, $label, $id = false){
		$obj = static::text($name);
		$label = Tag::label()->content($label);
		$obj->label = $label;
		if($id){
			$label->for($id);
			$obj->id($id);
		}

		$obj->after($label);
		return $obj;
	}

	static function labeled_text($label, $name, $id = false){
		$obj = static::text($name);
		$label = Tag::label()->content($label);
		$obj->label = $label;
		if($id){
			$label->for($id);
			$obj->id($id);
		}

		$obj->before($label);
		return $obj;
	}

	static function textarea($name){
		$obj = new Field('textarea');
		$obj->name($name);
		return $obj;
	}

	function id($v){
		if($this->label)
			$this->label->for($v);
		return parent::__call('id', array($v));
	}

	function value($v){
		if($this->_name == 'textarea')
			return $this->content($v);
		return parent::__call('value', array($v));
	}

	var $_required = false;
	function required(){
		$this->_required = true;
		return $this;
	}

	function is_required(){
		return $this->_required;
	}

}

function def_wrapper($name){
	eval('namespace forml;'.
	     'function '.$name.'(){'.
	     '$args = func_get_args();'.
	     'return call_user_func_array(array(\'forml\Field\',\''.$name.'\'), $args);}');
}

def_wrapper('text');
def_wrapper('labeled_text');
def_wrapper('text_labeled');
def_wrapper('textarea');

function line(){
	$args = func_get_args();
	return Tag::div($args)->class('line');
}