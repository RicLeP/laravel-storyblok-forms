<?php

namespace Riclep\StoryblokForms;

use Illuminate\Http\Request;
use Riclep\Storyblok\StoryblokFacade as StoryBlok;

class FormResponse
{
	// TODO - pass extra data - imagine a staff contact form wrapped in a component where they select the address this instance should go to


	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->requestPage();
	}


	/**
	 * @return void
	 */
	protected function requestPage() {
		$this->page = Storyblok::read($this->request->input('_slug'));
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	protected function form() {
		return $this->page->form;
	}

	/**
	 * @return array
	 */
	public function validate() {
		return $this->request->validate($this->form()->validationRules(), $this->form()->errorMessages());
	}

	public function validationRules() {
		return $this->form()->validationRules();
	}

	/**
	 * @return mixed
	 */
	public function response() {
		return $this->responses();
	}

	/**
	 * @return false|string
	 */
	public function json() {
		return json_encode($this->response());
	}

	/**
	 * @return mixed
	 */
	protected function responses() {
		$input = $this->request->except(['_token', '_slug']);

		return $this->form()->fields->map(function ($field) use ($input) {

			// Handle empty radio buttons sending nothing in POST request
			if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
				if (!array_key_exists($field->name, $input)) {
					$input[$field->name] = null;
				}
			}

			return $field->response($input[$field->name]);
		})->toArray();
	}

}