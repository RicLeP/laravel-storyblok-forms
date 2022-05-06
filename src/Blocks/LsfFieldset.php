<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfFieldset extends \Riclep\Storyblok\Block
{
	//// potentially all fields in a fieldset could be name <input name="fieldsetname[fieldname]">
	/// this would out a multidimensional array in the response.
	/// makes validation herder?

	protected $inFieldSet = false;
	protected $isRepeating = false;

	public function __construct($content, $parent = null)
	{
		parent::__construct($content, $parent);

		if ($this->parent() instanceof LsfFieldset) {
			$this->inFieldSet = true;
		}

		if ($this->parent() instanceof LsfRepeatingFieldset) {
			$this->isRepeating = true;
		}
	}

	public function getInputNameAttribute() {
		if ($this->isRepeating) {
			return $this->parent()->input_name . '[' . $this->key . '][' . $this->content()['name'] . ']';
		}

		if ($this->inFieldSet) {
			return $this->parent()->input_name . '[' . $this->content()['name'] . ']';
		}

		return $this->content()['name'];
	}

	public function response($input) {
		//dd($input, $this);

		return $this->fields->map(function ($field) use ($input) {

//			dump($field, $input);
// TODO - handle radio buttons being empty
			dump($field, $input, $field->name);

			return $field->response($input[$field->name]);
			/*dd($field);

			return [
				'label' => $field->label,
				'response' => $field->response($input[$field->name] ?? ''),
			];*/
		})->toArray();
	}


	/**
	 * Returns all the validation rules for the fields in this Fieldset
	 *
	 * @return array
	 */
	public function validationRules() {
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->validationRules());
		});

		return $rules;
	}

	/**
	 * Returns all the error messages for the fields in this Fieldset
	 *
	 * @return array
	 */
	public function errorMessages() {
		$rules = [];

		$this->fields->each(function ($field) use (&$rules) {
			$rules = array_merge($rules, $field->errorMessages());
		});

		return $rules;
	}
}