Forked from : https://github.com/kriswallsmith/Buzz 

Laravel : Buzz is a lightweight PHP 5.3 library for issuing HTTP requests.


### Installing via Composer

Update your project's composer.json file to include Buzz:

```javascript
{
    "require": {
        "sirsquall/buzz": "v0.12"
    }
}
```
Run the Composer update comand

    $ composer update


In your `config/app.php` add `'Buzz\BuzzServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Buzz\BuzzServiceProvider',

    ),

At the end of `config/app.php` add `'Buzz'    => 'Buzz\Buzz'` to the `$aliases` array

    'aliases' => array(

        'App'        => 'Illuminate\Support\Facades\App',
        'Artisan'    => 'Illuminate\Support\Facades\Artisan',
        ...
       'Buzz'            => 'Buzz\Buzz',

    ),

To override the default configuration options you can publish the config file.

    php artisan config:publish sirsquall/buzz

You may now edit these options at app/config/packages/sirsquall/buzz/config.php.

```php
<?php

$response = Buzz::get('http://www.google.com');
echo $response;
echo $response->getContent;
```

You can also use the low-level HTTP classes directly.

```php
<?php

$request = new Buzz\Message\Request('HEAD', '/', 'http://google.com');
$response = new Buzz\Message\Response();

$client = new Buzz\Client\FileGetContents();
$client->send($request, $response);

echo $request;
echo $response;
```

