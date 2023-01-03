<?php

namespace SalimId\Telegraph\Models;

use SalimId\Telegraph\Exception;
use SalimId\Telegraph\Client;
use SalimId\Telegraph\Model;

class Account extends Model
{
    /**
     * The key.
     *
     * @var string
     */
    protected string $key = 'access_token';

    /**
     * List of available attributes.
     *
     * @var array<string>
     */
    protected array $attributes = [
        'access_token',
        'short_name',
        'author_name',
        'author_url',
        'auth_url',
        'page_count',
    ];

    /**
     * List of fillable attributes.
     *
     * @var array<string>
     */
    protected array $fillable = [
        'short_name',
        'author_name',
        'author_url',
    ];

    /**
     * Get URL for showing model.
     *
     * @param string|null $identifier
     * @return string
     */
    protected function getShowUrl(?string $identifier = null): string
    {
        return '/getAccountInfo';
    }

    /**
     * Get params for showing models.
     *
     * @param string|null $identifier
     * @return array<string, mixed>
     */
    protected function getShowParams(?string $identifier = null): array
    {
        return [
            $this->key => $identifier ?? $this->id(),
        ];
    }

    /**
     * Get URL for creating model.
     *
     * @return string
     */
    protected function getCreateUrl(): string
    {
        return '/createAccount';
    }

    /**
     * Get params for creating models.
     *
     * @return array<string, mixed>
     */
    protected function getCreateParams(): array
    {
        return [
            'short_name' => $this->short_name ?? 'My Account',
        ];
    }

    /**
     * Get URL for updating model.
     *
     * @return string
     */
    protected function getUpdateUrl(): string
    {
        return '/editAccountInfo';
    }

    /**
     * Get default params.
     *
     * @return array<string, mixed>
     */
    protected function getDefaultParams(): array
    {
        return [
            'fields' => ['short_name', 'author_name', 'author_url', 'auth_url', 'page_count'],
        ];
    }

    /**
     * User the token.
     *
     * @return static
     */
    public function use(): static
    {
        if (empty($this->id())) {
            throw new Exception('Cannot use account without token.');
        }

        Client::token($this->id());

        return $this;
    }
}
