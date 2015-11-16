<?php namespace Mfn\Laravel\ViewDocblock\Types;

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

use Illuminate\Support\Collection;
use Mfn\ArgumentValidation\Exceptions\TypeError;
use Mfn\ArgumentValidation\Interfaces\TypeDescriptionParserInterface;
use Mfn\ArgumentValidation\Interfaces\TypeValidatorInterface;
use Mfn\ArgumentValidation\Types\AbstractType;

class CollectionType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return Collection::class;
    }

    /**
     * Perform the actual validation of a type.
     *
     * To signal a type error, throw a TypeError exception
     *
     * @param TypeValidatorInterface $validator
     *   Allows further validation for collection types
     * @param TypeDescriptionParserInterface $innerTypeDescriptor
     *   If the validated type supports collections, further type information
     *   will be available here. Note that it may be empty as well, so be sure
     *   to check for that.
     * @param mixed $values The value to validate
     * @throws TypeError
     */
    public function validate(
        TypeValidatorInterface $validator,
        TypeDescriptionParserInterface $innerTypeDescriptor,
        $values
    ) {
        if (!($values instanceof Collection)) {
            throw new TypeError(
                sprintf('Expected %s but %s received',
                    Collection::class,
                    $this->getPhpTypeDescription($values)
                )
            );
        }

        if (!empty($innerTypeDescriptor)) {
            foreach ($values as $value) {
                $validator->validateTypeDescriptor($innerTypeDescriptor,
                    $value);
            }
        }
    }
}
