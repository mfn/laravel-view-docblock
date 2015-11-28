<?php

/*
 * This file is part of https://github.com/mfn/php-laravel-view-docblock
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Markus Fischer <markus@fischer.name>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use Mfn\Laravel\ViewDocblock\Types\CollectionType;
use Mfn\ArgumentValidation\Types\Array_\TraversableType;

return [

    /*
    |--------------------------------------------------------------------------
    | Enable or disable in production
    |--------------------------------------------------------------------------
    |
    | In general, this feature naturally incurs an overhead. Depending on your
    | needs regarding type safety you may want to enable this in production.
    |
    | The defaults are to err on the safe side and not enable them by default.
    |
    */
    'enable_production' => false,

    /*
    |--------------------------------------------------------------------------
    | Make the docblock required for validation
    |--------------------------------------------------------------------------
    |
    | By default a missing docblock results in no validation. If you want to
    | enforce that every template requires a docblock once data has been
    | passed to it, set to true.
    |
    */
    'require_docblock_on_data' => false,

    /*
    |--------------------------------------------------------------------------
    | Every argument passed must be noted in the docblock
    |--------------------------------------------------------------------------
    |
    | The ultimate integrity switch. If true, every argument passed must be
    | noted in the docblock otherwise an exception is thrown.
    |
    */
    'report_missing_arguments' => false,

    /*
    |--------------------------------------------------------------------------
    | Blacklist variables from being passed to the validator
    |--------------------------------------------------------------------------
    |
    | Some variables may be deemed internal to the respective template engine
    | implementation, some may be global defaults however you don't want to
    | necessarily document them in every template.
    |
    */
    'argument_blacklist' => [
        '__env',
        'app',
        'obLevel',
    ],

    /*
    |--------------------------------------------------------------------------
    | Register additional types for parameter validation.
    |--------------------------------------------------------------------------
    |
    | They key is the class to instantiate and the value is an array of string
    | of names to register the type as. If you leave this empty, the default
    | name/alias will be used for registering.
    |
    */
    'additional_types' => [
        CollectionType::class => null,
        TraversableType::class => 'array',
    ],
];
