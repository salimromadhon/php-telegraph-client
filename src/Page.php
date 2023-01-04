<?php

namespace SalimId\Telegraph;

use SalimId\Telegraph\Exception;
use SalimId\Telegraph\Client;
use SalimId\Telegraph\Model;
use SalimId\Kit\Collection;

class Page extends Model
{
    /**
     * The key.
     *
     * @var string
     */
    protected string $key = 'path';

    /**
     * List of available attributes.
     *
     * @var array<string>
     */
    protected array $attributes = [
        'path',
        'url',
        'title',
        'description',
        'author_name',
        'author_url',
        'image_url',
        'content',
        'views',
        'can_edit',
    ];

    /**
     * List of fillable attributes.
     *
     * @var array<string>
     */
    protected array $fillable = [
        'title',
        'content',
        'author_name',
        'author_url',
        'image_url',
    ];

    /**
     * Get URL for showing model.
     *
     * @param string|null $identifier
     * @return string
     */
    protected function getShowUrl(?string $identifier = null): string
    {
        return '/getPage/' . ($identifier ?? $this->id());
    }

    /**
     * Get URL for creating model.
     *
     * @return string
     */
    protected function getCreateUrl(): string
    {
        return '/createPage';
    }

    /**
     * Get params for creating models.
     *
     * @return array<string, mixed>
     */
    protected function getCreateParams(): array
    {
        return [
            'title' => $this->title ?? date('Y-m-d'),
            'content' => $this->content ?? ['Content goes here...'],
        ];
    }

    /**
     * Get URL for updating model.
     *
     * @return string
     */
    protected function getUpdateUrl(): string
    {
        return '/editPage';
    }

    /**
     * Get default params.
     *
     * @return array<string, mixed>
     */
    protected function getDefaultParams(): array
    {
        return [
            'return_content' => true,
        ];
    }

    /**
     * Get pages.
     *
     * @param integer|null $limit
     * @param integer $offset
     * @return Collection<Page>
     */
    public static function get(?int $limit = null, int $offset = 0): Collection
    {
        if (empty($limit)) {
            return static::all();
        }

        $data = Client::request('POST', '/getPageList', [
            'json' => compact('offset', 'limit'),
        ]);

        if (!isset($data['pages']) || !is_array($data['pages'])) {
            throw new Exception('Failed to get pages.');
        }

        $pages = $data['pages'];

        foreach ($pages as &$page) {
            $page = (new static)->fillRaw($page);
        }

        return new Collection($pages);
    }

    /**
     * Get all pages.
     *
     * @return Collection<Page>
     */
    protected static function all()
    {
        $all = new Collection;

        $limit = 50;
        $iteration = 0;

        do {
            $pages = static::get($limit, $limit * $iteration++);

            $all = $all->merge($pages);
        } while ($pages->hasItems());

        return $all;
    }
}
