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

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\EngineResolver;
use Mfn\DocblockNormalize\Parser;
use Mfn\DocblockNormalize\TokenParser;
use Mfn\DocblockNormalize\TokenParserInterface;
use Mfn\Laravel\ViewDocblock\Adapters\CompilerEngine;
use Mfn\Laravel\ViewDocblock\Adapters\PhpEngine;
use Mfn\ArgumentValidation\ExtractFromDocblock;
use Mfn\ArgumentValidation\ArgumentValidation;
use Mfn\ArgumentValidation\TypeDescriptionParser;

class Provider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([$this->configPath() => config_path('view-docblock.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $this->mergeConfigFrom($this->configPath(), 'view-docblock');

        if (
            $this->app->environment() === 'production' &&
            !$app['config']['view-docblock.enable_production']
        ) {
            return;
        }

        $app->bind(TokenParserInterface::class, TokenParser::class);

        # Support additional types via configuration
        $app->singleton(ArgumentValidation::class, function ($app) {
            $validator = new ArgumentValidation(
                TypeDescriptionParser::class,
                $app['config']['view-docblock.report_missing_arguments']
            );
            foreach ($app['config']['view-docblock.additional_types'] as $typeClass => $aliases) {
                $type = $app[$typeClass];
                if (empty($aliases)) {
                    $validator->registerType($type);
                } else {
                    $aliases = !is_array($aliases) ? [$aliases] : $aliases;
                    foreach ($aliases as $alias) {
                        $validator->registerTypeAs($type, $alias);
                    }
                }
            }
            return $validator;
        });

        # From here on mimic behaviour of
        # \Illuminate\View\ViewServiceProvider
        $app->singleton('blade.compiler', function ($app) {
            $cache = $app['config']['view.compiled'];

            return new BladeCompiler($app['files'], $cache);
        });

        # Override default engines
        $app->singleton('view.engine.resolver', function ($app) {
            $resolver = new EngineResolver;

            $resolver->register('php', function () use ($app) {
                return new PhpEngine(
                    $app[Parser::class],
                    $app[ExtractFromDocblock::class],
                    $app[ArgumentValidation::class],
                    $app['config']->get('view-docblock.require_docblock_on_data')
                );
            });
            $resolver->register('blade', function () use ($app) {
                return new CompilerEngine(
                    $app['blade.compiler'],
                    $app[Parser::class],
                    $app[ExtractFromDocblock::class],
                    $app[ArgumentValidation::class],
                    $app['config']->get('view-docblock.require_docblock_on_data')
                );
            });

            return $resolver;
        });
    }

    /**
     * @return string
     */
    public function configPath()
    {
        return __DIR__ . '/../config/view-docblock.php';
    }
}
