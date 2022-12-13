<?php

namespace App\Presenter;

use App\Presenter\Contracts\Presentable;

abstract class Presenter
{
    /**
     * @var object
     */
    protected Presentable $entity;

    /**
     * @param  object  $entity
     */
    public function __construct(Presentable $entity = null)
    {
        if (! is_null($entity)) {
            $this->presentEntity($entity);
        }
    }

    public function presentEntity(Presentable $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Allow for property-style retrieval.
     *
     * @param $property
     * @return mixed
     */
    public function __get(string $property)
    {
        if (method_exists($this, $property)) {
            return $this->{$property}();
        }

        if (is_null($this->entity)) {
            throw new Exception('Presentable Entity must be provided.');
        }

        return $this->entity->{$property};
    }

    public function __call(string $method, array $args)
    {
        if (method_exists($this, $method)) {
            return $this->{$method}(...$args);
        }

        if (is_null($this->entity)) {
            throw new Exception('Presentable Entity must be provided.');
        }

        return $this->entity->{$method}(...$args);
    }

    /**
     * Provide compatibility for the checking.
     *
     * @param $property
     * @return bool
     */
    public function __isset($property)
    {
        return property_exists($this, $property) || property_exists($this->entity, $property);
    }
}
