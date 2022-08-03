# Monizze Provider for OAuth 2.0 Client

[![Build Status](https://img.shields.io/github/workflow/status/jzecca/oauth2-monizze/CI?logo=github&style=flat-square)](https://github.com/jzecca/oauth2-monizze/actions/workflows/ci.yaml)
[![Coverage](https://img.shields.io/codecov/c/gh/jzecca/oauth2-monizze?logo=codecov&style=flat-square)](https://codecov.io/gh/jzecca/oauth2-monizze)

This package provides [Monizze][monizze] OAuth 2.0 support
for the PHP League's [OAuth 2.0 Client][oauth2-client].

[monizze]: https://www.monizze.be
[oauth2-client]: https://github.com/thephpleague/oauth2-client

## Installation

To install, use composer:

```sh
composer require jzecca/oauth2-monizze
```

## Usage

Usage is the same as The League's OAuth client, using `\Jzecca\OAuth2\Client\Provider\Monizze` as the provider.

#### Available Options

The `Monizze` provider has the following options:

| Name     |   Type   | Default | Description                                              |
|----------|:--------:|:-------:|----------------------------------------------------------|
| `locale` | `string` |  `en`   | Display language of the login UI (supported: `en,fr,nl`) |

## Testing

Tests can be run with:

```sh
composer test
```

Code style can be fixed with:

```sh
composer fix
```
