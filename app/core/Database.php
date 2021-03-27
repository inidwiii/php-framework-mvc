<?php

namespace Gov\Core;

use Gov\Core\Config;

class Database
{
    protected $handler;

    protected $statement;

    protected $driver;

    protected $host;

    protected $port;

    protected $user;

    protected $pass;

    protected $name;

    protected $char;

    public function __construct(Config $config)
    {
        $this->driver = $config->get('database.driver', 'mysql');
        $this->host = $config->get('database.host', 'localhost');
        $this->port = $config->get('database.port', 3306);
        $this->user = $config->get('database.user', 'root');
        $this->pass = $config->get('database.pass', '');
        $this->name = $config->get('database.name');
        $this->char = $config->get('database.', 'utf8mb4');

        $this->connect();
    }

    public function bind(string $param, $value, $type = null): Database
    {
        if ($type === null) {
            switch (true) {
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
                    break;
            }
        }

        $this->statement->bindValue($param, $value, $type);

        return $this;
    }

    public function execute(): bool
    {
        return $this->statement->execute();
    }

    public function fetch(?string $model = null)
    {
        $this->execute();

        return $this->statement->fetchObject(
            $model ?? \stdClass::class
        );
    }

    public function fetchAll(?string $model = null)
    {
        $this->execute();

        return $this->statement->fetchAll(
            \PDO::FETCH_CLASS,
            $model ?? \stdClass::class
        );
    }

    public function prepare(string $sql): Database
    {
        if ($this->handler !== null) {
            $this->statement = $this->handler->prepare($sql);

            return $this;
        }

        throw new \RuntimeException(
            "Can't prepare SQL syntax due to unresolved Handler."
        );
    }

    protected function connect(): void
    {
        try {
            $this->handler = new \PDO(
                $this->getDsn(),
                $this->user,
                $this->pass,
                $this->getOptions()
            );
        } catch (\PDOException $exception) {
            die($exception->getMessage());
        }
    }

    protected function getDsn(): string
    {
        return sprintf(
            '%s:host=%s:%s;dbname=%s;charset=%s',
            $this->driver,
            $this->host,
            $this->port,
            $this->name,
            $this->char
        );
    }

    protected function getOptions(): array
    {
        return [
            \PDO::ATTR_CASE => \PDO::CASE_LOWER,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_NATURAL,
            \PDO::ATTR_PERSISTENT => true
        ];
    }
}
