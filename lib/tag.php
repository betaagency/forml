<?php
namespace forml;
class Tag{
	static $short_tags = array('img', 'input');
	var $_name = '';
	function __construct($name){
		$this->_name = $name;
	}

	static function __callStatic($name, $args){
		$tag = new static($name);
		if(isset($args[0]))
			$tag->content($args[0]);
		return $tag;
	}

	var $attrs = array();
	var $content;
	var $before;
	var $after;
	function __call($name, $args){
		if($name == 'content'){
			$this->content = $args[0];
		}elseif($name == 'selected'){
			$this->attrs['selected'] = true;
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
		if(in_array($this->_name, self::$short_tags))
			return true;
	}

	function tag_attrs(){
		if(!$this->attrs)
			return '';
		$a = array();
		foreach($this->attrs as $k=>$v){
			if($k != 'selected')
				$a[] = $k.'="'.self::escape($v).'"';
			else
				$a[] = 'selected';
		}
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
		return implode('', $this->to_array());
	}

	static function blank(){
		$args = func_get_args();
		$tag = new self(false);
		$tag->content = $args;
		return $tag;
	}

	function to_array(){
		$r = array();

		$fn = function($what) use(&$r){
			if(is_array($what)){
				$r = array_merge($r, $what);
			}else{
				$r[] = $what;
			}
		};

		if($this->before)
			$fn($this->before);

		if($this->_name)
			$r[] = "<".$this->_name.$this->tag_attrs().">";

		if($this->content)
			$fn($this->content);

		if($this->_name)
			if(!$this->is_short_tag())
				$r[] = "</".$this->_name.">";

		if($this->after)
			$fn($this->after);

		return $r;
	}

}

