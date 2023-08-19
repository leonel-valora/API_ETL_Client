<?php
interface DataStorege
{
    public function createStorage(string $storageName, array $columns): bool;

    public function insert(string $storageName, array $data): int;
    public function read(string $storageName, array|string $colums);
    public function update(string $storageName, $data, $condition);
    public function delete(string $storageName, $condition);
}
