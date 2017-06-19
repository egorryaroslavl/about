# About form

Installation
------------

```
composer require egorryaroslavl/about 
```

Then add ServiceProviders

``` 
  'providers' => [
    // ...
    Egorryaroslavl\About\AboutServiceProvider::class,
    Collective\Html\HtmlServiceProvider::class,
    Barryvdh\Elfinder\ElfinderServiceProvider::class,
    // ...
  ],
```
and aliases 

``` 
  'aliases' => [
    // ...
      'Form' => Collective\Html\FormFacade::class,
      'Html' => Collective\Html\HtmlFacade::class,
    // ...
  ],
``` 
and run
``` 
php artisan vendor:publish 
```


And after all, run this...

```
php artisan migrate
```

 
 