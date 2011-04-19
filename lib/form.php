<?php
namespace forml;
use \Tag as Tag;
class Form extends Tag{
	var $fields = array();
	function content($args){
		foreach($args as $arg)
			if(is_a($arg, 'forml\Field'))
				$this->fields[$arg->name] = $arg;
		return call_user_func_array(array('parent','__call'), array('content', array($args)));
	}

	function process($array){
		$return = array();
		foreach($this->fields as $f){
			if(isset($array[$f->name]))
				$return[$f->name] = $array[$f->name];
		}
		return $return;
	}
	function fill($array){

		foreach($array as $k=>$v)
			if(isset($this->fields[$k])){
				$this->fields[$k]->value($v);
			}
		return $this;
	}
	function is_correct($array){
		$errors = false;
		foreach($this->fields as $f){
			if($f->is_required())
				if(!isset($array[$f->name]) or !trim($array[$f->name]))
					$errors = true;
		}
		return !$errors;
	}
}
function form(){
	$args = func_get_args();
	$form = Form::form();
	$form->content($args);

	return $form;
}


