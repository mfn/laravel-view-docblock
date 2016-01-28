# Laravel View Docblock [ ![Travis Build Status](https://travis-ci.org/mfn/php-laravel-view-docblock.svg?branch=master)](https://travis-ci.org/mfn/php-laravel-view-docblock)

Homepage: https://github.com/mfn/php-laravel-view-docblock

# Blurb

Add a docblock to your view, describing what parameters and types the template
accepts and let the library automatically handle the argument type validation.

Never fear of a) now knowing *what* arguments a template accepts and b) what
*types* they should be.

# Requirements

PHP 5.6 / Laravel 5.0/5.1

# Install / Setup

Using composer: `composer.phar require mfn/laravel-view-docblock 0.1`

Register the service provider in your `config/app.php` by add this line to your
`providers` entry: `Mfn\Laravel\ViewDocblock\Provider::class`

Note: it is advisable to register the provider *after* the laravel framework
providers and *before* your custom ones.

Publish the configuration:

`php artisan vendor:publish --provider="Mfn\Laravel\ViewDocblock\Provider"`

# Example

Instead of:
```PHP
<div class="box-body">
    <div class="box-body">
        <div class='form-group{{ $errors->has("{$lang}.title") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[title]", trans('page::pages.form.title')) !!}
```
you add a docblock, specifying what parameters and types are accepted:
```PHP
<?php
use Illuminate\Support\ViewErrorBag;
/**
 * @param ViewErrorBag $errors
 * @param string $lang
 */
?>
<div class="box-body">
    <div class="box-body">
        <div class='form-group{{ $errors->has("{$lang}.title") ? ' has-error' : '' }}'>
            {!! Form::label("{$lang}[title]", trans('page::pages.form.title')) !!}
```
For a list of supported defaul types, please see https://github.com/mfn/php-parameter-validation

# Configuration

- `enable_production`: Set to true to enable validation in production too. Defaults to `false`.

- `require_docblock_on_data`: Set to true to *require* a docblock on every template file. Defaults to `false`.

- `report_missing_arguments`: Set to true to report missing parameter types as errors. Defaults to `false`.

- `argument_blacklist`: A list of variables which should automatically be **excluded** from the validation. Useful for global or internal variables which are available in all templates.

- `additional_types`: register additional validation types, specific for Laravel:
  - `CollectionType`: supports `Collection<SomeObject>`. Note: if an inner object type is provided (`SomeObject` in this example), the collection is iterated to ensure the types match!
  - `TraversableType`: transparently supports any class implementing the `Traversable` interface like an array. Note: inner types are ignored when encountering such a type.

The default settings are for easy getting started/integrating into existing
projects. To take full effect of the library, the recommended settings are:

- `require_docblock_on_data` => `true`
- `report_missing_arguments` => `true`

However, undertand that Laravel is notorious in passing "global" variables to
all templates, e.g. `$errors` is supposedly always available, which may render
the option `report_missing_arguments` being set to `true` quite annoying.

# Contribute

Fork it, hack on a feature branch, create a pull request, be awesome!

No developer is an island so adhere to these standards:

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)

© Markus Fischer <markus@fischer.name>
