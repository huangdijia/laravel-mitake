# SMS gateway for mitake

[![Latest Stable Version](https://poser.pugx.org/huangdijia/laravel-mitake/version.png)](https://packagist.org/packages/huangdijia/laravel-mitake)
[![Total Downloads](https://poser.pugx.org/huangdijia/laravel-mitake/d/total.png)](https://packagist.org/packages/huangdijia/laravel-mitake)

## Requirements

* PHP >= 7.0
* Laravel >= 5.5

## Installation

First, install laravel 5.5, and make sure that the database connection settings are correct.

~~~bash
composer require huangdijia/laravel-mitake
~~~

Then run these commands to publish config

~~~bash
php artisan vendor:publish --provider="Huangdijia\Mitake\MitakeServiceProvider"
~~~

## Configurations

~~~php
// config/mitake.php
    'username' => 'your account',
    'password' => 'your password',
~~~

## Usage

### As Facade

~~~php
use Huangdijia\Mitake\Facades\Mitake;

...

if (!Mitake::send('mobile', 'some message')) {
    echo Mitake::getError();
    echo Mitake::getErrno();
} else {
    echo "send success";
}

~~~

### As Command

~~~bash
php artisan mitake:send 'mobile' 'some message'
# send success
# or
# error
~~~

### As Helper

~~~php
if (!mitake()->send('mobile', 'some message')) {
    echo mitake()->getError();
    echo mitake()->getErrno();
} else {
    echo "send success";
}
if (!$error = mitake_send('mobile', 'some message')) {
    echo $error;
} else {
    echo "send success";
}
~~~

## Other

> * https://www.mitake.com.tw/

## License

laravel-mitake is licensed under The MIT License (MIT).