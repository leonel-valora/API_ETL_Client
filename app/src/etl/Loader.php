<?php

namespace App\ETL;

use DataStorege;
use Exception;

class Loader
{
    private $rowMappings;
    private $targetConnectionInfo;
    private $dsHandler;

    function __construct(DataStorege $dsHandler)
    {
        $this->dsHandler = $dsHandler;
    }


    function saveData($targetSourceName, $data, $isTransaction = false): int
    {
        $result = 0;

        try {
            $result = $this->dsHandler->insert($targetSourceName, $data);
        } catch (Exception $e) {
            throw $e;
        }

        return $result;
    }
}
