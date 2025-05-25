<?php
// Model/interfaces/IHealthRecord.php
namespace Model\interfaces;

interface IHealthRecord
{
    public function addRecord($data);
    public function getRecord($id);
    public function updateRecord($id, $data);
    public function getDescription();
    public function getData();
}