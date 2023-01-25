# WebmunkeezElasticBundle

This bundle unleashes a internationalization on Symfony applications.

## Installation

Use Composer to install this bundle:

```console
$ composer require webmunkeez/elastic-bundle
```

Add the bundle in your application kernel:

```php
// config/bundles.php

return [
    // ...
    Webmunkeez\ElasticBundle\WebmunkeezElasticBundle::class => ['all' => true],
    // ...
];
```