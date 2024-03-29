<?php
use forml as f;
class FieldTests extends PHPUnit_Framework_TestCase{
	function test_text(){
		$this->assertEquals((string)f\text('firstname'),
				    '<input type="text" name="firstname">');
	}

	function test_text_with_value(){
		$this->assertEquals((string)f\text('firstname')->value("Лёша"),
				    '<input type="text" name="firstname" value="Лёша">');
	}

	function test_textarea(){
		$this->assertEquals((string)f\textarea('firstname'),
				    '<textarea name="firstname"></textarea>');
	}

	function test_textarea_with_value(){
		$this->assertEquals((string)f\textarea('firstname')->value("Hello!"),
				    '<textarea name="firstname">Hello!</textarea>');
	}

	function test_line(){
		$this->assertEquals((string)f\line(''), '<div class="line"></div>');
	}

	function test_legend_group(){
		$this->assertEquals((string)f\legend_group('legend', ' one ', ' two '),
				    '<div class="group"><div class="legend">legend</div> one  two </div>');
	}

	function test_group(){
		$this->assertEquals((string)f\group(' one ', ' two '),
				    '<div class="group"> one  two </div>');
	}

	function test_select(){
		$countries = array(2=>'russia', 3=>'france', 4=>'egypt');
		$opts = '';
		foreach($countries as $k=>$v)
			$opts .= sprintf('<option value="%s">%s</option>', $k, $v);
		$this->assertEquals((string)f\select('country', $countries),
				    '<select name="country">'.$opts.'</select>');
	}

	function test_select_with_value(){
		$countries = array(2=>'russia', 3=>'france', 4=>'egypt');
		$opts = '';
		foreach($countries as $k=>$v)
			$opts .= sprintf('<option value="%s"%s>%s</option>', $k,(($k==3) ? ' selected': ''), $v);
		$this->assertEquals((string)f\select('country', $countries)->value(3),
				    '<select name="country">'.$opts.'</select>');
	}

	function test_label(){
		$this->assertEquals((string)f\label('Страна'),'<label>Страна</label>');
		$this->assertEquals((string)f\label('Страна', 'country'),'<label for="country">Страна</label>');
		$this->assertEquals((string)f\label('Страна')->for("country"),'<label for="country">Страна</label>');
	}

	function test_text_without_name(){
		$this->assertEquals((string)f\text(), '<input type="text">');
	}

	function test_file(){
		$this->assertEquals((string)f\file(), '<input type="file">');
		$this->assertEquals((string)f\file('img'), '<input type="file" name="img">');
	}

	function test_escape_in_textarea_value(){
		$this->assertEquals((string)f\textarea('text')->value('& hello \' " < >'),
				    '<textarea name="text">&amp; hello &#39; &quot; &lt; &gt;</textarea>');
	}

	function test_submit(){
		$this->assertEquals((string)f\submit('Отправить'),
				    '<input type="submit" value="Отправить">');
		$this->assertEquals((string)f\submit(),
				    '<input type="submit">');
	}

	function test_label_required(){
		$this->assertEquals((string)f\label('Страна')->required(),
				    '<label class="required">Страна <span>*</span></label>');
	}

	function test_text_required(){
		$this->assertEquals((string)f\text('firstname')->required(),
				    '<input type="text" name="firstname" class="required">');
	}

	function test_checkbox(){
		$this->assertEquals((string)f\checkbox('agreed', 'yes'),
				    '<input type="checkbox" name="agreed" value="yes">');
	}

	function test_checkbox_without_args(){
		$this->assertEquals((string)f\checkbox(),
				    '<input type="checkbox">');
	}

	function test_checked_checkbox(){
		$this->assertEquals((string)f\checkbox()->checked(),
				    '<input type="checkbox" checked="checked">');
	}



	function test_radio(){
		$this->assertEquals((string)f\radio('firstname'),
				    '<input type="radio" name="firstname">');
	}

	function test_radio_without_args(){
		$this->assertEquals((string)f\radio(),
				    '<input type="radio">');
	}

	function test_radio_with_value(){
		$this->assertEquals((string)f\radio('firstname', 'foo-bar'),
				    '<input type="radio" name="firstname" value="foo-bar">');
	}

}
