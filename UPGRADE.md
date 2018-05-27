# Upgrade Guide

## Upgrading to 2.0 from 1.0

### Updating Dependencies

Update the `sfelix-martins/passport-multiauth` dependency to `^2.0` in your `composer.json` file.

### Update Configs

If you are not using Laravel 5.5 version change the `JsonHandlerServiceProvider` from your `config/app.php` providers array:

```php
    'providers' => [
        ...
        SMartins\Exceptions\JsonHandlerServiceProvider::class,
    ],
```
