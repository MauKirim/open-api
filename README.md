# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maukirim/open-api.svg?style=flat-square)](https://packagist.org/packages/maukirim/open-api)
[![Total Downloads](https://img.shields.io/packagist/dt/maukirim/open-api.svg?style=flat-square)](https://packagist.org/packages/maukirim/open-api)
![GitHub Actions](https://github.com/maukirim/open-api/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require maukirim/open-api
```

publish configuration file

```bash
php artisan vendor:publish --provider="MauKirim\OpenApi\OpenApiServiceProvider"
```

add to your .env

```bash

MAUKIRIM_TOKEN=your_token

```

to get your token, you can register at [maukirim.com](https://maukirim.com) or contact us
at [whatsapp](https://wa.me/6285792071380)

we can provide free trial for 30 days

## Usage

Send a plain message to whatsapp

```php
use MauKirim\OpenApi;

$openApi = OpenApi::init(10); // 10 is the number timeout
$openApi->send(
    '628xxxxxx',
    'Hello World *hii*',
)
```

send a plain message with button

```php
use MauKirim\OpenApi;
$openApi->send(
    '628xxxxxx',
    'Hello World *hii*',
    [
        [
            'id' => '1',
            'text' => 'Button 1',
            'url' => 'your_url',
        ]
    ]
)
```

send a message with image

```php
use MauKirim\OpenApi;
$file = $request->file('image');
$openApi->sendImage(
    '628xxxxxx',
    'Hello World *hii*',
    $file
)
```

send a message with document

```php
use MauKirim\OpenApi;
$file = $request->file('document');
$openApi->sendDocument(
    '628xxxxxx',
    'Hello World *hii*',
    $file
)
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email info@maukirim.com instead of using the issue tracker.

## Credits

- [Indra Gunanda](https://github.com/maukirim)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
