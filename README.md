# WebScraper

It's an example of Web Scraper created with Symfony 3 and tested with PHPUnit.
A unit test uses mocked version of web pages and doesn't call any external resources.

### To install project

```sh
$ composer install
```

### Exposed end-point:

```sh
/api/products
```

### Console Command:

```sh
php bin/console api:products
```

### To run PHP Unit test

```sh
$ phpunit
```