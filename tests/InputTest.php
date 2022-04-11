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

}