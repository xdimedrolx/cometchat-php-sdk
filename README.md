<h1 align="center">🚧 xdimedrolx/cometchat-php-sdk 🚧</h1>

<p align="center">
    <strong>A PHP client for CometChat chat.</strong>
</p>

CometChat enables you to add voice, video & text chat for your website & app.

## Installation

The preferred method of installation is via [Composer][]. Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require xdimedrolx/cometchat-php-sdk symfony/http-client nyholm/psr7
```

## Usage

```php
use ComentChat\Chat\CometChat;

$client = CometChat::create($appId, $region, $apiKey);

$user = $client->user()->get('superhero')->getData();
```

## TODO

- [ ] Users
- [ ] Groups
- [ ] Auth Tokens
- [ ] Members
- [ ] Messages

[composer]: http://getcomposer.org/