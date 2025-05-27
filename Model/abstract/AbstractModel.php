<?php
namespace Model\abstract;

abstract class AbstractModel
{
    protected $conn;
    protected $tableName;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    abstract public function create($data);
    abstract public function read($id);
    abstract public function update($id, $data);
    abstract public function delete($id);
}