<?php namespace Mfn\Laravel\ViewDocblock;

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

class ArgumentValidationException extends \RuntimeException
{
    /**
     * @var string[]
     */
    protected $errors;

    /**
     * @var string Absolute filename
     */
    protected $path;

    /**
     * @param string $path
     * @param string[] $errors
     */
    public function __construct($path, array $errors)
    {
        $this->path = $path;
        $this->errors = $errors;

        parent::__construct($this->getValidationErrorsAsMessage());
    }

    /**
     * @return string
     */
    protected function getValidationErrorsAsMessage()
    {
        $msg = "Parameter validation error in file $this->path:\n";
        $msg .= join("\n", array_map(
                function ($error) {
                    return '    ' . $error;
                },
                $this->errors
            )
        );
        $msg .= "\n";

        return $msg;
    }
}
