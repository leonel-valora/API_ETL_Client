<?php

namespace App\ETL\Exceptions;

class ExtractorException extends \Exception
{
    const CODE_CONNECTION_ERROR = 100;
    const CODE_QUERY_ERROR = 101;

    protected $context;

    public function __construct($message, $code = 0, $context = [])
    {
        parent::__construct($message, $code);
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function __toString()
    {
        $contextStr = !empty($this->context) ? "\nContext: " . json_encode($this->context) : "";
        return "ExtractorException ({$this->code}): {$this->message}{$contextStr}";
    }
}
