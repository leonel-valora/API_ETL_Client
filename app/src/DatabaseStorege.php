<?php

use Medoo\Medoo;

class DatabaseStorage implements DataStorege
{
    private $dbHandler;


    public function __construct()
    {
        $this->dbHandler = new Medoo([
            'type' => $_ENV['DB_ENGINE'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
        ]);
    }

    /**
     * Create a new storage of row and column type.
     * @param string $storageName name to be assigned.
     * @param array $columns name of the columns,
     * and attributes they should contain (if applicable).
     * @throws Exception
     */
    public function createStorage(string $storageName, array $columns): bool
    {
        $result = $this->dbHandler->create($storageName, $columns);

        $this->handleErrors();

        return true;
    }

    /**
     * Insert new record(s) to the indicate storage.
     * @param string $storageName name of the target storage.
     * @param array $data records to save.
     * @throws Exception
     */
    public function insert(string $storageName, array $data): int
    {
        $result = $this->dbHandler->insert($storageName, $data);

        $this->handleErrors();

        return $result->rowCount();
    }

    /**
     * @param string $storageName name of the target table.
     * @param array|string $columns name of the columns to retrive,
     * array with the names as items or a string with the names separated by commas.
     * @throws Exception
     */
    public function read(string $storageName, array|string $columns = '*'): array|null
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }

        $result = $this->dbHandler->select($storageName, $columns);

        $this->handleErrors();

        return $result;
    }

    /**
     * Update a record by id or condition.
     * @param string $storageName name of the target source.
     * @param array $condition condition to update.
     */
    public function update(string $storageName, $data, $condition)
    {
        $result = $this->dbHandler->update($storageName, $data, $condition);

        $this->handleErrors();

        return $result->rowCount();
    }

    /**
     * Delete a record by id or condition.
     * @param string $storageName name of the target source.
     * @param array $condition condition to update..
     */
    public function delete(string $storageName, $condition)
    {
        $result = $this->dbHandler->delete($storageName, $condition);

        $this->handleErrors();

        return $result->rowCount();
    }

    /**
     * checks for error in the previous call to the data storage
     * @throws Exception
     */
    private function handleErrors()
    {
        if ($this->dbHandler->error) {
            throw new Exception($this->dbHandler->error);
        }
    }
}
