<?php

namespace Riclep\StoryblokForms;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Riclep\Storyblok\StoryblokFacade as StoryBlok;

class FormResponse
{
	// TODO - pass extra data - imagine a staff contact form wrapped in a component where they select the email address this instance should go to


	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		// convert JSON response to same format as standard HTML forms
		if ($request->isJson()) {
			$request->replace(Arr::undot($request->all()));
		}

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


	public function validate()
	{
		Validator::make($this->request->all(), $this->form()->validationRules(), $this->form()->errorMessages())->validate();
	}

	public function validationRules() {
		return $this->form()->validationRules();
	}

	/**
	 * @return mixed
	 */
	public function fields() {
		return $this->responses();
	}

	/**
	 * @return false|string
	 */
	public function json() {
		return json_encode($this->fields());
	}

	/**
	 * @return mixed
	 */
	protected function responses() {
		$input = $this->request->except(['_token', '_slug']);

		return $this->form()->fields->map(function ($field) use ($input) {

			// Handle empty radio buttons sending nothing in POST request
			//if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
				if (!array_key_exists($field->name, $input)) {
					$input[$field->name] = null;
				}
			//}

			return $field->response($input[$field->name]);
		})->toArray();
	}


	/**
	 * Flattens the response returning an array of field responses
	 *
	 * @return array
	 */
	public function flatten() {
		return Arr::flatten($this->fields());
	}

	public function files() {
		return array_values(array_filter($this->flatten(), function ($response) {
			return $response instanceof UploadedFile;
		}));
	}
}