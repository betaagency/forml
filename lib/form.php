<?php
namespace forml;
class Form extends Tag{
	var $fields = array();
	function find_fields($where){
		if(is_array($where))
			foreach($where as $v)
				$this->find_fields($v);
		if(is_object($where) and is_a($where, 'forml\Field'))
			$this->fields[] = $where;
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
		foreach($array as $k=>$v){
			foreach($this->fields as $field){
				if($field->type == 'radio'){
					if($field->name == $k and $field->value == $v)
						$field->checked('checked');
				}else{
					if($field->name == $k)
						$field->value($v);
				}
			}
		}
		return $this;
	}
	function errors($array){
		$return = array();

		foreach($this->_validators as $v){
			$err = $v($array);
			if($err){
				if(is_array($err))
					foreach($err as $v)
						$return[] = $v;
				else
					$return[] = $err;
			}
		}

		foreach($this->fields as $f){
			if($f->is_required())
				if(!isset($array[$f->name]) or !trim($array[$f->name]))
					$return[] = $f->_required_text;
		}
		return $return;
	}
	function is_correct($array){
		$errors = false;

		foreach($this->_validators as $v)
			if($v($array))
				$errors = true;

		foreach($this->fields as $f){
			if($f->is_required())
				if(!isset($array[$f->name]) or !trim($array[$f->name]))
					$errors = true;
		}
		return !$errors;
	}
	var $_validators = array();
	function validators(){
		$args = func_get_args();
		$this->_validators = $args;
		return $this;
	}

	var $_clearers = array();

	function clearers(){
		$args = func_get_args();
		$this->_clearers = $args;
		return $this;
	}
	function clear($r){
		foreach($this->_clearers as $v)
			$r = $v($r);
		return $r;
	}
}
function form(){
	$args = func_get_args();
	$form = Form::form();
	$form->content($args);
	return $form;
}


