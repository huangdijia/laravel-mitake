<?php

namespace Huangdijia\Mitake;

use Huangdijia\Mitake\Console\InfoCommand;
use Huangdijia\Mitake\Console\SendCommand;
use Illuminate\Support\ServiceProvider;

class MitakeServiceProvider extends ServiceProvider
{
    protected $defer = true;

    protected $commands = [
        SendCommand::class,
        // InfoCommand::class,
    ];

    public function boot()
    {
        $this->bootConfig();

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/config.php' => $this->app->basePath('config/mitake.php')]);
        }
    }

    public function register()
    {
        $this->app->singleton(Mitake::class, function () {
            return new Mitake(config('mitake'));
        });

        $this->app->alias(Mitake::class, 'sms.mitake');

        $this->commands($this->commands);
    }

    public function bootConfig()
    {
        $path = __DIR__ . '/../config/config.php';

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
