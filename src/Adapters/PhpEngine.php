<?php namespace Mfn\Laravel\ViewDocblock\Adapters;

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

use Mfn\DocblockNormalize\Parser;
use Mfn\Laravel\ViewDocblock\ArgumentValidationInterface;
use Mfn\Laravel\ViewDocblock\ArgumentValidationTrait;
use Mfn\ArgumentValidation\ExtractFromDocblock;
use Mfn\ArgumentValidation\ArgumentValidation;

class PhpEngine extends \Illuminate\View\Engines\PhpEngine implements ArgumentValidationInterface
{
    use ArgumentValidationTrait;
    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var ExtractFromDocblock
     */
    protected $extractor;

    /**
     * @var ArgumentValidation
     */
    protected $validator;

    /**
     * @var bool
     */
    protected $requireValidationOnData = false;

    /**
     * @var string[]
     */
    protected $argumentBlacklist = [];

    /**
     * @param Parser $parser
     * @param ExtractFromDocblock $extractor
     * @param ArgumentValidation $validator
     * @param bool $requireValidationOnData
     * @param string[] $argumentBlacklist
     */
    public function __construct(
        Parser $parser,
        ExtractFromDocblock $extractor,
        ArgumentValidation $validator,
        $requireValidationOnData = false,
        $argumentBlacklist = []
    ) {
        $this->parser = $parser;
        $this->extractor = $extractor;
        $this->validator = $validator;
        $this->requireValidationOnData = $requireValidationOnData;
        $this->argumentBlacklist = $argumentBlacklist;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @param  string $path
     * @param  array $data
     * @return string
     * @throws \Exception
     */
    public function get($path, array $data = [])
    {
        $arguments = $data;

        foreach ($this->argumentBlacklist as $argument) {
            unset($arguments[$argument]);
        }

        $this->validate($path, $arguments);

        return parent::get($path, $data);
    }
}
