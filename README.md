# An addon package for [Laravel Storyblok](https://github.com/RicLeP/laravel-storyblok) that lets you use the [Storyblok headless CMS](https://www.storyblok.com/) as a form builder.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/riclep/laravel-storyblok-forms.svg?style=flat-square)](https://packagist.org/packages/riclep/laravel-storyblok-forms)
[![Build](https://img.shields.io/scrutinizer/build/g/riclep/laravel-storyblok-forms?style=flat-square)](https://scrutinizer-ci.com/g/riclep/laravel-storyblok-forms)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/riclep/laravel-storyblok-forms?style=flat-square)](https://scrutinizer-ci.com/g/riclep/laravel-storyblok-forms)
[![Total Downloads](https://img.shields.io/packagist/dt/riclep/laravel-storyblok-forms.svg?style=flat-square)](https://packagist.org/packages/riclep/laravel-storyblok-forms)
[![Twitter](https://img.shields.io/twitter/follow/riclep.svg?style=social&label=Follow)](https://twitter.com/intent/follow?screen_name=riclep)


## Installation

This page requires [Laravel Storyblok](https://github.com/RicLeP/laravel-storyblok)

First install the package.

`composer require riclep/laravel-storyblok-forms`

Publish the package assets - this will copy stub views for each form component

`php artisan vendor:publish`

Install the components - this will create the required components and component groups in Storyblok. Ensure you have your management key and space ID set up in the `.env`, see [Laravel Storyblok](https://github.com/RicLeP/laravel-storyblok) installation for details.

`php artisan lsf:install`

## Documentation

[Read the docs](https://ls.sirric.co.uk/docs/2.11/laravel-storyblok-forms)

[Contribute to the docs](https://github.com/RicLeP/laravel-storyblok-docs/)

## Key Features

- Use the install Artisan command to create the required components in Storyblok
- Add fields and validation rules in Storyblok
- Simple validation
- Outputs collected form data in a structured format

## Future plans

More validation and field types.

### Changelog

[See it here](CHANGELOG.md)

## Contributing

Please feel free to help expand and improve this project. The package uses Git Flow but you can submit a pull request to be merged to the develop branch.

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
