# An addon package for [Laravel Storyblok](https://github.com/RicLeP/laravel-storyblok) that lets you use the [Storyblok headless CMS](https://www.storyblok.com/) as a form builder.

### Key Features

- Use the install Artisan command to create the required components in Storyblok
- Add fields and validation rules in Storyblok
- Simple validation
- Outputs collected form data in a structured format

## Installation

This page requires [Laravel Storyblok](https://github.com/RicLeP/laravel-storyblok)

First install the package.

`composer require riclep/laravel-storyblok-forms`

Publish the package assets - this will copy stub views for each form component

`php artisan vendor:publish`

Install the components - this will create the required components and component groups in Storyblok. Ensure you have your management key and space ID set up in the `.env`, see Laravel Storyblok](https://github.com/RicLeP/laravel-storyblok) installation for details.

`php artisan lsf:install`

## Building forms

Each form should be created as a new page in Storyblok using the Form (lsf-form) content type created in the installation step.

Once created attach a form to a page using a Single Option field with a source Stories.

In the `Block` containing your form remember to resolve the relation as you would do for any Laravel Storyblok relationship.

```php
namespace App\Storyblok;

use Riclep\Storyblok\Block;

class SomeBlock extends Block
{
	public $_resolveRelations = ['form']; // the field holding your form
}
```

To render a form do the following in your Blade view. This will use the stubbed fields installed earlier. Feel free to customise them as required.

```blade
{{ $story->form->render() }}
```

By default the form will post to the same URL as it was created on in Storblok so add route to `web.php`. You can customise this action by editing `lsf-form.blade.php`.

```php
Route::post('/forms/my-form', [FormController::class, 'store']);
```

Create your controller!

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Riclep\StoryblokForms\FormResponse;

class FormController extends Controller
{
    public function store(Request $request) {
		$formResponse = new FormResponse($request); // initialise the form
	    $formResponse->validate(); // validate the form - will redirect back with errors like normal
	    $response = $formResponse->response(); // get a formatted response of all the inputs
	}
}
```


## Documentation

Coming soon!

## Future plans

More validation and field types.

### Changelog

[See it here](CHANGELOG.md)

## Contributing

Please feel free to help expand and improve this project. Currently it supports most of the basic usage for block, fields and content. It would be great to add more advanced features and transformations or simply fix bugs.

### Security

If you discover any security related issues, please email ric@wearebwi.com instead of using the issue tracker.

## Credits

- Richard Le Poidevin [GitHub](https://github.com/riclep) / [Twitter](https://twitter.com/riclep)
- [Storyblok](https://www.storyblok.com/)
- [Laravel](https://laravel.com/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
