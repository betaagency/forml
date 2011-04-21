<?php
namespace forml;
class Form extends Tag{
	var $fields = array();
	function find_fields($where){
		if(is_array($where))
			foreach($where as $v)
				$this->find_fields($v);
		if(is_object($where) and is_a($where, 'forml\Field'))
			$this->fields[$where->name] = $where;
		if(is_a($where, 'forml\Tag')){
			$this->find_fields($where->to_array());
		}
	}

	function content($args){
		$this->find_fields($args);
		foreach($this->fields as $v)
			if(($v->_name == 'input') and ($v->type == 'file'))
				$this->enctype('multipart/form-data');
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


