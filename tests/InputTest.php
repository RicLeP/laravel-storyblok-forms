<?php

namespace Riclep\StoryblokForms\Tests;


use Riclep\StoryblokForms\Blocks\LsfCheckbox;
use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfForm;
use Riclep\StoryblokForms\Blocks\LsfInput;
use Riclep\StoryblokForms\Blocks\LsfRadioButton;
use Riclep\StoryblokForms\Validator;

class InputTest extends TestCase
{
	private function getBlockContents($index) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content']['fields'][$index];
	}

	private function getPageContents() {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content'];
	}



	/** @test */
	public function returns_false_when_no_validation_rules() {
		// Job title
		$input = new LsfInput($this->getBlockContents(1), null);

		$this->assertFalse($input->validationRules());
	}

	/** @test */
	public function can_get_field_validation_rules() {
		// Email
		$input = new LsfInput($this->getBlockContents(2), null);

		$this->assertEquals(['email' => ['email', 'required']], $input->validationRules());
	}

	/** @test */
	public function can_get_field_validation_with_parameter_rules() {
		// Numeric
		$input = new LsfInput($this->getBlockContents(5), null);

		$this->assertEquals(['min' => ['numeric', 'min:10']], $input->validationRules());
	}

	/** @test */
	public function can_parse_checkbox() {
		// checkbox
		$input = new LsfCheckbox($this->getBlockContents(3), null);

		$this->assertEquals([['checked' => false, 'label' => 'First', 'value' => 'first'], ['checked' => false, 'label' => 'Second', 'value' => 'second'], ['checked' => true, 'label' => 'Selected', 'value' => 'selected']], $input->siblings()->toArray());
	}

	/** @test */
	public function can_parse_radio_buttons() {
		// radio
		$input = new LsfRadioButton($this->getBlockContents(4), null);

		$this->assertEquals([['checked' => false, 'label' => 'First', 'value' => 'first'], ['checked' => false, 'label' => 'Second', 'value' => 'second'], ['checked' => true, 'label' => 'Selected', 'value' => 'selected']], $input->siblings()->toArray());
	}

	/** @test */
	public function can_get_fieldset_rules() {
		// Fieldset
		$input = new LsfFieldset($this->getBlockContents(0), null);

		$this->assertEquals(['name' => ['required'], 'surname' => ['required']], $input->validationRules());
	}

	/** @test */
	public function can_get_form_rules() {
		$form = new LsfForm($this->getPageContents(), null);

		$this->assertEquals(['name' => ['required'], 'surname' => ['required'], 'email' => ['email', 'required'], 'min' => ['numeric', 'min:10']], $form->validationRules());
	}

	/** @test */
	public function can_get_fieldset_error_messages() {
		// Fieldset
		$input = new LsfFieldset($this->getBlockContents(0), null);

		$this->assertEquals(['name.required' => 'Please enter your name', 'surname.required' => 'Please enter your surname'], $input->errorMessages());
	}

	/** @test */
	public function can_get_field_error_messages() {
		// email
		$input = new LsfInput($this->getBlockContents(2), null);

		$this->assertEquals(['email.required' => 'Let us know your email'], $input->errorMessages());
	}

	/** @test */
	public function can_get_form_error_messages() {
		// Form
		$form = new LsfForm($this->getPageContents(), null);

		$this->assertEquals(['name.required' => 'Please enter your name', 'surname.required' => 'Please enter your surname', 'email.required' => 'Let us know your email'], $form->errorMessages());
	}

	/** @test */
	public function can_get_input_response() {
		// Job title
		$input = new LsfInput($this->getBlockContents(1), null);

		$this->assertEquals('name', $input->response('name'));
	}

	/** @test */
	public function can_get_checkbox_response() {
		// Checkbox
		$input = new LsfCheckbox($this->getBlockContents(3), null);


		$this->assertEquals([['label' => 'First', 'checked' => true], ['label' => 'Second', 'checked' => false], ['label' => 'Selected', 'checked' => true]], $input->response(['first', 'selected']));
	}

	/** @test */
	public function can_get_radio_button_response() {
		// Radio
		$input = new LsfRadioButton($this->getBlockContents(4), null);

		$this->assertEquals([['label' => 'First', 'checked' => false], ['label' => 'Second', 'checked' => true], ['label' => 'Selected', 'checked' => false]], $input->response(['second']));
	}


	/** @test */
	public function can_flatten_form() {
		// Form
		$form = new LsfForm($this->getPageContents(), null);


		$this->assertInstanceOf(LsfInput::class, $form->flattenFields()->toArray()[0]);
		$this->assertInstanceOf(LsfInput::class, $form->flattenFields()->toArray()[1]);
		$this->assertInstanceOf(LsfInput::class, $form->flattenFields()->toArray()[2]);
		$this->assertInstanceOf(LsfInput::class, $form->flattenFields()->toArray()[3]);
		$this->assertInstanceOf(LsfCheckbox::class, $form->flattenFields()->toArray()[4]);
		$this->assertInstanceOf(LsfRadioButton::class, $form->flattenFields()->toArray()[5]);
	}

	/** @test */
	public function can_get_validators_rules()
	{
		// Numeric
		$input = new LsfInput($this->getBlockContents(5), null);

		$this->assertEquals(['min' => ['numeric', 'min:10']], $input->validators->validationRules());
	}

	/** @test */
	public function can_get_validators_error_messages()
	{
		// Email
		$input = new LsfInput($this->getBlockContents(2), null);

		$this->assertEquals(['email.required' => 'Let us know your email'], $input->validators->errorMessages());
	}

	/** @test */
	public function can_use_array_access_on_validators() {
		// Numeric
		$input = new LsfInput($this->getBlockContents(5), null);

		$this->assertTrue($input->validators->offsetExists(0));

		$this->assertInstanceOf(Validator::class, $input->validators[0]);

		$input->validators->offsetSet(2, 'testing');


		$this->assertEquals('testing', $input->validators[2]);

		$input->validators[] = 'testing 2';

		$this->assertEquals('testing 2', $input->validators[3]);

		$input->validators->offsetUnset(2);

		$this->assertFalse($input->validators->offsetExists(2));
	}

}