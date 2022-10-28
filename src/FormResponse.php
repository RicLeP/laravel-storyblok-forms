<?php

namespace Riclep\StoryblokForms;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use JsonException;
use Riclep\Storyblok\StoryblokFacade as StoryBlok;

class FormResponse
{
	// TODO - pass extra data - imagine a staff contact form wrapped in a component where they select the email address this instance should go to


	/**
	 * @var Request
	 */
	public Request $request;


	/**
	 * The Storyblok page for this form
	 *
	 * @var
	 */
	protected $page;

	/**
	 * You’ll most likely want to extend this class to add your own functionality. The FormResponse
	 * is used when you’ve submitted your form for validation and further processing. It will
	 * output a nested array of fields and the value inputted / selected.
	 *
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
	 * The FormResponse needs to load the form’s page from Storyblok so it can know
	 * all the fields and inputs to expect. By default we pass the page’s slug
	 * with the request so we know what to request
	 *
	 * @return void
	 */
	protected function requestPage(): void
	{
		$this->page = Storyblok::read($this->request->input('_slug'));
	}

	/**
	 * The Field that holds the form on the Page from Storyblok
	 *
	 */
	protected function form()
	{
		return $this->page->form;
	}


	/**
	 * Creates a Laravel Validator taking the request and the rules and messages from the Storyblok
	 * form description. Failing validation redirects the user back to the form / returns a JSON
	 * errorbag if you’re posting asynchronously
	 *
	 * @return void
	 */
	public function validate(): void
	{
		Validator::make($this->request->all(), $this->form()->validationRules(), $this->form()->errorMessages())->validate();
	}


	/**
	 * Get the validation rules for this form. It’ll load the ones defined in Storyblok but you can
	 * override or extend them as needed - just return any valid rules.
	 *
	 * @return mixed
	 */
	public function validationRules(): mixed
	{
		return $this->form()->validationRules();
	}

	/**
	 * Returns the fields and their innput
	 *
	 * @return mixed
	 */
	public function fields(): mixed
	{
		return $this->responses();
	}

	/**
	 * Converts the fields and their input to JSON
	 *
	 * @return false|string
	 * @throws JsonException
	 */
	public function json(): bool|string
	{
		return json_encode($this->fields(), JSON_THROW_ON_ERROR);
	}

	/**
	 * Loops over all the fields and passes the input into them so we can build
	 * a nested array of every field and it’s inputted / selected values
	 *
	 * @return array
	 */
	protected function responses(): array
	{
		$input = $this->request->except(['_token', '_slug']);

		return $this->form()->fields->map(function ($field) use ($input) {

			// Handle empty radio buttons sending nothing in POST request
			//if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
				if (!array_key_exists($field->name, $input)) {
					$input[$field->name] = null;
				}
			//}

			return $field->response($input[$field->name]);
		})->keyBy('name')->toArray();
	}


	/**
	 * Flattens the response returning an array of field responses
	 *
	 * @return array
	 */
	public function flatten(): array
	{
		return Arr::flatten($this->fields());
	}


	/**
	 * Find any uploaded files so we’re easily able to handle them
	 *
	 * @return array
	 */
	public function files(): array
	{
		return array_values(array_filter($this->flatten(), function ($response) {
			return $response instanceof UploadedFile;
		}));
	}
}