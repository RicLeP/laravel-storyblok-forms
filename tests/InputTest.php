<?php

namespace Riclep\StoryblokForms\Tests;


use Riclep\StoryblokForms\Blocks\LsfCheckbox;
use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfForm;
use Riclep\StoryblokForms\Blocks\LsfInput;
use Riclep\StoryblokForms\Blocks\LsfRadioButton;

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
		// Fieldset
		$form = new LsfForm($this->getPageContents(), null);

		$this->assertEquals(['name' => ['required'], 'surname' => ['required'], 'email' => ['email', 'required']], $form->validationRules());
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
		// Fieldset
		$form = new LsfForm($this->getPageContents(), null);

		$this->assertEquals(['name.required' => 'Please enter your name', 'surname.required' => 'Please enter your surname', 'email.required' => 'Let us know your email'], $form->errorMessages());
	}


}