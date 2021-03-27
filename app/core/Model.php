<?php

namespace Gov\Core;

use Gov\Core\Database;

class Model
{
    protected $database;

    protected $table;

    protected $column = [];

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function all()
    {
        return $this->database
            ->prepare(sprintf('SELECT * FROM %s', $this->table))
            ->fetchAll(static::class);
    }

    public function find($id, string $column = 'id')
    {
        return $this->database
            ->prepare(sprintf('SELECT * FROM %s WHERE id=:id', $this->table))
            ->bind($column, $id)
            ->fetch(static::class);
    }

    public function save(?array $data = null)
    {
        $syntax = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            join(', ', $this->allow),
            ':' . join(', :', $this->allow)
        );

        $statement = $this->database->prepare($syntax);

        foreach ($this->allow as $column) {
            if ($data !== null && !isset($data[$column])) {
                throw new \RuntimeException(sprintf(
                    "Inserting into table %s must contain value for column %s.",
                    $this->table,
                    $column
                ));
            }

            if ($data === null && !property_exists($this, $column)) {
                throw new \RuntimeException(sprintf(
                    "Inserting into table %s must contain value for column %s.",
                    $this->table,
                    $column
                ));
            }

            $statement->bind($column, $data === null ? $this->{$column} : $data[$column]);
        }

        return $statement->execute();
    }

    public function update(?array $data = null, ?string $identifier = null, string $columnIdentifier = 'id')
    {
        $id = $this->id ?? $identifier;

        $syntax = sprintf(
            'UPDATE %s SET %s WHERE %s=:%s',
            $this->table,
            join(', ', array_map(function ($column) {
                return sprintf('%s=:%s', $column, $column);
            }, $this->allow)),
            $columnIdentifier,
            $columnIdentifier
        );

        $statement = $this->database->prepare($syntax);

        foreach ($this->allow as $column) {
            if ($data !== null && !isset($data[$column])) {
                throw new \RuntimeException(sprintf(
                    "Inserting into table %s must contain value for column %s.",
                    $this->table,
                    $column
                ));
            }

            $statement->bind($column, $data === null ? $this->{$column} : $data[$column]);
        }

        $statement->bind($columnIdentifier, $id);

        return $statement->execute();
    }

    public function make(string $name)
    {
        $model = $this->getModelNamespace($name);

        return new $model;
    }

    protected function getModelNamespace(string $name)
    {
        return sprintf('Gov\Model\%s', ucfirst($name));
    }
}
