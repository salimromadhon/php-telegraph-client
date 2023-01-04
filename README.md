# PHP Telegraph Client

The PHP Telegraph Client is a set of simple classes to interact with Telegra.ph.

## Installation

```
composer require salimid/php-telegraph-client
```

## Usage

``` php
use SalimId\Telegraph\Account;
use SalimId\Telegraph\Page;

$account = Account::create([
    'short_name' => 'John',
    'author_name' => 'John Doe',
]);

$page = $account->page()->create(['title' => 'Hello World']);

$pages = $account->pages();

$page = $pages->first();
$page->title = 'My First Post';
$page->save();

$account->use();

$page = new Page;
$page->title = 'My Second Post';
$page->save();
```

## License 
The MIT License (MIT). Please see [License File](LICENSE) for more information.
