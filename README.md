# Example on Laravel

[![Build Status][travis-svg]][travis-link]

Example for using `oidc/core` on Laravel

## Sign In With Apple

Controller:

* [Login](/app/Http/Controllers/SignInWithApple/Login.php)
* [Callback](/app/Http/Controllers/SignInWithApple/Callback.php)

> Notice: `response_mode` must be `form_post`, so that callback route must be `post` method.

Provider:

* [SignInWithApple](/app/OpenIDConnect/Client/Concerns/SignInWithApple.php)

> Notice: Client secret in SignInWithApple is a dynamic JWT string, see code for more information.

OpenID Connect Configuration:

* See [OpenIDConnectDiscover](/app/Console/Commands/OpenIDConnectDiscover.php)

## Line

Controller:

* [Login](/app/Http/Controllers/Line/Login.php)
* [Callback](/app/Http/Controllers/Line/Callback.php)

Provider:

* [Line](/app/OpenIDConnect/Client/Concerns/Line.php)

> Notice: Register new JWK with HS256 algorithm and client key. ID token will sign with HS256 algorithm.

OpenID Connect Configuration:

* See [OpenIDConnectDiscover](/app/Console/Commands/OpenIDConnectDiscover.php)


[travis-svg]: https://travis-ci.com/oidcphp/example-laravel.svg?branch=master
[travis-link]: https://travis-ci.com/oidcphp/example-laravel
