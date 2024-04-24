<p style="text-align:center">
<img src="public/images/dadehpardaz.png" width="100" alt="DadehPardaz">
</p>

<h1 style="text-align:center">DadehPardaz</h1>

# About Project

This is a test project that is not intended to be used in production environments.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Used Technologies

- [Laravel](https://laravel.com) as the main framework
- [MariaDB](https://mariadb.org/) as database management system
- [Filament](https://filamentphp.com) for admin panel
- [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) for Swagger (OpenAPI) documentation
- [JWT](https://github.com/lcobucci/jwt) for API token generation
- [Composer Merge Plugin](https://github.com/wikimedia/composer-merge-plugin) to merge composer.json requirements from internal packages

## Installation Steps

1. Do `composer install`
2. Copy `.env.example` to `.env`
3. Run `php artisan key:generate`
4. Execute `php artisan migrate:fresh --seed`
5. Start the server by using `php artisan serve`

## Web Form

Web form is available via [http://127.0.0.1:8000/payment](http://127.0.0.1:8000/payment)

## API

Swagger (OpenAPI) is accessible via [http://127.0.0.1:8000/openapi](http://127.0.0.1:8000/openapi)

## Admin Panel

Admin panel can be used via [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

Default admin user is:

- Mobile: `09123456789`
- Password: `123456`
