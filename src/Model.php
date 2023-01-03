<?php

namespace SalimId\Telegraph;

use SalimId\Telegraph\Exceptions\Exception;

class Model
{
    /**
     * The key.
     *
     * @var string
     */
    protected string $key;

    /**
     * List of available attributes.
     *
     * @var array<string>
     */
    protected array $attributes = [];

    /**
     * List of fillable attributes.
     *
     * @var array<string>
     */
    protected array $fillable = [];

    /**
     * Data container.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Indicate if it's on creating mode, otherwise it's updating.
     *
     * @var boolean
     */
    protected bool $creating = true;

    /**
     * Instantiate model.
     *
     * @param array<string, mixed> $data
     */
    final public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Attribute getter.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    /**
     * Attribute setter.
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set(string $name, $value)
    {
        if (in_array($name, $this->fillable)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * Get debug info.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return $this->data;
    }

    /**
     * Get the key.
     *
     * @return string|null
     */
    public function key(): ?string
    {
        return $this->key ?? null;
    }

    /**
     * Get the ID.
     *
     * @return string
     */
    public function id(): ?string
    {
        return isset($this->key) ? $this->{$this->key} : null;
    }

    /**
     * Fill data.
     *
     * @param array<string, mixed> $data
     * @return static
     */
    public function fill(array $data): static
    {
        foreach ($data as $name => $value) {
            if (is_string($name)) {
                $this->{$name} = $value;
            }
        }

        return $this;
    }

    /**
     * Fill raw data.
     *
     * @param array<string, mixed> $data
     * @return static
     */
    protected function fillRaw(array $data): static
    {
        foreach ($data as $name => $value) {
            if (is_string($name)) {
                $this->data[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * Get models.
     *
     * @param array<string, mixed> $params
     * @return array<static>
     */
    public static function get(array $params = []): array
    {
        return [];
    }

    /**
     * Create a model.
     *
     * @param array<string, mixed> $data
     * @return static
     */
    public static function create(array $data = []): static
    {
        $model = new static($data);
        $model = $model->save();
        $model->creating = false;

        return $model;
    }

    /**
     * Find a model.
     *
     * @param string $identifier
     * @return static|null
     */
    public static function find(string $identifier): ?static
    {
        try {
            $model = new static;
            $model->creating = false;
            $url = $model->getShowUrl($identifier);
    
            $params = array_merge(
                $model->getDefaultParams(),
                $model->getShowParams($identifier),
                $model->data,
            );

            $data = Client::request('POST', $url, [
                'json' => $params,
            ]);

            $model->fillRaw($data);

            $model->data[$model->key] = $identifier;

            return $model;
        } catch (Exception $e) {
            // Do nothing.
        }

        return null;
    }

    /**
     * Fill and save data.
     *
     * @param array<string, mixed> $data
     * @return static
     */
    public function update(array $data): static
    {
        return $this->fill($data)->save();
    }

    /**
     * Save model.
     */
    public function save(): static
    {
        $url = $this->creating ? $this->getCreateUrl() : $this->getUpdateUrl();

        $params = array_merge(
            $this->getDefaultParams(),
            $this->creating ? $this->getCreateParams() : $this->getUpdateParams(),
            $this->data,
        );

        $data = Client::request('POST', $url, [
            'json' => $params,
        ]);

        $this->creating = false;

        return $this->fillRaw($data);
    }

    /**
     * Refresh model.
     *
     * @return static
     */
    public function refresh(): static
    {
        try {
            $url = $this->getShowUrl();
    
            $params = array_merge(
                $this->getDefaultParams(),
                $this->getShowParams(),
                $this->data,
            );

            $data = Client::request('POST', $url, [
                'json' => $params,
            ]);
    
            return $this->fillRaw($data);
        } catch (Exception $e) {
            // Do nothing.
        }

        return $this;
    }

    /**
     * Get default params.
     *
     * @return array<string, mixed>
     */
    protected function getDefaultParams(): array
    {
        return [];
    }

    /**
     * Get URL for indexing model.
     *
     * @return string
     */
    protected function getIndexUrl(): string
    {
        return '';
    }

    /**
     * Get params for indexing models.
     *
     * @return array<string, mixed>
     */
    protected function getIndexParams(): array
    {
        return [];
    }

    /**
     * Get URL for showing model.
     *
     * @param string|null $identifier
     * @return string
     */
    protected function getShowUrl(?string $identifier = null): string
    {
        return '';
    }

    /**
     * Get params for showing models.
     *
     * @param string|null $identifier
     * @return array<string, mixed>
     */
    protected function getShowParams(?string $identifier = null): array
    {
        return [];
    }

    /**
     * Get URL for creating model.
     *
     * @return string
     */
    protected function getCreateUrl(): string
    {
        return '';
    }

    /**
     * Get params for creating models.
     *
     * @return array<string, mixed>
     */
    protected function getCreateParams(): array
    {
        return [];
    }

    /**
     * Get URL for updating model.
     *
     * @return string
     */
    protected function getUpdateUrl(): string
    {
        return '';
    }

    /**
     * Get params for updating models.
     *
     * @return array<string, mixed>
     */
    protected function getUpdateParams(): array
    {
        return [
            $this->key() => $this->id(),
        ];
    }
}
