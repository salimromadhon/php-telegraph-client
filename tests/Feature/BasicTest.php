<?php

namespace Tests\Unit;

use SalimId\Telegraph\Account;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testBasic()
    {
        // Create an account.
        $account = Account::create([
            'short_name' => 'Author',
            'author_name' => 'The Awesome Author',
        ]);

        $this->assertEquals('Author', $account->short_name);
        $this->assertEquals('The Awesome Author', $account->author_name);

        // Create a page and edit it.
        $page = $account->page()->create(['title' => 'My Awesome Writing']);
        $this->assertEquals('My Awesome Writing', $page->title);
        $this->assertEquals('My Awesome Writing', $page->refresh()->title);

        $page = $account->page($page->path);
        $page->title = 'My Super Awesome Writing';
        $page->save();
        $this->assertEquals('My Super Awesome Writing', $page->title);
        $this->assertEquals('My Super Awesome Writing', $page->refresh()->title);

        // Get pages.
        $pages = $account->pages();
        $this->assertTrue($pages->hasItems());
        $this->assertEquals($page->path, $pages->first()->path);
    }
}
