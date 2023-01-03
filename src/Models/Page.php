<?php

namespace SalimId\Telegraph\Models;

use SalimId\Telegraph\Exception;
use SalimId\Telegraph\Client;
use SalimId\Telegraph\Model;

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
}
