<?php

namespace App\Storyblok\Forms;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class FormResponse extends \Riclep\StoryblokForms\FormResponse
{
	// TODO - pass extra data - imagine a staff contact form wrapped in a component where they select the email address this instance should go to
	public Request $request;

	protected $step;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request, $page)
	{
		$this->step = $request->input('step');

		// convert JSON response to same format as standard HTML forms

		$request->replace(Arr::undot($request->input('form')));

		$this->request = $request;

		// Just for the test, $page not normally passed in as it’s read from the request
		$this->page = $page;
	}

	public function response() {
		return $this->responses();
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	protected function form() {
		return $this->page->block();
	}


	public function validationRules() {
		return $this->form()->stepValidationRules($this->step);
	}

	public function validate()
	{
		Validator::make($this->request->except(['_token', '_slug']), $this->validationRules(), $this->form()->errorMessages())->validate();
	}
}