<?php

use SalimId\Telegraph\Account;

$account = Account::find('ce4c2c49e7205b2e8418848054a422181f84be1b68d660f596580a4eaa28');

// Data to be used.
$title = 'This is the new title.';

if ($account) {
    $page = $account->pages()[0] ?? null;

    if ($page) {
        $page->refresh()->update(['title' => $title]);
    }
}

if (isset($page)) {
    // Done.
    var_dump($page);
}
