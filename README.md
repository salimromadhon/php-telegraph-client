# PHP Telegraph Client

The PHP Telegraph Client is a set of simple classes to interact with [Telegra.ph](https://telegra.ph/).

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

$account = Account::find('d3b25feccb89e508a9114afb82aa421fe2a9712b963b387cc5ad71e58722');

$account->short_name = 'JD';
$account->save()->revoke();

$page = $account->page()->create(['title' => 'Hello World']);

$pages = $account->pages();

$page = $pages->first()->refresh();
$page->title = 'My First Post';
$page->save();

$account->use();

$page = new Page;
$page->title = 'My Second Post';
$page->save();

$popular = $account->pages()->sortBy('views')->reverse()->first()->refresh();
$popular->title = 'This Post Got ' . $popular->views . ' Views';
$popular->save();
```

## License 
The MIT License (MIT). Please see [License File](LICENSE) for more information.
