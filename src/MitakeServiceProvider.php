<?php

namespace Huangdijia\Mitake;

use Illuminate\Support\ServiceProvider;

class MitakeServiceProvider extends ServiceProvider
{
    // protected $defer = true;

    protected $commands = [
        Console\SendCommand::class,
        Console\InstallCommand::class,
    ];

    public function boot()
    {
        $this->bootConfig();

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/mitake.php' => $this->app->basePath('config/mitake.php')], 'config');
        }
    }

    public function register()
    {
        $this->app->singleton(Mitake::class, function () {
            return new Mitake($this->app['config']->get('mitake'));
        });

        $this->app->alias(Mitake::class, 'sms.mitake');

        $this->commands($this->commands);
    }

    public function bootConfig()
    {
        $path = __DIR__ . '/../config/mitake.php';

        $this->mergeConfigFrom($path, 'mitake');
    }

    public function provides()
    {
        return [
            Mitake::class,
            'sms.mitake',
        ];
    }
}
