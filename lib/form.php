<?php
namespace forml;
use \Tag as Tag;
class Form extends Tag{
	var $fields = array();
	function content($args){
		foreach($args as $arg)
			if(is_a($arg, 'forml\Field'))
				$this->fields[] = $arg;
		return call_user_func_array(array('parent','__call'), array('content', array($args)));
	}
}
function form(){
	$args = func_get_args();
	$form = Form::form();
	$form->content($args);

	return $form;
}


