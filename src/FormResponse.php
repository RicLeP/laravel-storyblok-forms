<?php

namespace Riclep\StoryblokForms;

use Illuminate\Http\Request;
use Riclep\Storyblok\StoryblokFacade as StoryBlok;

class FormResponse
{
	// TODO - pass extra data - imagine a staff contact form wrapped in a component where they select the address this instance should go to


	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->requestPage();
	}


	protected function requestPage() {
		$this->page = Storyblok::read($this->request->input('_slug'));
	}

	protected function form() {
		return $this->page->form;
	}

	public function validate() {
		$this->request->validate($this->form()->validationRules(), $this->form()->errorMessages());
	}

	public function response() {
		return $this->responses();
	}

	public function json() {
		return json_encode($this->response());
	}

	protected function responses() {
		$input = $this->request->input();

		return $this->form()->flattenFields()->map(function ($field) use ($input) {
			return [
				'label' => $field->label,
				'response' => $field->response($input[$field->name]),
			];
		})->toArray();
	}
}