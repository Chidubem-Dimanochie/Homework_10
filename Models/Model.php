<?php

namespace Homework_10\Models;

use PDO;

abstract class Model
{
    // Database connection method
    private function connect()
    {
        $string = "mysql:hostname=" . DB_HOST . ";dbname=" . DB_NAME;
        $con = new PDO($string, DB_USER, DB_PASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);
        if ($check) {
            // Return results as an associative array
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        return false;
    }

    public function fetchAll($query)
    {
        $connectedPDO = $this->connect();
        $statementObject = $connectedPDO->query($query);
        return $statementObject->fetchAll();
    }

    public function fetchAllWithParams($query, $data = [])
    {
        $connection = $this->connect();
        $statementObject = $connection->prepare($query);
        $successOrFail = $statementObject->execute($data);
        if ($successOrFail) {
            $result = $statementObject->fetchAll();
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        return false;
    }
}
