<?php
class Tag{
	static $short_tags = array('img', 'input');
	var $name = '';
	function __construct($name){
		$this->name = $name;
	}

	static function __callStatic($name, $args){
		$tag = new Tag($name);
		if(reset($args))
			$tag->content(reset($args));
		return $tag;
	}

	var $attrs = array();
	var $content = '';
	var $before = '';
	var $after = '';
	function __call($name, $args){
		if($name == 'content'){
			$this->content = $args[0];
		}elseif($name == 'before'){
			$this->before = $args[0];
		}elseif($name == 'after'){
			$this->after = $args[0];
		}elseif($name == 'wrap'){
			$this->before = $args[0];
			$this->after = $args[1];
		}elseif(preg_match('/_and_/', $name)){
			$attrs = explode('_and_', $name);
			foreach($attrs as $v)
				$this->{$v}($args[0]);
		}elseif(preg_match('/_/', $name)){
			$attrs = explode('_', $name);
			foreach($attrs as $k=>$v)
				$this->{$v}($args[$k]);
		}else{
			$this->attrs[$name] = $args[0];
		}

		return $this;
	}

	function is_short_tag(){
		if(in_array($this->name, self::$short_tags))
			return true;
	}

	function tag_attrs(){
		if(!$this->attrs)
			return '';
		$a = array();
		foreach($this->attrs as $k=>$v)
			$a[] = $k.'="'.self::escape($v).'"';
		return ' '.implode(' ', $a);
	}

	static function escape($v){
		return str_replace(array('&','"',"'",'<','>'),
				   array('&amp;','&quot;','&#39;','&lt;','&gt;'),
				   $v);
	}

	function __get($name){
		if(isset($this->attrs[$name]))
			return $this->attrs[$name];
		return false;
	}

	function __toString(){
		$r = '';
		$r .= $this->before;
		$r .= "<".$this->name.$this->tag_attrs().">";
		$r .= $this->content;
		if(!$this->is_short_tag())
			$r .= "</".$this->name.">";
		$r .= $this->after;
		return $r;
	}
}