<?php
use forml\Tag as Tag;
class TagTests extends PHPUnit_Framework_TestCase{
	static function setUpBeforeClass(){
		//nothing
	}

	function test_basic(){
		$this->assertEquals((string)Tag::p(), '<p></p>');
	}

	function test_basic_with_content(){
		$this->assertEquals((string)Tag::p("Hello!"), '<p>Hello!</p>');
	}

	function test_attributes(){
		$this->assertEquals((string)Tag::p("Hello!")->class('text'), '<p class="text">Hello!</p>');
	}

	function test_attribute_quote(){
		$this->assertEquals((string)Tag::p("Hello!")->title('& hello \' " < >'),
				    '<p title="&amp; hello &#39; &quot; &lt; &gt;">Hello!</p>');
	}

	function test_multi_assign_attrs(){
		$this->assertEquals((string)Tag::input()->id_and_name('firstname'),
				    '<input id="firstname" name="firstname">');
	}

	function test_multi_assign_attrs_without_and(){
		$this->assertEquals((string)Tag::input()->id_name('firstname','name'),
				    '<input id="firstname" name="name">');
	}

	function test_img_tag(){
		$this->assertEquals((string)Tag::img()->src('http://ya.ru/logo.jpg'),
				    '<img src="http://ya.ru/logo.jpg">');
	}

	function test_before_and_after(){
		$this->assertEquals((string)Tag::p('test')->before('-')->after('+'),
				    '-<p>test</p>+');
	}

	function test_wrap(){
		$this->assertEquals((string)Tag::p('test')->wrap('-', '+'),
				    '-<p>test</p>+');
	}

	function test_getter(){
		$this->assertEquals((string)Tag::p()->border('1px')->border,
				    '1px');
	}

	function test_getter_unquoting(){
		$this->assertEquals((string)Tag::p()->title('& hello \' " < >')->title,
				    '& hello \' " < >');
	}

	function test_correct_name_assign(){
		$this->assertEquals((string)Tag::input()->name('firstname'),
				    '<input name="firstname">');
	}

	function test_blank_tag(){
		$this->assertEquals((string)Tag::blank(),
				    '');
		$this->assertEquals(Tag::blank()->to_array(),
				    array());
	}

	function test_blank_tag_basic(){
		$tag = Tag::blank(Tag::p('hello,'), Tag::p(' world!'));
		$this->assertEquals((string)$tag,
				    '<p>hello,</p><p> world!</p>');
		$this->assertEquals(array_map('strval', $tag->to_array()),
				    array('<p>hello,</p>','<p> world!</p>'));
	}

	function test_tag_to_array(){
		$tag = Tag::div(Tag::p('Hello, World!'))
			->id('content')->class('text')
			->before(Tag::p('intro'))->after('out');

		$this->assertEquals(array_map('strval', $tag->to_array()),
				    array('<p>intro</p>',
					  '<div id="content" class="text">','<p>Hello, World!</p>','</div>',
					  'out'));
		$this->assertEquals(array_map('gettype', $tag->to_array()),
				    array('object',
					  'string','object','string',
					  'string'));
	}

	function test_tag_name(){
		$tag = Tag::input()->name('firstname');
		$this->assertEquals($tag->name, 'firstname');
	}

	function test_tag_selected(){
		$tag = Tag::option("russia")->value(3)->selected();
		$this->assertEquals((string)$tag, '<option value="3" selected>russia</option>');
	}
}
