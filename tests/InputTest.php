<?php

namespace Riclep\StoryblokForms\Tests;


use Riclep\StoryblokForms\Blocks\LsfCheckbox;
use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfInput;
use Riclep\StoryblokForms\Blocks\LsfRadioButton;

class InputTest extends TestCase
{
	private function getBlockContents($index) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content']['fields'][$index];
	}

	private function getFieldContents($field) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content'][$field];
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

		$this->assertEquals([['checked' => false, 'label' => 'First', 'value' => 'first'], ['checked' => false, 'label' => 'Second', 'value' => 'second'], ['checked' => true, 'label' => 'Selected', 'value' => 'selected']], $input->checkboxes()->toArray());
	}

	/** @test */
	public function can_parse_radio_buttons() {
		// checkbox
		$input = new LsfRadioButton($this->getBlockContents(4), null);

		$this->assertEquals([['checked' => false, 'label' => 'First', 'value' => 'first'], ['checked' => false, 'label' => 'Second', 'value' => 'second'], ['checked' => true, 'label' => 'Selected', 'value' => 'selected']], $input->radioButtons()->toArray());
	}

	/** @test */
	public function can_get_fieldset_rules() {
		// Fieldset
		$input = new LsfFieldset($this->getBlockContents(0), null);

		$this->assertEquals(['name' => ['required'], 'surname' => ['required']], $input->validationRules());
	}



















	/** @test */
	public function xcan_get_field_validation_rules() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals('required', $formInput->validators[0]->rule());
		$this->assertEquals('numeric', $formInput->validators[1]->rule());
	}


	/** @test */
	public function xcan_get_field_error_messages() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals('This field is required', $formInput->validators[0]->errorMessage());
		$this->assertEquals('You must enter a number', $formInput->validators[1]->errorMessage());
	}


	/** @test */
	public function xcan_get_input_validation_array() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals(['name' => ['required', 'numeric']], $formInput->validators->getRules());
	}


	/** @test */
	public function xcan_get_validation_array() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals(['name' => ['required', 'numeric']], $formInput->validationRules());
	}



	/** @xxxxtest */
	/*public function xcan_extract_content()
	{
		config(['storyblok.view_path' => 'Fixtures.views.']);


		$block = new FormInput($this->getBlockContents(0), null);
		//$xx = $this->blade($block->view(), array_merge(['block' => $this]));
		$xx = $this->blade($block->views()[0], array_merge(['block' => $this]));
		dd($xx);

		dd($block->render());

	}*/
}