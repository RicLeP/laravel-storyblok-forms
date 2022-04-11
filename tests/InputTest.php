<?php

namespace Riclep\StoryblokForms\Tests;


use Riclep\StoryblokForms\Blocks\LsfInput;

class InputTest extends TestCase
{
	private function getBlockContents($index) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content']['contact'][0]['fields'][$index];
	}

	private function getFieldContents($field) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content'][$field];
	}


	/** @test */
	public function can_get_field_validation_rules() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals('required', $formInput->validators[0]->rule());
		$this->assertEquals('numeric', $formInput->validators[1]->rule());
	}


	/** @test */
	public function can_get_field_error_messages() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals('This field is required', $formInput->validators[0]->errorMessage());
		$this->assertEquals('You must enter a number', $formInput->validators[1]->errorMessage());
	}


	/** @test */
	public function can_get_input_validation_array() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals(['name' => ['required', 'numeric']], $formInput->validators->getRules());
	}


	/** @test */
	public function can_get_validation_array() {
		$formInput = new LsfInput($this->getBlockContents(0), null);

		$this->assertEquals(['name' => ['required', 'numeric']], $formInput->validationRules());
	}



	/** @xxxxtest */
	/*public function can_extract_content()
	{
		config(['storyblok.view_path' => 'Fixtures.views.']);


		$block = new FormInput($this->getBlockContents(0), null);
		//$xx = $this->blade($block->view(), array_merge(['block' => $this]));
		$xx = $this->blade($block->views()[0], array_merge(['block' => $this]));
		dd($xx);

		dd($block->render());

	}*/
}