<?php

namespace App\Storyblok\Forms;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FormResponse extends \Riclep\StoryblokForms\FormResponse
{
	public function __construct(Request $request)
	{
		if ($request->isJson()) {
			$request->replace(Arr::undot($request->all()));
		}

		parent::__construct($request);
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	protected function form() {
		return $this->page->form;
	}

	public function response() {
		return $this->responses();
	}
}